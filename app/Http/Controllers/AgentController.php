<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Call;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketEmailNotification;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function dashboard()
    {
        $recentCalls = Call::where('user_id', auth()->id())->latest()->take(5)->get();
        $openTickets = Ticket::where('user_id', auth()->id())->where('status', 'open')->get();
        return view('agent.dashboard', compact('recentCalls', 'openTickets'));
    }

    public function calls()
    {
        $calls = Call::where('user_id', auth()->id())->latest()->paginate(10);
        return view('agent.calls.index', compact('calls'));
    }

    public function createCall()
    {
        return view('agent.calls.create');
    }

    public function storeCall(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'duration' => 'required|integer',
            'subject' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $call = Call::create($validated);

        return redirect()->route('agent.calls')->with('success', 'Appel enregistré avec succès.');
    }

    public function tickets(Request $request)
    {
        $query = Ticket::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $tickets = $query->where('user_id', auth()->id())->latest()->paginate(10);

        return view('agent.tickets.index', compact('tickets'));
    }

    public function showTicket(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('agent.tickets.show', compact('ticket'));
    }

    public function updateTicket(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update($validated);

        // Notification existante
        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewTicketNotification($ticket));
        }

        // Nouvelle notification par email
        $ticket->user->notify(new TicketEmailNotification($ticket, 'updated'));
        foreach ($supervisors as $supervisor) {
            try {
                $supervisor->notify(new NewTicketNotification($ticket));
                $supervisor->notify(new TicketEmailNotification($ticket, 'updated'));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de notification : ' . $e->getMessage());
            }
        }

        return redirect()->route('agent.tickets.show', $ticket)->with('success', 'Ticket mis à jour avec succès.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $ticket->comments()->save($comment);

        return redirect()->route('agent.tickets.show', $ticket)->with('success', 'Commentaire ajouté avec succès.');
    }


    public function createTicket()
    {
        $calls = Call::where('user_id', auth()->id())->whereDoesntHave('ticket')->get();
        return view('agent.tickets.create', compact('calls'));
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'call_id' => 'required|exists:calls,id',
            'status' => 'required|in:open,in_progress,closed',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $validated['user_id'] = auth()->id();
        $ticket = Ticket::create($validated);

        
        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewTicketNotification($ticket));
        }

    try {
        Log::info('Tentative d\'envoi de notification à l\'agent : ' . $ticket->user->email);
        $ticket->user->notify(new TicketEmailNotification($ticket, 'created'));
        Log::info('Notification envoyée à l\'agent');
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'envoi de la notification à l\'agent : ' . $e->getMessage());
    }

    $supervisors = User::where('role', 'supervisor')->get();
    foreach ($supervisors as $supervisor) {
        try {
            Log::info('Tentative d\'envoi de notification au superviseur : ' . $supervisor->email);
            $supervisor->notify(new TicketEmailNotification($ticket, 'created'));
            Log::info('Notification envoyée au superviseur');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification au superviseur : ' . $e->getMessage());
        }
    }

        return redirect()->route('agent.tickets')->with('success', 'Ticket créé avec succès.');
    }

    /* public function updateTicket(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'description' => 'required|string',
        ]);

        $ticket->update($validated);

        return redirect()->route('agent.tickets')->with('success', 'Ticket mis à jour avec succès.');
    } */
}

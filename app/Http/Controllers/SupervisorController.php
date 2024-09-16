<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        $totalCalls = Call::count();
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'open')->count();
        $agents = User::where('role', 'agent')->count();

        $recentCalls = Call::with('user')->latest()->take(5)->get();
        $recentTickets = Ticket::with('user')->latest()->take(5)->get();

        return view('supervisor.dashboard', compact('totalCalls', 'totalTickets', 'openTickets', 'agents', 'recentCalls', 'recentTickets'));
    }

    public function calls(Request $request)
    {
        $query = Call::with('user');

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $calls = $query->latest()->paginate(15);

        return view('supervisor.calls', compact('calls'));
    }

    public function tickets(Request $request)
    {
        $query = Ticket::with('user', 'comments');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(15);

        return view('supervisor.tickets', compact('tickets'));
    }

    /* public function tickets(Request $request)
    {
        $query = Ticket::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(15);

        return view('supervisor.tickets', compact('tickets'));
    } */

    public function reports()
    {
        $callsPerDay = Call::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $ticketStatusDistribution = Ticket::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $agentPerformance = Call::select('user_id', DB::raw('count(*) as total_calls'), DB::raw('avg(duration) as avg_duration'))
            ->with('user')
            ->groupBy('user_id')
            ->get();

        return view('supervisor.reports', compact('callsPerDay', 'ticketStatusDistribution', 'agentPerformance'));
    }


    

    public function showTicket(Ticket $ticket)
    {
        $ticket->load('user', 'comments.user');
        return view('supervisor.tickets.show', compact('ticket'));
    }

    public function updateTicket(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update($validated);

        return redirect()->route('supervisor.tickets.show', $ticket)->with('success', 'Ticket mis à jour avec succès.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $ticket->comments()->save($comment);

        return redirect()->route('supervisor.tickets.show', $ticket)->with('success', 'Commentaire ajouté avec succès.');
    }

    public function notifications()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('supervisor.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect()->back();
    }
}

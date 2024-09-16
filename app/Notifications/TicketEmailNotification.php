<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TicketEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $action;

    public function __construct(Ticket $ticket, $action)
    {
        $this->ticket = $ticket;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        Log::info('Méthode via() appelée pour : ' . $notifiable->email);
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        Log::info('Début de la méthode toMail pour : ' . $notifiable->email);

        $subject = $this->action === 'created' ? 'Nouveau ticket créé' : 'Ticket mis à jour';
        $content = "Un nouveau ticket a été créé.\n";
        $content .= "ID du ticket: " . $this->ticket->id . "\n";
        $content .= "Description: " . $this->ticket->description . "\n";
        $content .= "Statut: " . $this->ticket->status . "\n";
        $content .= "Priorité: " . $this->ticket->priority;

        try {
            Mail::raw($content, function ($message) use ($notifiable, $subject) {
                $message->to($notifiable->email)
                    ->subject($subject);
            });
            Log::info('Email envoyé avec succès à : ' . $notifiable->email);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email à ' . $notifiable->email . ': ' . $e->getMessage());
        }

        return null;
    }
}

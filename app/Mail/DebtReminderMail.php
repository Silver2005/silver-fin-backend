<?php

namespace App\Mail;

use App\Models\Debt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DebtReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $debt;

    /**
     * Create a new message instance.
     */
    public function __construct(Debt $debt)
    {
        // On injecte la dette pour y avoir accès dans la vue
        $this->debt = $debt;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rappel d\'échéance - SILVER FIN',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.debt_reminder',
        );
    }
}
<?php

namespace App\Mail;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent when a winner registers a participation (regle.md, sections 2 and 8).
 */
class ParticipationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Participation $participation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ta participation au tirage K-Way × Quézac est enregistrée',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.participation-confirmed',
            with: [
                'firstName' => $this->participation->first_name,
                'rulesUrl' => route('legal.rules'),
            ],
        );
    }
}

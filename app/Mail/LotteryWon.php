<?php

namespace App\Mail;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent by `lottery:draw` to each winner of the final draw (regle.md, section 5).
 */
class LotteryWon extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Participation $participation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu as gagné un lot K-Way × Quézac !',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.lottery-won',
            with: [
                'firstName' => $this->participation->first_name,
                'prizeRank' => $this->participation->prize_rank,
                'prize' => config('game.prizes')[$this->participation->prize_rank],
                'rulesUrl' => url('/reglement'),
            ],
        );
    }
}

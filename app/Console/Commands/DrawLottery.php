<?php

namespace App\Console\Commands;

use App\Mail\LotteryWon;
use App\Models\Participation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('lottery:draw')]
#[Description('Tire au sort les gagnants des lots parmi les participations')]
class DrawLottery extends Command
{
    public function handle(): int
    {
        if (Participation::whereNotNull('drawn_at')->exists()) {
            $this->error('Le tirage a déjà eu lieu : il ne peut pas être relancé.');

            return self::FAILURE;
        }

        $candidates = Participation::all();
        $winners = [];

        foreach (config('game.prizes') as $rank => $prize) {
            if ($candidates->isEmpty()) {
                break;
            }

            $winner = $candidates->values()[random_int(0, $candidates->count() - 1)];
            $winner->update(['prize_rank' => $rank, 'drawn_at' => now()]);
            Mail::to($winner->email)->send(new LotteryWon($winner));
            $winners[] = [$rank, $prize, $winner->first_name, $winner->email];

            // One prize per e-mail: this person's other participations leave the draw.
            $candidates = $candidates->where('email', '!=', $winner->email);
        }

        if (! $winners) {
            $this->warn('Aucune participation : aucun lot n’a été attribué.');

            return self::FAILURE;
        }

        $this->table(['Rang', 'Lot', 'Prénom', 'E-mail'], $winners);

        return self::SUCCESS;
    }
}

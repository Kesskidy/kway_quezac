<?php

namespace App\Console\Commands;

use App\Mail\LotteryWon;
use App\Models\Participation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Final prize draw (regle.md, section 5 "Le tirage final des lots").
 *
 * - One winner per prize of config('game.prizes'), drawn with random_int among the participations.
 *   Each participation is one ticket: a person with several winning codes has several chances.
 * - One prize per e-mail: once an e-mail wins, its other participations are set aside.
 * - Refuses to run if a draw already took place (a participation has a drawn_at).
 */
#[Signature('lottery:draw')]
#[Description('Tire au sort les gagnants des lots parmi les participations')]
class DrawLottery extends Command
{
    public function handle(): int
    {
        $prizes = config('game.prizes');
        ksort($prizes);

        $winners = DB::transaction(function () use ($prizes) {
            if (Participation::whereNotNull('drawn_at')->lockForUpdate()->exists()) {
                return null;
            }

            /** @var Collection<int, Participation> $candidates */
            $candidates = Participation::orderBy('id')->lockForUpdate()->get(['id', 'first_name', 'email']);
            $winners = [];

            foreach (array_keys($prizes) as $rank) {
                if ($candidates->isEmpty()) {
                    break;
                }

                $winner = $candidates->values()[random_int(0, $candidates->count() - 1)];
                $winner->update(['prize_rank' => $rank, 'drawn_at' => now()]);
                $winners[$rank] = $winner;

                // One prize per e-mail: the other participations of this person are set aside.
                $candidates = $candidates->reject(fn (Participation $p) => $p->email === $winner->email);
            }

            return $winners;
        });

        if ($winners === null) {
            $this->error('Le tirage a déjà eu lieu : il ne peut pas être relancé.');

            return self::FAILURE;
        }

        if ($winners === []) {
            $this->warn('Aucune participation : aucun lot n’a été attribué.');

            return self::FAILURE;
        }

        $this->table(
            ['Rang', 'Lot', 'Prénom', 'E-mail'],
            collect($winners)->map(fn (Participation $p, int $rank) => [$rank, $prizes[$rank], $p->first_name, $p->email])->all(),
        );

        $unawarded = count($prizes) - count($winners);
        if ($unawarded > 0) {
            $this->warn("Pas assez de participants : {$unawarded} lot(s) non attribué(s).");
        }

        // Sent after the commit: only saved winners are notified (regle.md, section 5).
        foreach ($winners as $winner) {
            Mail::to($winner->email)->send(new LotteryWon($winner));
        }

        $this->info('Tirage terminé : '.count($winners).' gagnant(s), prévenu(s) par e-mail.');

        return self::SUCCESS;
    }
}

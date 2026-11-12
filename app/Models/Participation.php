<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipationFactory> */
    use HasFactory;

    protected $fillable = [
        'game_session_id',
        'first_name',
        'email',
        'consented_at',
        'prize_rank',
        'drawn_at',
    ];

    protected function casts(): array
    {
        return [
            'consented_at' => 'datetime',
            'prize_rank' => 'integer',
            'drawn_at' => 'datetime',
        ];
    }

    /**
     * E-mails are compared in lower case, without surrounding spaces,
     * so that the "one prize per e-mail" rule cannot be bypassed.
     */
    public static function normalizeEmail(string $email): string
    {
        return mb_strtolower(trim($email));
    }

    /**
     * @return BelongsTo<GameSession, $this>
     */
    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = ['game_session_id', 'first_name', 'email', 'consented_at', 'prize_rank', 'drawn_at'];

    protected function casts(): array
    {
        return ['consented_at' => 'datetime', 'prize_rank' => 'integer', 'drawn_at' => 'datetime'];
    }

    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }
}

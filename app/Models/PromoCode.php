<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PromoCode extends Model
{
    /** @use HasFactory<\Database\Factories\PromoCodeFactory> */
    use HasFactory;

    protected $fillable = ['code', 'used_at'];

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
        ];
    }

    /**
     * Codes are stored and compared in upper case, without surrounding spaces.
     */
    public static function normalize(string $code): string
    {
        return strtoupper(trim($code));
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * @return HasOne<GameSession, $this>
     */
    public function gameSession(): HasOne
    {
        return $this->hasOne(GameSession::class);
    }
}

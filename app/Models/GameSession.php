<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    /** @use HasFactory<\Database\Factories\GameSessionFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'promo_code_id',
        'won',
        'scenario',
    ];

    protected function casts(): array
    {
        return [
            'won' => 'boolean',
            'scenario' => 'integer',
        ];
    }

    /**
     * The ULID goes in `token`; `id` stays an auto-increment integer.
     *
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['token'];
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    /**
     * @return BelongsTo<PromoCode, $this>
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }
}

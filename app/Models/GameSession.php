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
        'course',
        'started_at',
        'finished_at',
        'inputs',
        'fill_percent',
        'duration_ticks',
        'wheel_allowed',
        'spun_at',
        'segment',
        'won',
    ];

    protected function casts(): array
    {
        return [
            'course' => 'array',
            'inputs' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'spun_at' => 'datetime',
            'fill_percent' => 'integer',
            'duration_ticks' => 'integer',
            'wheel_allowed' => 'boolean',
            'segment' => 'integer',
            'won' => 'boolean',
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

    public function isFinished(): bool
    {
        return $this->finished_at !== null;
    }

    /**
     * Records started_at on the first call only (atomic, safe against concurrent calls).
     */
    public function markStarted(): void
    {
        if ($this->started_at !== null) {
            return;
        }

        static::whereKey($this->getKey())->whereNull('started_at')->update(['started_at' => now()]);

        $this->refresh();
    }

    /**
     * @return BelongsTo<PromoCode, $this>
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }
}

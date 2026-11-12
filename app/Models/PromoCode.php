<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'used_at'];

    protected function casts(): array
    {
        return ['used_at' => 'datetime'];
    }

    public function gameSession(): HasOne
    {
        return $this->hasOne(GameSession::class);
    }
}

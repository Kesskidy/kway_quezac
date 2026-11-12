<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'price_cents', 'image_path'];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
        ];
    }

    /**
     * Price for display, e.g. "300 €" or "39,90 €" (prices are stored in cents).
     */
    public function formattedPrice(): string
    {
        $decimals = $this->price_cents % 100 === 0 ? 0 : 2;

        return number_format($this->price_cents / 100, $decimals, ',', "\u{202F}").' €';
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

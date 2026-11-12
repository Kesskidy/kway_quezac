<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'price_cents', 'image_path'];

    public function formattedPrice(): string
    {
        return number_format($this->price_cents / 100, $this->price_cents % 100 ? 2 : 0, ',', ' ').' €';
    }
}

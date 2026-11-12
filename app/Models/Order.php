<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'first_name', 'email', 'size'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

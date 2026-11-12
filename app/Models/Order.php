<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    protected $fillable = ['first_name', 'email', 'size'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Merchant extends Model
{
    use HasApiTokens, HasFactory;

    protected $guarded = [];

    public function products() {
        return $this->hasMany(Product::class);
    }
}

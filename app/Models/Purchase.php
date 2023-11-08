<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['customer_name', 'total_amount'];

    // Purchase.php
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

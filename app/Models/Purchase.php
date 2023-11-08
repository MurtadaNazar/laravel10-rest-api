<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['customer_name', 'total_amount', 'total_price', 'products'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

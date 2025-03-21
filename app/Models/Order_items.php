<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_items extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'order_id', 'burger_id', 'quantity', 'price'
    ];

    // Relation avec la commande
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    // Relation avec le burger
    public function burger()
    {
        return $this->belongsTo(Burgers::class);
    }
}

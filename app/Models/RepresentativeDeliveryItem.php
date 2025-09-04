<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepresentativeDeliveryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'item_id',
        'quantity',
        'unit_id',
        'weight',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

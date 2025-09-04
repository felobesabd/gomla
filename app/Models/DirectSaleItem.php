<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'item_id',
        'unit_id',
        'quantity',
        'weight',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'weight' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(DirectSale::class, 'sale_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}

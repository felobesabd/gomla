<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'unit_id',
        'container_id',
        'transaction_type',
        'quantity',
        'weight',
        'source_type',
        'source_id',
        'destination_type',
        'destination_id',
        'qty_before',
        'qty_after',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'transaction_type' => 'integer',
        'quantity' => 'integer',
        'weight' => 'decimal:2',
        'qty_before' => 'decimal:4',
        'qty_after' => 'decimal:4',
        'delivered_at' => 'datetime',
    ];

    const TRANSACTION_IN = 0;  // IN
    const TRANSACTION_OUT = 1; // OUT
}

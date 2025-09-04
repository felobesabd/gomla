<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepresentativeDelivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'representative_id',
        'document_no',
        'delivered_at',
        'status',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    public function items()
    {
        return $this->hasMany(RepresentativeDeliveryItem::class, 'delivery_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($delivery) {
            if (empty($delivery->document_no)) {
                $delivery->document_no = self::generateDocumentNo();
            }
        });
    }

    public static function generateDocumentNo(): string
    {
        $prefix = 'DLV';
        $year   = now()->format('Y');

        // Find last record for this year
        $last = self::whereYear('created_at', now()->year)
            ->orderByDesc('id')
            ->first();

        $lastNumber = $last ? intval(substr($last->document_no, -4)) : 0;

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$nextNumber}";
    }
}

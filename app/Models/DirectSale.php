<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_no',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'total_amount',
        'sold_at',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

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
        $prefix = 'DIS';
        $year   = now()->format('Y');

        // Find last record for this year
        $last = self::whereYear('created_at', now()->year)
            ->orderByDesc('id')
            ->first();

        $lastNumber = $last ? intval(substr($last->document_no, -4)) : 0;

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$nextNumber}";
    }

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_CANCELLED = 2;

    public function items()
    {
        return $this->hasMany(DirectSaleItem::class, 'sale_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_CONFIRMED => 'تم البيع',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير معروف'
        };
    }

    public function isConfirmed()
    {
        return $this->status == self::STATUS_CONFIRMED;
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}

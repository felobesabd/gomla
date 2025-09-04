<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Container extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'container_number',
        'supplier_id',
        'item_id',
        'unit_id',
        'number',
        'qty',
        'arrival_date',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    const STATUS_PENDING = 0;
    const STATUS_ARRIVED = 1;
    const STATUS_CANCELLED = 2;

    /*public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'قيد الانتظار';
            case self::STATUS_ARRIVED:
                return 'وصل';
            case self::STATUS_CANCELLED:
                return 'تم الإلغاء';
            default:
                return 'غير معروف';
        }
    }*/
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'path'
    ];

    protected function path(): Attribute
    {
        return Attribute::make(
            get: function (?string $value, array $attributes) {
                $path = $attributes['path'] ?? null;
                return  $path ? url('uploads') . '/' . $path : null;
            }
        );
    }
}

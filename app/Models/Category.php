<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'group_id',
    ];

    public function getGroupName($group_id)
    {
        $group = self::query()
            ->join('groups', 'groups.id', '=', 'categories.group_id')
            ->where('groups.id', $group_id)
            ->value('groups.group_name');

        return $group ?: '---';
    }
}

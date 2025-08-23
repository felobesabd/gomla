<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function store_location()
    {
        return $this->belongsTo(StoreLocation::class, 'store_location_id');
    }

    /*public function getCategoryName($category_id)
    {
        $category = self::query()
            ->join('categories', 'categories.id', '=', 'item_categories.category_id')
            ->where('categories.id', $category_id)
            ->value('categories.category_name');

        return $category ?: '---';
    }

    public function getGroupName($group_id)
    {
        $group = self::query()
            ->join('groups', 'groups.id', '=', 'item_categories.group_id')
            ->where('groups.id', $group_id)
            ->value('groups.group_name');

        return $group ?: '---';
    }

    public function getUnitName($unit_id)
    {
        $unit = self::query()
            ->join('units', 'units.id', '=', 'item_categories.unit_id')
            ->where('units.id', $unit_id)
            ->value('units.name');

        return $unit ?: '---';
    }

    public function getNameById($table, $id, $idColumn, $selfId, $nameColumn)
    {
        $name = self::query()
            ->join($table, "$table.$idColumn", '=', "item_categories.$selfId")
            ->where("$table.$idColumn", $id)
            ->value("$table.$nameColumn");

        return $name ?: '---';
    }*/
}

<?php
namespace App\Services;

use App\Models\Unit;
use Yajra\Datatables\Datatables;

class UnitService
{
    public function getUnits(): object
    {
        $model = Unit::orderBy('created_at', 'desc')->get();
        return $this->getTableData(model: $model);
    }

    public function createUnit($data): object
    {
        $model = Unit::create($data);
        return $model;
    }

    public function updateUnit(int $id, array $data): bool
    {
        $unit = Unit::findOrFail($id);
        return $unit->fill($data)->save();
    }

    public function deleteUnit($id): bool
    {
        $unit = Unit::findOrFail($id);
        return $unit->delete();
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->addColumn('action', function ($row) {
                $res = '
                    <a href="' . route('admin.units.edit', ['unit' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        تعديل
                    </a>
                    <a href="' . route('admin.units.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        مسح
                    </a>
                    ';

                return $res;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

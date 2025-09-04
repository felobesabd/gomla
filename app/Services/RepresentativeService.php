<?php
namespace App\Services;

use App\Models\Representative;
use Yajra\Datatables\Datatables;

class RepresentativeService
{
    public function getRepresentatives(): object
    {
        $model = Representative::orderBy('created_at', 'desc')->get();
        return $this->getTableData(model: $model);
    }

    public function createRepresentative($data): object
    {
        $model = Representative::create($data);
        return $model;
    }

    public function updateRepresentative(int $id, array $data): bool
    {
        $unit = Representative::findOrFail($id);
        return $unit->fill($data)->save();
    }

    public function deleteRepresentative($id): bool
    {
        $unit = Representative::findOrFail($id);
        return $unit->delete();
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->addColumn('action', function ($row) {
                $res = '
                    <a href="' . route('admin.representatives.edit', ['representative' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        تعديل
                    </a>
                    <a href="' . route('admin.representatives.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        مسح
                    </a>
                    ';

                return $res;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

<?php
namespace App\Services;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;

class SupplierService
{
    public function getSuppliers(): object
    {
        $model = Supplier::orderBy('created_at', 'desc')->get();
        return $this->getTableData(model: $model);
    }

    public function createSupplier($data): object
    {
        DB::beginTransaction();
        try {
            $model = Supplier::create($data);
            DB::commit();
            return $model;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateSupplier(int $id, array $data): bool
    {
        $model = Supplier::findOrFail($id);
        DB::beginTransaction();
        try {
            DB::commit();
            return $model->fill($data)->save();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteSupplier($id): bool
    {
        $driver = Supplier::findOrFail($id);
        return $driver->delete();
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->addColumn('action', function ($row) {
                $res = '
                    <a href="' . route('admin.suppliers.edit', ['supplier' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        تعديل
                    </a>
                    <a href="' . route('admin.suppliers.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        مسح
                    </a>
                    ';
                return $res;
            })
            /*
             * <a href="' . route('admin.suppliers.show', ['supplier' => $row->id]) . '" class="btn btn-info" onclick="return true;">
                        View
                    </a>
             * */
            ->rawColumns(['action'])
            ->make(true);
    }
}

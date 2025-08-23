<?php
namespace App\Services;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\Datatables\Datatables;

class ItemsService
{
    public function getItemCats(Request $request): object
    {
        $data = Item::orderBy('created_at', 'DESC')->get();
        return $this->getTableData(model: $data);
    }

    public function getReports(Request $request): object
    {
        $query = Item::select('item_categories.*')->orderBy('item_categories.created_at', 'DESC');

        if ($request->filled('from_date')) {
            $query->where('item_categories.created_at', '>=', \Carbon\Carbon::parse($request->from_date)->startOfDay());
        }

        if ($request->filled('to_date')) {
            $query->where('item_categories.created_at', '<=', \Carbon\Carbon::parse($request->to_date)->endOfDay());
        }

        if ($request->filled('status') && $request->status != -1) {
            $query->where('item_categories.used', $request->status);
        }

        if ($request->filled('country_manufacture') && $request->country_manufacture != 0) {
            $query->where('item_categories.country_manufacture', $request->country_manufacture);
        }

        if ($request->filled('store_location') && $request->store_location != 0) {
            $query->where('item_categories.store_location_id', $request->store_location);
        }

        $model = $query->get();

        return $this->getTableData(model: $model);
    }

    public function createItemCat($data): object
    {
        $model = Item::create($data);
        return $model;
    }

    public function searchItems(Request $request)
    {
        $search = $request->get('term');

        $items = Item::where('part_no', 'LIKE', '%' . $search . '%')->pluck('part_no');
        return $items;
    }

    public function updateItemCat(int $id, array $data): bool
    {
        $itemCat = Item::findOrFail($id);
        return $itemCat->fill($data)->save();
    }

    public function deleteItemCat($id): bool
    {
        $itemCat = Item::findOrFail($id);
        return $itemCat->delete();
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->addColumn('action', function ($row) {
                $res = '
                    <a href="' . route('admin.items.edit', ['item' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        تعديل
                    </a>
                    <a href="' . route('admin.items.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        مسح
                    </a>
                    ';
                return $res;
            })

            ->rawColumns(['action', 'used'])
            ->make(true);
    }
}

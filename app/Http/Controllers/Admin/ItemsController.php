<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItemsRequest;
use App\Imports\ItemsImport;
use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\Services\ItemsService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemsController
{
    protected $itemService;

    public function __construct(ItemsService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('items.list');

        if ($request->ajax()) {
            return $this->itemService->getItemCats($request);
        }

        return view('admin.items.index');
    }

    public function report(Request $request)
    {
        //checkUserHasRolesOrRedirect('items.list');

        if ($request->ajax()) {
            return $this->itemService->getReports($request);
        }

        $store_locations = StoreLocation::all();

        return view('admin.all_reports.inventory', compact('store_locations'));
    }

    public function create()
    {
        //checkUserHasRolesOrRedirect('items.add');
        return view('admin.items.create');
    }

    public function store(ItemsRequest $request)
    {
        $itemCat = $this->itemService->createItemCat(data: $request->all());
        return redirect()->route('admin.items.index')->with('success', 'تم الإنشاء بنجاح');
    }

    public function searchByPartNo(Request $request) {
        return $this->itemService->searchItems($request);
    }

    public function edit(Request $request, $id)
    {
        //checkUserHasRolesOrRedirect('items.edit');

        $item = Item::findOrFail($id);

        return view('admin.items.edit', compact('item'));
    }

    public function update(ItemsRequest $request, $id)
    {
        $itemCat = $this->itemService->updateItemCat(id: $id, data: $request->all());
        return redirect()->route('admin.items.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('items.delete');

        $itemCat = $this->itemService->deleteItemCat($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }

    public function import(Request $request)
    {
        //checkUserHasRolesOrRedirect('items.add');

        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls'
            ]);

            $categories = Category::all();
            $groups = Group::all();
            $units = Unit::all();

            Excel::import(new ItemsImport($categories, $groups, $units), $request->file('file'));

            return redirect()->back()->with('success', 'Items imported successfully!');
        } catch (\Throwable $e) {
            \Log::error('Import failed con: ' . $e->getMessage());

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

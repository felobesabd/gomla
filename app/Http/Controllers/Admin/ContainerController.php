<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UnitRequest;
use App\Models\Container;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Unit;
use App\Services\ContainerService;
use Illuminate\Http\Request;

class ContainerController
{
    protected $containerService;

    public function __construct(ContainerService $containerService)
    {
        $this->containerService = $containerService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('unit.list');
        if ($request->ajax()) {
            return $this->containerService->getContainers();
        }

        return view('admin.containers.index');
    }

    public function create()
    {
        //checkUserHasRolesOrRedirect('unit.add');
        $items      = Item::all();
        $suppliers  = Supplier::all();
        $units      = Unit::all();
        return view('admin.containers.create', compact('items', 'units', 'suppliers'));
    }

    public function store(Request $request)
    {
        $unit = $this->containerService->createContainer(data: $request->all());
        return redirect()->route('admin.containers.index')->with('success', 'تم الإنشاء بنجاح');
    }

    public function edit(Request $request, $id)
    {
        //checkUserHasRolesOrRedirect('unit.edit');
        $container  = Container::findOrFail($id);
        $items      = Item::all();
        $suppliers  = Supplier::all();
        $units      = Unit::all();
        return view('admin.containers.edit', compact('container', 'items', 'units', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $unit = $this->containerService->updateContainer(id: $id, data: $request->all());
        return redirect()->route('admin.containers.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('unit.delete');
        $unit = $this->containerService->deleteContainer($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }

    public function confirmContainer($id)
    {
        //checkUserHasRolesOrRedirect('direct_sale.confirm');
        return $this->containerService->confirmContainer($id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use App\Services\UnitService;
use Illuminate\Http\Request;

class UnitController
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('unit.list');
        if ($request->ajax()) {
            return $this->unitService->getUnits();
        }

        return view('admin.units.index');
    }

    public function create()
    {
        //checkUserHasRolesOrRedirect('unit.add');
        return view('admin.units.create');
    }

    public function store(UnitRequest $request)
    {
        $unit = $this->unitService->createUnit(data: $request->all());
        return redirect()->route('admin.units.index')->with('success', 'تم الإنشاء بنجاح');
    }

    public function edit(Request $request, $id)
    {
        //checkUserHasRolesOrRedirect('unit.edit');
        $unit = Unit::findOrFail($id);
        return view('admin.units.edit', compact('unit'));
    }

    public function update(UnitRequest $request, $id)
    {
        $unit = $this->unitService->updateUnit(id: $id, data: $request->all());
        return redirect()->route('admin.units.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('unit.delete');
        $unit = $this->unitService->deleteUnit($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}

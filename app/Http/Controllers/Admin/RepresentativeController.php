<?php

namespace App\Http\Controllers\Admin;

use App\Models\Representative;
use App\Services\RepresentativeService;
use Illuminate\Http\Request;

class RepresentativeController
{
    protected $representativeService;

    public function __construct(RepresentativeService $representativeService)
    {
        $this->representativeService = $representativeService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('unit.list');
        if ($request->ajax()) {
            return $this->representativeService->getRepresentatives();
        }

        return view('admin.representatives.index');
    }

    public function create()
    {
        //checkUserHasRolesOrRedirect('unit.add');
        return view('admin.representatives.create');
    }

    public function store(Request $request)
    {
        $unit = $this->representativeService->createRepresentative(data: $request->all());
        return redirect()->route('admin.representatives.index')->with('success', 'تم الإنشاء بنجاح');
    }

    public function edit(Request $request, $id)
    {
        //checkUserHasRolesOrRedirect('unit.edit');
        $representative = Representative::findOrFail($id);
        return view('admin.representatives.edit', compact('representative'));
    }

    public function update(Request $request, $id)
    {
        $unit = $this->representativeService->updateRepresentative(id: $id, data: $request->all());
        return redirect()->route('admin.representatives.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('unit.delete');
        $unit = $this->representativeService->deleteRepresentative($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}

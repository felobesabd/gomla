<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\SupplierContact;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('supplier.list');
        if ($request->ajax()) {
            return $this->supplierService->getSuppliers();
        }

        return view('admin.suppliers.index');
    }

    public function create()
    {
        //checkUserHasRolesOrRedirect('supplier.add');
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $supplier = $this->supplierService->createSupplier(data: $request->all());
        return redirect()->route('admin.suppliers.index')->with('success', 'تم الإنشاء بنجاح');
    }

    public function show($id)
    {
        //checkUserHasRolesOrRedirect('supplier.edit');

        $supplier = Supplier::findOrFail($id);
        $supplierContacts = SupplierContact::where('supplier_id', $supplier->id)->get();

        return view('admin.suppliers.view', compact('supplier', 'supplierContacts'));
    }

    public function edit(Request $request, $id)
    {
        //checkUserHasRolesOrRedirect('supplier.edit');
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = $this->supplierService->updateSupplier(id: $id, data: $request->all());
        return redirect()->route('admin.suppliers.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('supplier.delete');
        $supplier = $this->supplierService->deleteSupplier($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}

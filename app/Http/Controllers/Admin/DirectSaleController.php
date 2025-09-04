<?php

namespace App\Http\Controllers\Admin;

use App\Models\DirectSale;
use App\Services\DirectSaleService;
use Illuminate\Http\Request;

class DirectSaleController
{
    protected $saleService;

    public function __construct(DirectSaleService $saleService) {
        $this->saleService = $saleService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('direct_sale.list');
        if ($request->ajax()) {
            return $this->saleService->getDirectSales($request);
        }
        return view('admin.direct_sales.index');
    }

    public function create(Request $request, $id = null)
    {
        //checkUserHasRolesOrRedirect('direct_sale.add');
        if (!$request->isMethod('post')) {
            $data = $this->saleService->getDirectSaleData($id);
            return view('admin.direct_sales.save')->with($data);
        }

        $this->saleService->saveDirectSale(request()->all(), $id);

        return redirect()->route('admin.direct_sales.index')->with('success', 'تم الإنشاء بنجاح');
    }

    public function edit(Request $request, $id = null)
    {
        //checkUserHasRolesOrRedirect('direct_sale.edit');
        if (!$request->isMethod('post')) {
            $data = $this->saleService->getDirectSaleData($id);
            return view('admin.direct_sales.save')->with($data);
        }

        $this->saleService->saveDirectSale(request()->all(), $id);

        return redirect()->route('admin.direct_sales.index')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('direct_sale.delete');
        $this->saleService->deleteDirectSale($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }

    public function confirmSale($id)
    {
        //checkUserHasRolesOrRedirect('direct_sale.confirm');
        return $this->saleService->confirmSale($id);
    }

    public function printInvoice($id)
    {
        //checkUserHasRolesOrRedirect('direct_sale.print');
        $sale = DirectSale::with(['items', 'items.item', 'items.unit'])->findOrFail($id);
        return view('admin.direct_sales.print', compact('sale'));
    }
}

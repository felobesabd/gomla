<?php

namespace App\Http\Controllers\Admin;

use App\Models\RepresentativeDelivery;
use App\Models\Supplier;
use App\Services\RepresentativeDeliveryService;
use Illuminate\Http\Request;

class RepresentativeDeliveryController
{

    protected $deliveryService;
    public function __construct(RepresentativeDeliveryService $deliveryService) {

        $this->deliveryService = $deliveryService;
    }

    public function index(Request $request)
    {
        //checkUserHasRolesOrRedirect('accident_report.list');
        if ($request->ajax()) {
            return $this->deliveryService->getRepresentativeDelivery($request);
        }
        return view('admin.delivery.index');
    }


    public function saveRepresentativeDelivery(Request $request, $id = null)
    {
        //dd($request);
        //checkUserHasRolesOrRedirect('accident_report.add');
        if (!$request->isMethod('post')) {
            $data = $this->deliveryService->getRepresentativeDeliveryData($id);
            $suppliers = Supplier::all();
            return view('admin.delivery.save', compact('suppliers'))->with($data);
        }

        $this->deliveryService->saveRepresentativeDelivery(request()->all(), $id);

        return redirect()->route('admin.delivery.index')->with('success', 'تم الإنشاء بنجاح');
    }

/*    public function show($id)
    {
        //checkUserHasRolesOrRedirect('accident_report.show');
        $data = $this->deliveryService->getRepresentativeDeliveryData($id);
        $suppliers = Supplier::all();

        return view('admin.delivery.view', compact('suppliers'))->with($data);
    }*/


    public function destroy($id)
    {
        //checkUserHasRolesOrRedirect('accident_report.delete');
        $this->deliveryService->deleteAccidentReport($id);
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }

    public function changeStatusAndQty($id)
    {
        //checkUserHasRolesOrRedirect('accident_report.delete');
        return $this->deliveryService->changeStatusQty($id);
    }

    public function printDelivery($id)
    {
        // checkUserHasRolesOrRedirect('representative_delivery.print');
        $delivery = RepresentativeDelivery::with([
            'representative',
            'items',
            'items.item',
            'items.unit'
        ])->findOrFail($id);

        return view('admin.delivery.print', compact('delivery'));
    }
}

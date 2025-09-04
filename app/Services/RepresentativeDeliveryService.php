<?php
namespace App\Services;

use App\Models\Item;
use App\Models\Representative;
use App\Models\RepresentativeDelivery;
use App\Models\RepresentativeDeliveryItem;
use App\Models\Transaction;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class RepresentativeDeliveryService
{
    public function getRepresentativeDeliveryData($id): array
    {
        $data['items']           = Item::all();
        $data['units']           = Unit::all();
        $data['representatives'] = Representative::all();

        if (!is_null($id)){
            $data['delivery_obj']   = RepresentativeDelivery::where('id', $id)->first();
            $data['delivery_items'] = RepresentativeDeliveryItem::where('delivery_id', $id)->get();
        }

        return $data;
    }

    public function saveRepresentativeDelivery($data, $id): bool
    {
        $delivery_data['representative_id']  = $data['representative_id'];
        $delivery_data['delivered_at']       = $data['delivered_at'];
        //$delivery_data['status']             = $data['status'];

        // create
        if (is_null($id)) {
            $delivery_obj = RepresentativeDelivery::create($delivery_data);
            $id = $delivery_obj->id;
        } else {
            RepresentativeDelivery::where('id', $id)->update($delivery_data);
            RepresentativeDeliveryItem::where('delivery_id', $id)->delete();
        }

        $delivery_items_data = $this->handleDeliveryItemsData($data, $id);
        if (!empty($delivery_items_data)) {
            RepresentativeDeliveryItem::insert($delivery_items_data);
        }

        return true;
    }

    public function getRepresentativeDelivery(Request $request): object
    {
        $data = RepresentativeDelivery::orderBy('created_at', 'desc')->get();
        return $this->getTableData(model: $data);
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->editColumn('representative_id', function ($row) {
                return $row->representative ? $row->representative->name : '--';
            })
            ->editColumn('delivered_at', function ($row) {
                return $row->delivered_at->format('Y-m-d H:i');
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 0)
                    $html = '<label for="" class="text-warning">قيد الانتظار</label>';
                else if ($row->status == 1)
                    $html = '<label for="" class="text-success">مكتمل</label>';
                else
                    $html = '<label for="" class="text-danger">تم الإلغاء</label>';

                return $html;
            })
            /*->addColumn('action', function ($row) {
                $isDisabled = $row->status != 0;
                $disabledClass = $isDisabled ? 'disabled' : '';
                $onclick = $isDisabled ? 'return false;' : 'return true;';

                $res = '
                    <a href="' . route('admin.delivery.save_view', ['id' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        تعديل
                    </a>
                    <a href="' . route('admin.delivery.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        مسح
                    </a>
                    <a href="' . route('admin.delivery.status', ['id' => $row->id]) . '"
                       class="btn btn-success ' . $disabledClass . '"
                       onclick="' . $onclick . '">
                        تم التسليم
                    </a>
                    ';
                return $res;
            })*/
            ->addColumn('action', function ($row) {
                $isDisabled = $row->status != 0; // Only enable for confirmed deliveries
                $disabledClass = $isDisabled ? 'disabled' : '';

                $res = '
                    <div class="d-flex justify-content-center mb-1">
                        <a href="' . route('admin.delivery.save_view', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-primary me-1" onclick="return true;">
                            تعديل
                        </a>
                        <a href="' . route('admin.delivery.delete', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-danger" onclick="return confirmMsg();">
                            مسح
                        </a>
                    </div>
                    <div class="d-flex justify-content-center mb-1">
                        <a href="' . route('admin.delivery.status', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-success ' . $disabledClass . ' me-1"
                           onclick="return ' . ($isDisabled ? 'false' : 'true') . ';">
                            تم التسليم
                        </a>
                        <a href="' . route('admin.delivery.print', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-info print-btn"
                           target="_blank">
                            طباعة
                        </a>
                        ';

                $res .= '</div>';

                return $res;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    private function handleDeliveryItemsData($data, $delivery_id): array
    {
        $delivery_items_data = [];
        if(isset($data['item_id'])){
            foreach ($data['item_id'] as $index => $item){
                $delivery_items_data[$index]['delivery_id'] = $delivery_id;
                $delivery_items_data[$index]['item_id']     = $data['item_id'][$index];
                $delivery_items_data[$index]['unit_id']     = $data['unit_id'][$index];
                $delivery_items_data[$index]['quantity']    = $data['quantity'][$index];
                $delivery_items_data[$index]['weight']      = $data['weight'][$index];
                $delivery_items_data[$index]['created_at']  = now();
                $delivery_items_data[$index]['updated_at']  = now();
            }
        }

        return $delivery_items_data;
    }


    public function deleteAccidentReport($id): bool
    {
        $delivery = RepresentativeDelivery::findOrFail($id);
        return $delivery->delete();
    }

    public function changeStatusQty($id): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $delivery       = RepresentativeDelivery::findOrFail($id);
            $deliveryItems  = RepresentativeDeliveryItem::where('delivery_id', $id)->get();

            // 1. Change status
            $delivery->update(['status' => 1]);

            // loop items
            foreach ($deliveryItems as $deliveryItem) {
                // get item
                $item = Item::where('id', $deliveryItem->item_id)->first();

                // calculating the final quantity after deducting the quantity given to the representative
                $newWeight = $item->weight - $deliveryItem->weight;
                if ($newWeight < 0) {
                    throw new \Exception('الكمية غير كافية في المخزن للمنتج: ' . $item->item_name);
                }

                // Record quantity before update
                $qtyBefore = $item->weight;

                $item->update(['weight' => $newWeight]);

                // Create transaction record (optional but recommended)
                Transaction::create([
                    'item_id'           => $deliveryItem->item_id,
                    'unit_id'           => $deliveryItem->unit_id,
                    'transaction_type'  => Transaction::TRANSACTION_OUT,
                    'quantity'          => $deliveryItem->quantity,
                    'weight'            => $deliveryItem->weight,
                    'reference_type'    => RepresentativeDelivery::class,
                    'reference_id'      => $delivery->id,
                    'source_type'       => 'inventory',
                    'source_id'         => null,
                    'destination_type'  => 'representative',
                    'destination_id'    => $delivery->representative_id,
                    'qty_before'        => $qtyBefore,
                    'qty_after'         => $newWeight,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم تغيير الحاله وتم خصم الكميه من المخزن بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

}

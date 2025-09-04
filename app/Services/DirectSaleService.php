<?php
namespace App\Services;

use App\Models\Item;
use App\Models\DirectSale;
use App\Models\DirectSaleItem;
use App\Models\Transaction;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class DirectSaleService
{
    public function getDirectSaleData($id): array
    {
        $data['items'] = Item::all();
        $data['units'] = Unit::all();

        if (!is_null($id)){
            $data['sale_obj'] = DirectSale::where('id', $id)->first();
            $data['sale_items'] = DirectSaleItem::where('sale_id', $id)->get();
        }

        return $data;
    }

    public function saveDirectSale($data, $id): bool
    {
        $sale_data = [
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_address' => $data['customer_address'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        // create
        if (is_null($id)) {
            $sale_obj = DirectSale::create($sale_data);
            $id = $sale_obj->id;
        } else {
            DirectSale::where('id', $id)->update($sale_data);
            DirectSaleItem::where('sale_id', $id)->delete();
        }

        $sale_items_data = $this->handleSaleItemsData($data, $id);
        if (!empty($sale_items_data)) {
            DirectSaleItem::insert($sale_items_data);
        }

        return true;
    }

    public function getDirectSales(Request $request): object
    {
        $data = DirectSale::orderBy('created_at', 'desc')->get();
        return $this->getTableData(model: $data);
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->editColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })
            ->editColumn('sold_at', function ($row) {
                return $row->sold_at->format('Y-m-d H:i');
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
            ->addColumn('action', function ($row) {
                $isDisabled = $row->status != 0;
                $disabledClass = $isDisabled ? 'disabled' : '';
                $onclick = $isDisabled ? 'return false;' : 'return true;';

                // First row: Edit and Delete buttons
                $firstRow = '
                    <div class="d-flex justify-content-center mb-1">
                        <a href="' . route('admin.direct_sales.edit', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-primary me-1" onclick="return true;">
                            تعديل
                        </a>
                        <a href="' . route('admin.direct_sales.delete', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-danger" onclick="return confirmMsg();">
                            مسح
                        </a>
                    </div>
                    ';

                // Second row: Confirm and Print buttons
                $secondRow = '
                    <div class="d-flex justify-content-center">
                        <a href="' . route('admin.direct_sales.confirm', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-success ' . $disabledClass . ' me-1"
                           onclick="' . $onclick . '">
                            تأكيد البيع
                        </a>
                        <a href="' . route('admin.direct_sales.print', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-info print-btn"
                           target="_blank">
                            طباعة
                        </a>
                        ';

                $secondRow .= '</div>';

                return $firstRow . $secondRow;
            })
            /*->addColumn('action', function ($row) {
                $isDisabled = $row->status != 0;
                $disabledClass = $isDisabled ? 'disabled' : '';
                $onclick = $isDisabled ? 'return false;' : 'return true;';

                $res = '
                    <a href="' . route('admin.direct_sales.edit', ['id' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        تعديل
                    </a>
                    <a href="' . route('admin.direct_sales.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        مسح
                    </a>
                    <a href="' . route('admin.direct_sales.confirm', ['id' => $row->id]) . '"
                       class="btn btn-success ' . $disabledClass . '"
                       onclick="' . $onclick . '">
                        تأكيد
                    </a>
                    ';

                if ($row->status == 1) {
                    $res .= '
                        <a href="' . route('admin.direct_sales.print', ['id' => $row->id]) . '"
                           class="btn btn-info print-btn"
                           target="_blank">
                            طباعة
                        </a>
                        ';
                }
                return $res;
            })*/
            /*->addColumn('action', function ($row) {
                $isDisabled = $row->status != 0;
                $disabledClass = $isDisabled ? 'disabled' : '';
                $onclick = $isDisabled ? 'return false;' : 'return true;';
                $tooltipStyle = 'data-bs-toggle="tooltip" data-bs-placement="top"';

                $res = '
                    <a href="' . route('admin.direct_sales.edit', ['id' => $row->id]) . '"
                       class="btn btn-icon btn-primary btn-sm me-1"
                       ' . $tooltipStyle . ' title="تعديل"
                       onclick="return true;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="' . route('admin.direct_sales.delete', ['id' => $row->id]) . '"
                       class="btn btn-icon btn-danger btn-sm me-1"
                       ' . $tooltipStyle . ' title="مسح"
                       onclick="return confirmMsg();">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a href="' . route('admin.direct_sales.confirm', ['id' => $row->id]) . '"
                       class="btn btn-icon btn-success btn-sm me-1 ' . $disabledClass . '"
                       ' . $tooltipStyle . ' title="تأكيد البيع"
                       onclick="' . $onclick . '">
                        <i class="fas fa-check-circle"></i>
                    </a>
                    ';

                if ($row->status == 1) {
                    $res .= '
                        <a href="' . route('admin.direct_sales.print', ['id' => $row->id]) . '"
                           class="btn btn-icon btn-info btn-sm me-1 print-btn"
                           ' . $tooltipStyle . ' title="طباعة الفاتورة"
                           target="_blank">
                            <i class="fas fa-print"></i>
                        </a>';
                }

                return $res;
            })*/
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    private function handleSaleItemsData($data, $sale_id): array
    {
        $sale_items_data = [];
        if(isset($data['item_id'])){
            foreach ($data['item_id'] as $index => $item){
                $unit_price = $data['unit_price'][$index] ?? 0;
                $quantity = $data['quantity'][$index] ?? 0;
                $weight = $data['weight'][$index] ?? 0;

                $sale_items_data[$index]['sale_id'] = $sale_id;
                $sale_items_data[$index]['item_id'] = $data['item_id'][$index];
                $sale_items_data[$index]['unit_id'] = $data['unit_id'][$index];
                $sale_items_data[$index]['quantity'] = $quantity;
                $sale_items_data[$index]['weight'] = $weight;
                $sale_items_data[$index]['unit_price'] = $unit_price;
                $sale_items_data[$index]['total_price'] = $unit_price * $quantity;
                $sale_items_data[$index]['created_at'] = now();
                $sale_items_data[$index]['updated_at'] = now();
            }
        }

        return $sale_items_data;
    }

    public function deleteDirectSale($id): bool
    {
        $sale = DirectSale::findOrFail($id);
        return $sale->delete();
    }

    public function confirmSale($id): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $sale = DirectSale::findOrFail($id);
            $saleItems = DirectSaleItem::where('sale_id', $id)->get();

            // Check if already confirmed
            if ($sale->status != 0) {
                throw new \Exception('تم تأكيد البيع مسبقاً');
            }

            $totalAmount = 0;

            // Process items and update inventory
            foreach ($saleItems as $saleItem) {
                $item = Item::findOrFail($saleItem->item_id);

                // Calculate new weight
                $newWeight = $item->weight - $saleItem->weight;
                if ($newWeight < 0) {
                    throw new \Exception('الكمية غير كافية في المخزن للمنتج: ' . $item->item_name);
                }

                // Record quantity before update
                $qtyBefore = $item->weight;

                // Update item inventory
                $item->update(['weight' => $newWeight]);

                // Add to total amount
                $totalAmount += $saleItem->total_price;

                // Create transaction record
                Transaction::create([
                    'item_id' => $saleItem->item_id,
                    'unit_id' => $saleItem->unit_id,
                    'transaction_type' => Transaction::TRANSACTION_OUT,
                    'quantity' => $saleItem->quantity,
                    'weight' => $saleItem->weight,
                    'reference_type' => DirectSale::class,
                    'reference_id' => $sale->id,
                    'source_type' => 'inventory',
                    'source_id' => null,
                    'destination_type' => 'customer',
                    'destination_id' => null,
                    'qty_before' => $qtyBefore,
                    'qty_after' => $newWeight,
                ]);
            }

            // Update sale status and total amount
            $sale->update([
                'status' => 1,
                'total_amount' => $totalAmount,
                'sold_at' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'تم تأكيد البيع وخصم الكميات من المخزن بنجاح');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}

<?php
namespace App\Services;

use App\Models\Container;
use App\Models\Item;
use App\Models\Transaction;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class ContainerService
{
    public function getContainers(): object
    {
        $model = Container::orderBy('created_at', 'desc')->get();
        return $this->getTableData(model: $model);
    }

    public function createContainer($data): object
    {
        DB::beginTransaction();
        try {
            // add unique container_number
            /*$lastContainerNumber = Container::max('container_number');
            $data['container_number'] = $lastContainerNumber ? (string) (intval($lastContainerNumber) + 1) : '1';*/

            $lastContainerNumber = Container::lockForUpdate()->max('container_number');
            $data['container_number'] = $lastContainerNumber ? (string)((int)$lastContainerNumber + 1) : '1';

            // create Container
            $model = Container::create($data);

            DB::commit();
            return $model;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateContainer(int $id, array $data): bool
    {
        $unit = Container::findOrFail($id);
        return $unit->fill($data)->save();
    }

    public function deleteContainer($id): bool
    {
        $unit = Container::findOrFail($id);
        return $unit->delete();
    }

    public function confirmContainer($id): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $container = Container::findOrFail($id);

            // Check if already confirmed
            if ($container->status != 0) {
                throw new \Exception('تم تأكيد البيع مسبقاً');
            }

            // Process items and update inventory

            $item = Item::findOrFail($container->item_id);

            // Calculate new weight
            $newWeight = $item->weight + $container->qty;
            /*if ($newWeight < 0) {
                throw new \Exception('الكمية غير كافية في المخزن للمنتج: ' . $item->item_name);
            }*/

            // Record quantity before update
            $qtyBefore = $item->weight;

            // Update item inventory
            $item->update(['weight' => $newWeight]);

            // Create transaction record
            Transaction::create([
                'container_id' => $container->id,
                'item_id' => $container->item_id,
                'unit_id' => $container->item_id,
                'transaction_type' => Transaction::TRANSACTION_IN,
                'quantity' => $container->number,
                'weight' => $container->qty,
                'reference_type' => Container::class,
                'reference_id' => $container->id,
                'source_type' => 'container',
                'source_id' => $container->id,
                'destination_type' => 'inventory',
                'destination_id' => null,
                'qty_before' => $qtyBefore,
                'qty_after' => $newWeight,
            ]);

            // Update sale status and total amount
            $container->update([
                'status' => 1,
                'arrival_date' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'تم تاكيد الاستلام واضافه الكميات الى المخزن بنجاح');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->editColumn('item_id', function ($row) {
                return $row->item ? $row->item->item_name : '--';
            })
            ->editColumn('supplier_id', function ($row) {
                return $row->supplier ? $row->supplier->supplier_name : '--';
            })
            ->editColumn('unit_id', function ($row) {
                return $row->unit ? $row->unit->unit_name : '--';
            })
            ->addColumn('action', function ($row) {
                $isDisabled = $row->status != 0;
                $disabledClass = $isDisabled ? 'disabled' : '';

                $res = '
                    <div class="d-flex justify-content-center mb-1">
                        <a href="' . route('admin.containers.edit', ['container' => $row->id]) . '"
                           class="btn btn-sm btn-primary me-1" onclick="return true;">
                            تعديل
                        </a>
                        <a href="' . route('admin.containers.delete', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-danger" onclick="return confirmMsg();">
                            مسح
                        </a>
                    </div>

                    <div class="d-flex justify-content-center mb-1">
                        <a href="' . route('admin.containers.confirm', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-success ' . $disabledClass . ' me-1"
                           onclick="return ' . ($isDisabled ? 'false' : 'true') . ';">
                            تاكيد الاستلام
                        </a>
                        ';

                /*
                 * <a href="' . route('admin.containers.print', ['id' => $row->id]) . '"
                           class="btn btn-sm btn-info print-btn"
                           target="_blank">
                            طباعة
                        </a>
                 * */

                $res .= '</div>';

                return $res;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

<?php

namespace App\Imports;

use App\Models\FinalStoreTransaction;
use App\Models\Group;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ItemsImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $categories;
    protected $groups;
    protected $units;

    public function __construct($categories, $groups, $units)
    {
        $this->categories = $categories;
        $this->groups = $groups;
        $this->units = $units;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $transactions = [];
            $baseTime     = Carbon::now();
            $secondsToAdd = 0;

            foreach ($rows as $row) {
                $groupId = $this->getGroupId($row['model']);
                $catId   = $this->getCategoryId($row['category']);
                $unitId  = $this->getUnitId($row['unit']);

                /*if (is_null($groupId) || is_null($catId) || is_null($unitId)) {
                    throw new \Exception(json_encode($this->importErrors));
                }*/

                $item = Item::firstOrNew([
                    'group_id'    => $groupId,
                    'category_id' => $catId,
                    'item_name'   => $row['description'],
                    'part_no'     => $row['part_number'],
                ]);

                $quantity   = $row['qty'];
                $cost       = $this->getValidDecimalValue($row['price']);
                $minAllowed = $this->getValidDecimalValue($row['minimum_allowed_value']);
                $qtyBefore  = $item->exists ? $item->quantity : 0;
                $qtyAfter   = $qtyBefore + $quantity;

                $item->unit_id           = $unitId;
                $item->quantity          = $qtyAfter;
                $item->cost              = $cost;
                $item->min_allowed_value = $minAllowed;
                $item->save();

                $currentTimestamp = $baseTime->copy()->addSeconds($secondsToAdd);
                $transactions[] = [
                    'user_id'                => Auth::id(),
                    'group_store_in_id'      => null,
                    'job_card_id'            => null,
                    'sales_id'               => null,
                    'add_used_id'            => null,
                    'item_id'                => $item->id,
                    'quantity'               => $quantity,
                    'cost'                   => $cost,
                    'total_cost'             => $quantity * $cost,
                    'date_time'              => Carbon::now(),
                    'status'                 => 0,
                    'notes'                  => 'Imported via Excel',
                    'import_file'            => 1,
                    'qty_before_transaction' => $qtyBefore,
                    'qty_after_transaction'  => $qtyAfter,
                    'created_at'             => $currentTimestamp,
                    'updated_at'             => Carbon::now(),
                ];

                $secondsToAdd += 5;
            }

            if (!empty($transactions)) {
                FinalStoreTransaction::insert($transactions);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Import failed Throwable: ' . $e->getMessage());
            throw $e;
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    private $importErrors = [];

    private function getCategoryId($categoryName)
    {
        $cleanedName = trim($categoryName);
        foreach ($this->categories as $category) {
            if ($category->category_name === $cleanedName) {
                return $category->id;
            }
        }

        throw new \Exception("Category not found for: " . $cleanedName);
    }

    private function getGroupId($groupName)
    {
        $cleanedName = trim($groupName);
        foreach ($this->groups as $group) {
            if ($group->group_name === $cleanedName) {
                return $group->id;
            }
        }

        throw new \Exception("Group not found for: " . $cleanedName);
    }

    private function getUnitId($unitName)
    {
        $cleanedName = trim($unitName);
        foreach ($this->units as $unit) {
            if ($unit->name === $cleanedName) {
                return $unit->id;
            }
        }

        throw new \Exception("Unit not found for: " . $cleanedName);
    }

    private function getValidDecimalValue($value)
    {
        return is_numeric($value) ? $value : null;
    }
}


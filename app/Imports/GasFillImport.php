<?php

namespace App\Imports;

use App\Models\GasFill;
use App\Models\Setting;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GasFillImport implements ToModel, WithHeadingRow
{
    protected $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function headingRow(): int
    {
        return 3; // Skip the first two title rows
    }

    public function map($row): array
    {
        return [
            'bill_no' => $row[2],
            'bus_no'  => $row[3],
            'date'    => $row[1],
            'diesel'  => $row[4],
        ];
    }


    private function getDieselSalary()
    {
        $setting = Setting::where('key', 'diesel')->first();
        if ($setting) {
            return $setting->value;
        }
        return 0;
    }

    public function model(array $row)
    {
        // Skip header rows or rows without the required structure
        if ($row[0] === "Sl.No." || $row[2] === 'CANCELLED' || empty($row[2]) || empty($row[3]) || empty($row[1]) || empty($row[4])) {
            \Log::info('Skipped Header or Empty Row:', $row);
            return null;
        }

        $mappedRow = [
            'bill_no'   => $row[2],
            'bus_no'    => $row[3],
            'date'      => $row[1],
            'diesel'    => $row[4],
        ];

        // Validate mapped fields
        if (!isset($mappedRow['bill_no'], $mappedRow['bus_no'], $mappedRow['date'], $mappedRow['diesel'])) {
            \Log::info('Row Missing Required Fields:', $mappedRow);
            throw ValidationException::withMessages([
                'file' => "Row missing required fields.",
            ]);
        }

        $dateString = $mappedRow['date'];

        if (is_numeric($dateString)) {
            // Convert Excel serialized date to a DateTime object
            $date = \DateTime::createFromFormat('Y-m-d', gmdate('Y-m-d', ($dateString - 25569) * 86400));
        } else {
            // If it's not numeric, try parsing it as a normal date format
            $date = \DateTime::createFromFormat('m/d/Y', $dateString);
        }

        if (!$date) {
            \Log::info('Invalid Date Format:', ['date' => $dateString]);
            throw ValidationException::withMessages([
                'file' => "Invalid date format for date: " . $dateString,
            ]);
        }

        return new GasFill([
            'bill_no'       => $mappedRow['bill_no'],
            'vehicle_id'    => $this->getVehicleId($mappedRow['bus_no']),
            'date_in'       => $date->format('Y-m-d'),
            'diesel'        => $mappedRow['diesel'],
        ]);
    }

    private $vehiclesMap;

    private function getVehicleId($plate_no)
    {
        if (!$this->vehiclesMap) {
            $this->vehiclesMap = $this->vehicles->pluck('id', 'plate_no')->toArray();
        }

        foreach ($this->vehiclesMap as $storedPlate => $id) {
            if (stripos($storedPlate, $plate_no) !== false) {
                return $id;
            }
        }

        /*$vehicle = $this->vehiclesMap::where('plate_no', 'LIKE', '%' . $plate_no . '%')->first();
        if ($vehicle) {
            return $vehicle->id;
        }*/

        throw ValidationException::withMessages([
            'file' => "Vehicles not found for: " . $plate_no,
        ]);
    }
}


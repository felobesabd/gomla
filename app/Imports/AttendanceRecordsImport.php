<?php

namespace App\Imports;

use App\Models\AttendanceRecords;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceRecordsImport implements ToModel, WithHeadingRow
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws ValidationException
     */
    public function model(array $row)
    {
        $months = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
            'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08',
            'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
        ];

        // Extract date parts
        $dateParts = explode('-', $row['date']);

        if (count($dateParts) !== 3) {
            throw new \Exception("Invalid date format: " . $row['date']);
        }

        $day = $dateParts[0];
        $month = ucfirst(strtolower($dateParts[1])); // Normalize month case
        $year = intval($dateParts[2]);

        if ($year < 100) {
            $year += 2000;
        }

        if (!isset($months[$month])) {
            throw new \Exception("Invalid month: " . $month);
        }

        // Convert to YYYY-MM-DD format
        $formattedDate = sprintf('%d-%s-%02d', $year, $months[$month], $day);

        // Check if the row indicates a status (e.g., "Annual Leave")
        if (stripos($row['time'], 'Annual Leave') !== false) {
            return new AttendanceRecords([
                'employee_id' => $this->getEmployeeId($row['name']),
                'civil_no'    => $row['ac_no'],
                'date'        => $formattedDate,
                'time_in_1'   => null,
                'time_in_2'   => null,
                'time_out_1'  => null,
                'time_out_2'  => null,
                'status'      => 2,
            ]);
        }

        // Parse time entries from the 'Time' column
        $times = preg_split('/\s+/', trim($row['time'])); // split by spaces
        return new AttendanceRecords([
            'employee_id' => $this->getEmployeeId($row['name']),
            'civil_no'    => $row['ac_no'],
            'date'        => $formattedDate,
            'time_in_1'   => isset($times[0]) ? $this->getValidTimeValue($times[0]) : null,
            'time_in_2'   => isset($times[1]) ? $this->getValidTimeValue($times[1]) : null,
            'time_out_1'  => isset($times[2]) ? $this->getValidTimeValue($times[2]) : null,
            'time_out_2'  => isset($times[3]) ? $this->getValidTimeValue($times[3]) : null,
            'status'      => null, // No special status
        ]);
    }

    private function getEmployeeId($name)
    {
        $employeeMap = collect($this->employees)
            ->mapWithKeys(fn($employee) => [strtolower($employee->name) => $employee->id])
            ->toArray();

        $lowerName = strtolower($name);

        if (array_key_exists($lowerName, $employeeMap)) {
            return $employeeMap[$lowerName];
        }

        throw ValidationException::withMessages([
            'file' => "Employee not found for: " . $name,
        ]);
    }

    private function getValidTimeValue($value)
    {
        // Check if the value matches a time format
        return preg_match('/^\d{2}:\d{2}$/', $value) ? $value : null;
    }
}


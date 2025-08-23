<?php
namespace App\Services;

use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class DepartmentService
{
    public function getDepartments(Request $request): object
    {
        $query = Department::select(
            'departments.id',
            'departments.name_en',
            'employees.name',
            'employees.joining_date',
        )
            ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
            ->orderBy('departments.id', 'ASC')
            ->orderBy('employees.joining_date', 'ASC');


        if ($request->filled('from_date')) {
            $query->where('employees.joining_date', '>=', \Carbon\Carbon::parse($request->from_date)->startOfDay());
        }

        if ($request->filled('to_date')) {
            $query->where('employees.joining_date', '<=', \Carbon\Carbon::parse($request->to_date)->endOfDay());
        }

        if ($request->filled('employee') && $request->employee != 0) {
            $query->where('employees.id', $request->employee);
        }

        if ($request->filled('department') && $request->department != 0) {
            $query->where('departments.id', $request->department);
        }

        $query->orderBy('departments.id', 'ASC');
        $query->orderBy('employees.joining_date', 'ASC');

        $data = $query->get();
        // Generate custom code for each employee
        $data = $data->map(function ($item, $index) use ($data) {
            // if found employee set code else not set code
            if (!empty($item->name)) {
                $departmentLetter = strtoupper(substr($item->name_en, 0, 1));
                $item->code = $departmentLetter . '-' . ($index + 1);
            } else {
                $item->code         = '--';
                $item->name         = '--';
                $item->joining_date = '--';
            }
            return $item;

        });

        return $this->getTableData(model: $data);
    }

    public function createDepartment($data): object
    {
        $model = Department::create($data);
        return $model;
    }

    public function updateDepartment(int $id, array $data): bool
    {
        $department = Department::findOrFail($id);
        return $department->fill($data)->save();
    }

    public function deleteDepartment($id): bool
    {
        $department = Department::findOrFail($id);
        return $department->delete();
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->addColumn('action', function ($row) {
                $res = '
                    <a href="' . route('admin.departments.edit', ['department' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        Edit
                    </a>
                    <a href="' . route('admin.departments.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        Delete
                    </a>
                    ';
                return $res;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /*public function getDepartmentsReports(Request $request): object
    {
        $query = Department::select(
            'departments.id AS department_id',
            'departments.name_en',
            'employees.name',
            'employees.joining_date',
        )
            ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
            ->orderBy('departments.id', 'ASC')
            ->orderBy('employees.joining_date', 'ASC');

        if ($request->filled('from_date')) {
            $query->where('employees.joining_date', '>=', \Carbon\Carbon::parse($request->from_date)->startOfDay());
        }

        if ($request->filled('to_date')) {
            $query->where('employees.joining_date', '<=', \Carbon\Carbon::parse($request->to_date)->endOfDay());
        }

        if ($request->filled('employee') && $request->employee != 0) {
            $query->where('employees.id', $request->employee);
        }

        if ($request->filled('department') && $request->department != 0) {
            $query->where('departments.id', $request->department);
        }

        $query->orderBy('departments.id', 'ASC');
        $query->orderBy('employees.joining_date', 'ASC');

        $data = $query->get();

        // Group the data by department and generate custom employee codes
        $groupedData = $data->groupBy('department_id')->map(function ($departmentGroup) {
            // Generate department-specific codes
            $departmentLetter = strtoupper(substr($departmentGroup->first()->name_en, 0, 1));

            return [
                'department_name' => $departmentGroup->first()->name_en,
                'employees' => $departmentGroup->values()->map(function ($employee, $index) use ($departmentLetter) {
                    return [
                        'employee_name' => $employee->name,
                        'joining_date' => $employee->joining_date,
                        'code' => $departmentLetter . '-' . ($index + 1), // Custom code generation
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'done..',
            'data' => $groupedData
        ]);
    }*/
}

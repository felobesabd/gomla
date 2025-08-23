<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        checkUserHasRolesOrRedirect('department.list');

        if ($request->ajax()) {
            return $this->departmentService->getDepartments($request);
        }

        $employees   = Employee::all();
        $departments = Department::all();

        return view('admin.departments.index', compact('employees', 'departments'));
    }

    public function create()
    {
        checkUserHasRolesOrRedirect('department.add');

        return view('admin.departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        $department = $this->departmentService->createDepartment(data: $request->all());
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show(Request $request, $id)
    {
        $department = Department::find($id);
    }

    public function edit(Request $request, $id)
    {
        checkUserHasRolesOrRedirect('department.edit');

        $department = Department::find($id);
        return view('admin.departments.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, $id)
    {
        $department = $this->departmentService->updateDepartment(id: $id, data: $request->all());
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        checkUserHasRolesOrRedirect('department.delete');

        $department = $this->departmentService->deleteDepartment($id);
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    /*public function getReportDepartmentResponse(Request $request)
    {
        return $this->departmentService->getDepartmentsReports($request);
    }

    public function getDepartmentReportsView(Request $request)
    {
        return view('admin.all_reports.departments');
    }*/
}

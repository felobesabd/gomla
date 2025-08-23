<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Imports\UsersImport;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        checkUserHasRolesOrRedirect('user.list');
        if ($request->ajax()) {
            return $this->userService->getUsers(data: $request->all());
        }
        return view('admin.users.index');
    }

    public function manage_users(Request $request)
    {
        checkUserHasRolesOrRedirect('user.add');
        $employees = Employee::all();
        $roles = Role::all();
        return view('admin.users.manage', compact('employees', 'roles'));
    }

    public function manage_users_store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3'
        ]);

        $user = $this->userService->manageUser(data: $request->all());
        return redirect()->back()->with('success', 'Added successfully');
    }

    public function edit(Request $request, $id)
    {
        checkUserHasRolesOrRedirect('user.edit');
        $user = User::find($id);
        $employees = Employee::all();
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'employees', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'password' => ['required', 'min:3']
        ]);

        $user = $this->userService->updateUser(id: $id, data: $request->all());
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        checkUserHasRolesOrRedirect('user.delete');
        $user = $this->userService->deleteUser($id);
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    public function export()
    {
        return Excel::download(new UsersExport(), 'users.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $file = $request->file('file')->store('files');

        Excel::import(new UsersImport, storage_path('app/' . $file));
        return redirect()->back()->with('success', 'All good!');
    }

    /*Excel::import(new UsersImport(), $request->file('file')->store('files'));
        return redirect('/')->with('success', 'All good!');
        return 'success';*/
}

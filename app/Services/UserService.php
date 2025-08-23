<?php
namespace App\Services;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;

class UserService
{

    public function getUsers($data): object
    {
        $model = User::all();
        return $this->getTableData(model: $model);
    }

    public function manageUser($data): object {
        DB::beginTransaction();
        try {
            $user = User::create([
                'employee_id' => $data['employee_id'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($data['role']);

            DB::commit();
            return $user;
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateUser(int $id, array $data): bool
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->syncRoles($data['role']);

            DB::commit();
            return $user->fill($data)->save();
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteUser($id): bool
    {
        $user = User::findOrFail($id);
        if ($user->email === 'admin@admin.com') {
            throw ValidationException::withMessages([
                'name' => "You cannot delete the admin",
            ]);
        }

        return $user->delete();
    }

    protected function getTableData($model)
    {
        return Datatables::of($model)
            ->addColumn('roles', function ($row) {
                return $row->roles->pluck('name')->join(', ') ?: '--';
            })
            ->editColumn('status', function ($row) {
                $res = Status::tryFrom($row->status)?->probertyName();
                return $res;
            })
            ->addColumn('action', function ($row) {
                $res = '
                    <a href="' . route('admin.users.edit', ['user' => $row->id]) . '" class="btn btn-primary" onclick="return true;">
                        Edit
                    </a>
                    ';
                return $res;
            })

            /*
             * <a href="' . route('admin.users.delete', ['id' => $row->id]) . '" class="btn btn-danger" onclick="return confirmMsg();">
                        Delete
                    </a>
             * */

            ->rawColumns(['action'])
            ->make(true);
    }
}

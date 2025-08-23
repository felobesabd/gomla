<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccidentReport;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Item;
use App\Models\JobCard;
use App\Models\JobCardEmployees;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Services\AdminService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index()
    {
        return view('admin.index');
    }

    public function editProfile()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = $this->adminService->updateProfile(id: auth()->user()->id, data: $request->all());
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}

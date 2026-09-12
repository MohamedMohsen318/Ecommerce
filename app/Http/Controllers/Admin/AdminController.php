<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;
use App\Services\Admin\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function index(): View
    {
        return view('admin.admins.index', [
            'admins' => $this->adminService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.admins.create', [
            'roles' => Role::query()->where('guard_name', 'admins')->get(),
        ]);
    }

    public function store(AdminRequest $request): RedirectResponse
    {
        $this->adminService->create($request->validated());

        return redirect()->route('admin.admins.index')->with('success', 'Admin created.');
    }

    public function edit(Admin $admin): View
    {
        return view('admin.admins.edit', [
            'admin' => $admin->load('roles'),
            'roles' => Role::query()->where('guard_name', 'admins')->get(),
        ]);
    }

    public function update(AdminRequest $request, Admin $admin): RedirectResponse
    {
        $this->adminService->update($admin, $request->validated());

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated.');
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        $this->adminService->delete($admin, auth('admins')->user());

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::with('permissions')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show form to create a new role
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::all()->groupBy(
            fn ($perm) => explode('.', $perm->name)[0]
        );

        return view('roles.form', compact('permissions'));
    }

    /**
     * Store a new role
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $data = $request->validate([
            'name'        => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create([
            'name' => $data['name'],
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        $permissions = Permission::all()->groupBy(
            fn ($perm) => explode('.', $perm->name)[0]
        );

        $assigned = $role->permissions->pluck('id')->toArray();

        return view('roles.form', compact('role', 'permissions', 'assigned'));
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $request->validate([
            'name'        => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update([
            'name' => $data['name'],
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}

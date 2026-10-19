<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    public function index(Request $request)
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->orderByDesc('is_system')
            ->orderBy('display_name')
            ->paginate(10);

        $stats = [
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'system' => Role::where('is_system', true)->count(),
            'custom' => Role::where('is_system', false)->count(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'roles' => $roles->getCollection()->map(fn ($role) => [
                    'id'=>$role->id,'name'=>$role->name,'display_name'=>$role->display_name,
                    'description'=>$role->description,'is_system'=>$role->is_system,
                    'users_count'=>$role->users_count,'permissions_count'=>$role->permissions_count,
                    'show_url'=>route('admin.roles.show',$role),'edit_url'=>route('admin.roles.edit',$role),
                    'delete_url'=>route('admin.roles.destroy',$role),
                ]),
                'pagination'=>['first_item'=>$roles->firstItem(),'last_item'=>$roles->lastItem(),'total'=>$roles->total(),'links'=>$roles->linkCollection()->toArray()],
                'stats'=>$stats,
            ]);
        }

        return view(
            'Admin.roles.index',
            compact('roles', 'stats')
        );
    }

    public function create()
    {
        $permissions = Permission::orderBy('group_name')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group_name');

        return view(
            'Admin.roles.create',
            compact('permissions')
        );
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => Str::slug((string) $request->input('name')),
        ]);

        $validated = $this->validateRole($request);

        $role = Role::create([
            'name' => Str::slug($validated['name']),
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        $role->permissions()->sync(
            $validated['permissions'] ?? []
        );

        $this->activityLogService->log(
            'created',
            'Roles',
            "Role \"{$role->display_name}\" was created."
        );

        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role created successfully.'
            );
    }

    public function show(Role $role)
    {
        $role->load([
            'permissions' => fn ($query) => $query
                ->orderBy('group_name')
                ->orderBy('display_name'),
            'users',
        ]);

        return view(
            'Admin.roles.show',
            compact('role')
        );
    }

    public function edit(Role $role)
    {
        if (
            $role->name === 'super-admin'
            && !auth()->user()->hasRole('super-admin')
        ) {
            abort(403);
        }

        $permissions = Permission::orderBy('group_name')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group_name');

        $selectedPermissions = $role
            ->permissions()
            ->pluck('permissions.id')
            ->all();

        return view(
            'Admin.roles.edit',
            compact(
                'role',
                'permissions',
                'selectedPermissions'
            )
        );
    }

    public function update(Request $request, Role $role)
    {
        if (
            $role->name === 'super-admin'
            && !auth()->user()->hasRole('super-admin')
        ) {
            abort(403);
        }

        $validated = $this->validateRole(
            $request,
            $role
        );

        $role->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($role->name !== 'super-admin') {
            $role->permissions()->sync(
                $validated['permissions'] ?? []
            );
        } else {
            $role->permissions()->sync(
                Permission::pluck('id')->all()
            );
        }

        $this->activityLogService->log(
            'updated',
            'Roles',
            "Role \"{$role->display_name}\" was updated."
        );

        return redirect()
            ->route('admin.roles.show', $role)
            ->with(
                'success',
                'Role updated successfully.'
            );
    }

    public function destroy(Role $role)
    {
        if (
            $role->is_system
            || in_array(
                $role->name,
                ['super-admin', 'admin'],
                true
            )
        ) {
            return back()->with(
                'error',
                'System roles cannot be deleted.'
            );
        }

        if ($role->users()->exists()) {
            return back()->with(
                'error',
                'Remove users from this role before deleting it.'
            );
        }

        $roleName = $role->display_name;

        $this->activityLogService->log(
            'deleted',
            'Roles',
            "Role \"{$roleName}\" was deleted."
        );

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role deleted successfully.'
            );
    }

    private function validateRole(
        Request $request,
        ?Role $role = null
    ): array {
        return $request->validate([
            'name' => [
                $role ? 'sometimes' : 'required',
                'string',
                'max:80',
                Rule::unique('roles', 'name')
                    ->ignore($role?->id),
            ],
            'display_name' => [
                'required',
                'string',
                'max:120',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);
    }
}


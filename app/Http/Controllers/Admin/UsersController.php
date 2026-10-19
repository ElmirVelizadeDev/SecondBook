<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $role = $request->query('role');

        $status = $request->query('status');

        $usersQuery = User::with('roles')->latest();

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role !== null && Role::where('name', $role)->exists()) {
            $usersQuery->where(function ($query) use ($role) {
                $query->where('role', $role)
                    ->orWhereHas(
                        'roles',
                        fn ($roleQuery) => $roleQuery->where('name', $role)
                    );
            });
        }

        if (in_array($status, ['active', 'inactive', 'banned'], true)) {
            $usersQuery->where('status', $status);
        }

        $users = $usersQuery
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => User::count(),

            'active' => User::where('status', 'active')->count(),

            'inactive' => User::whereIn('status', ['inactive', 'banned'])->count(),

            'admins' => User::where('role', 'admin')->count(),
        ];

        $roles = Role::orderBy('display_name')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'users' => $users->getCollection()->map(fn ($user) => [
                    'id' => $user->id, 'name' => $user->name,
                    'username' => $user->username, 'email' => $user->email,
                    'phone' => $user->phone, 'role' => $user->roles->first()?->name ?? $user->role,
                    'role_display' => $user->roles->first()?->display_name ?? ucfirst($user->role ?? 'user'),
                    'status' => $user->status,
                    'email_verified_at' => $user->email_verified_at?->format('d M Y'),
                    'registered_at' => $user->created_at?->format('d M Y'),
                    'photo_url' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null,
                    'show_url' => route('admin.users.show', $user),
                    'edit_url' => route('admin.users.edit', $user),
                    'status_url' => route('admin.users.status', $user),
                    'delete_url' => route('admin.users.destroy', $user),
                    'is_current' => auth()->id() === $user->id,
                ]),
                'pagination' => ['first_item'=>$users->firstItem(),'last_item'=>$users->lastItem(),'total'=>$users->total(),'links'=>$users->linkCollection()->toArray()],
                'stats' => $stats,
                'roles' => $roles->map->only(['name','display_name']),
                'filters' => $request->only(['search','role','status']),
            ]);
        }

        return view(
            'Admin.users.index',
            compact(
                'users',
                'stats',
                'search',
                'role',
                'status',
                'roles'
            )
        );
    }

    public function create()
    {
        $roles = Role::orderBy('display_name')->get();

        return view('Admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        $profilePhoto = $this->storeProfilePhoto($request);

        $user = User::create([
            ...$validated,

            'name' => trim(
                $validated['first_name'] . ' ' . $validated['last_name']
            ),

            'role' => $this->legacyRoleFor($validated['role']),

            'profile_photo' => $profilePhoto,
        ]);

        $this->syncRole($user, $validated['role']);

        $this->activityLogService->log(
            'created',
            'Users',
            "User \"{$user->name}\" was created."
        );

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    public function show(User $user)
    {
        $user->loadCount('orders')
            ->loadSum('orders', 'total_price');

        return view(
            'Admin.users.show',
            compact('user')
        );
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('display_name')->get();

        return view(
            'Admin.users.edit',
            compact('user', 'roles')
        );
    }

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $this->validateUser(
            $request,
            $user,
            true
        );

        if ($request->filled('password')) {
            $validated['password'] = $request->input('password');
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            $this->deleteProfilePhoto(
                $user->profile_photo
            );

            $validated['profile_photo'] =
                $this->storeProfilePhoto($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Protect current user's role and status
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {
            $validated['role'] = $user->role;

            $validated['status'] = $user->status;
        } elseif (
            $validated['role'] === 'super-admin'
            && !auth()->user()->hasRole('super-admin')
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Save role before removing it from user update data
        |--------------------------------------------------------------------------
        */

        $roleName = $validated['role'];

        $validated['name'] = trim(
            $validated['first_name'] . ' ' .
            $validated['last_name']
        );

        unset($validated['role']);

        $user->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Sync role
        |--------------------------------------------------------------------------
        */

        if (auth()->id() !== $user->id) {
            $this->syncRole(
                $user,
                $roleName
            );
        }

        $this->activityLogService->log(
            'updated',
            'Users',
            "User \"{$user->name}\" was updated."
        );

        return redirect()
            ->route(
                'admin.users.show',
                $user
            )
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    public function updateStatus(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'status' => [
                'required',

                Rule::in([
                    'active',
                    'inactive',
                    'banned',
                ]),
            ],
        ]);

        if (auth()->id() === $user->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,

                    'message' =>
                        'You cannot change your own account status.',
                ], 403);
            }

            return back()->with(
                'error',
                'You cannot change your own account status.'
            );
        }

        $oldStatus = $user->status;

        $user->update($validated);

        $this->activityLogService->log(
            'updated',
            'Users',
            "User \"{$user->name}\" status changed from \"{$oldStatus}\" to \"{$user->status}\"."
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,

                'message' =>
                    'User status updated successfully.',

                'status' => $user->status,
            ]);
        }

        return back()->with(
            'success',
            'User status updated successfully.'
        );
    }

    public function destroy(User $user)
    {
        if ($user->orders()->exists()) {
            $message =
                'This user cannot be deleted because they have existing orders.';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,

                    'message' => $message,
                ], 422);
            }

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    $message
                );
        }

        $userName = $user->name;

        $this->activityLogService->log(
            'deleted',
            'Users',
            "User \"{$userName}\" was deleted."
        );

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,

                'message' =>
                    'User deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }

    private function validateUser(
        Request $request,
        ?User $user = null,
        bool $isUpdate = false
    ): array {
        return $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique(
                    'users',
                    'username'
                )->ignore($user?->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($user?->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'banned',
                ]),
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);
    }

    private function storeProfilePhoto(
        Request $request
    ): ?string {
        return $request->hasFile('profile_photo')
            ? $request
                ->file('profile_photo')
                ->store(
                    'profile-photos',
                    'public'
                )
            : null;
    }

    private function deleteProfilePhoto(
        ?string $profilePhoto
    ): void {
        if ($profilePhoto) {
            Storage::disk('public')->delete(
                $profilePhoto
            );
        }
    }

    private function syncRole(
        User $user,
        string $roleName
    ): void {
        $role = Role::where(
            'name',
            $roleName
        )->firstOrFail();

        $user->update([
            'role' => $this->legacyRoleFor($roleName),
        ]);

        $user->roles()->sync([
            $role->id,
        ]);
    }

    private function legacyRoleFor(
        string $roleName
    ): string {
        return match ($roleName) {
            'seller' => 'seller',

            'super-admin',
            'admin',
            'manager',
            'editor' => 'admin',

            default => 'user',
        };
    }
}


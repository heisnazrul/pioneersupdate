<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request, ?string $role = null): View
    {
        $roleFilter = $role ?? $request->query('role');

        if ($roleFilter && !in_array($roleFilter, User::ROLES, true)) {
            abort(404);
        }

        $usersQuery = User::query()
            ->when($roleFilter, fn($query) => $query->where('role', $roleFilter))
            ->orderBy('name')
            ->orderBy('email');

        $users = $usersQuery->paginate(15)->withQueryString();

        $roleCounts = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        return view('admin.users.index', [
            'users' => $users,
            'activeRole' => $roleFilter,
            'roleCounts' => $roleCounts,
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
            'totalUsers' => User::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role' => ['required', Rule::in(User::ROLES)],
            'status' => ['required', Rule::in(User::STATUSES)],
            'phone' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:5120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validated['username'] = $this->resolveUsername($validated['username'] ?? null, $validated['name'], $validated['email']);
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(User::ROLES)],
            'status' => ['required', Rule::in(User::STATUSES)],
            'phone' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:5120'],
            'remove_avatar' => ['sometimes', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $validated['username'] = $this->resolveUsername($validated['username'] ?? null, $validated['name'], $validated['email'], $user->id);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        unset($validated['remove_avatar']);

        if ($user->id === $request->user()->id && $validated['status'] === 'banned') {
            return back()->withErrors([
                'status' => 'You cannot ban your own account.',
            ])->withInput();
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(User::STATUSES)],
        ]);

        if ($user->id === $request->user()->id) {
            return back()->withErrors([
                'status' => 'You cannot change your own status.',
            ]);
        }

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function resolveUsername(?string $username, string $name, string $email, ?int $ignoreId = null): string
    {
        $base = $username ?: Str::slug($name ?: Str::before($email, '@'));
        $base = $base ?: 'user';

        $candidate = Str::limit($base, 60, '');
        $suffix = 1;

        while (
            User::where('username', $candidate)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = Str::limit("{$base}-{$suffix}", 60, '');
            $suffix++;
        }

        return $candidate;
    }
}

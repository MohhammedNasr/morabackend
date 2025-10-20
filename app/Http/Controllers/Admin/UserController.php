<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
       // $roles = Role::all();
        return view('admin.users.index');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $user->update([
            'is_active' => $request->is_active ? 1 : 0
        ]);

        return response()->json([
            'message' => __('messages.users.status_updated')
        ]);
    }

    public function datatable(Request $request)
    {
        $users = User::query()
            ->where('role_id', 4)
            ->with(['role', 'stores']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $users->where('status', $request->status);
        }

        if ($request->has('role_id') && $request->role_id) {
            $users->where('role_id', $request->role_id);
        }

        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $users->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dates[0])->startOfDay(),
                    \Carbon\Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }

        return DataTables::of($users)
            ->addColumn('roles', function ($user) {
                return $user->role ? $user->role->name : 'No Role';
            })
            ->addColumn('stores', function ($user) {
                return $user->stores->pluck('name')->implode(', ');
            })
            ->addColumn('status', function ($user) {
                return view('admin.users.partials.status', compact('user'));
            })
            ->addColumn('actions', function ($user) {
                return view('admin.users.partials.actions', compact('user'));
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function show(User $user)
    {
        $user->load(['role', 'stores']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
        
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::where('name', 'admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Admin user created successfully.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'is_active' => $user->is_active,
                ],
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', 'admin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin user updated successfully.',
                'data' => $user->fresh()
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Admin user updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Admin user deleted successfully.'
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Admin user deleted successfully.');
    }
}

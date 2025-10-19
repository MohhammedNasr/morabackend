<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StoreUserController extends Controller
{
    public function index(Store $store)
    {
        return view('admin.store_users.index', compact('store'));
    }

    public function datatable(Store $store)
    {
        $users = $store->users()
            ->with(['role'])
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.is_active',
                'users.created_at'
            ]);

        return DataTables::of($users)
            ->addColumn('status', function($user) {
                return $user->is_active
                    ? '<span class="kt-badge kt-badge--success kt-badge--inline">Active</span>'
                    : '<span class="kt-badge kt-badge--danger kt-badge--inline">Inactive</span>';
            })
            ->addColumn('role', function($user) {
                return $user->role?->name ?? '';
            })
            ->addColumn('actions', function($user) use ($store) {
                return view('admin.store_users.partials.actions', compact('store', 'user'))->render();
            })
            ->editColumn('created_at', function($user) {
                return $user->created_at?->format('Y-m-d H:i') ?? '';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create(Store $store)
    {
        return view('admin.store_users.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $store->users()->save($user);

        return redirect()->route('admin.store_users.index', $store)
            ->with('success', 'User created successfully.');
    }

    public function edit(Store $store, User $user)
    {
        return view('admin.store_users.edit', compact('store', 'user'));
    }

    public function update(Request $request, Store $store, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->fill($request->all());
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->route('admin.store_users.index', $store)
            ->with('success', 'User updated successfully.');
    }
}

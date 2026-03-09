<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::with('store');
        
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        if ($request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $stores = \App\Models\Store::where('is_active', true)->get();
        return view('users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,manager,cashier',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'store_id' => $request->store_id,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function edit(\App\Models\User $user)
    {
        $stores = \App\Models\Store::where('is_active', true)->get();
        return view('users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,cashier',
        ]);

        $data = $request->only(['name', 'email', 'role', 'store_id', 'is_active']);
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated');
    }

    public function destroy(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete yourself');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted');
    }
}

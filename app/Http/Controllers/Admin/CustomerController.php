<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        session()->put('menu', 'users');

        $search = $request->input('search');

        $customers = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('role', 'user')
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.customer', compact('customers'));
    }
    // Show create form (if not already exists)
    public function create()
    {
        return view('admin.customers.create');
    }

    // Store new customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return redirect()->route('admin.customer')->with('success', 'Customer berhasil ditambahkan');
    }

    // Show edit form
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $customer->update($updateData);

        return redirect()->route('admin.customer')->with('success', 'Customer berhasil diperbarui');
    }

    // Delete customer
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->active = false;
        $customer->save();

        return redirect()->route('admin.customer')->with('success', 'Customer berhasil dihapus');
    }
}

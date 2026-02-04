<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    //
    public function index() 
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer) {
        $customer->load(['interactions' => function($query) {
            $query->latest();
        }]);

        return view('customers.show', compact('customer'));
    }


    // 3. Tampilkan Form Tambah Pelanggan
    public function create()
    {
        return view('customers.create');
    }

    // 4. Simpan Pelanggan Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers',
            'email' => 'nullable|email|unique:customers',
        ]);

        Customer::create($validated);

        // Redirect ke dashboard (daftar pelanggan) dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit(Customer $customer) {
        return view('customers.edit', compact('customer'));
    }

    public function update (Request $request, Customer $customer) {
        $validated = $request->validate([
            'name' =>'required|string|max:255',

            'phone_number' => [
                'required', 'string', 'max:20',
                Rule::unique('customers', 'phone_number')->ignore($customer->id),
            ],

            'email' => [
                'nullable', 'email',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ]
        ]);

        $customer->update($validated);

        return redirect()->route('dashboard')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy(Customer $customer) {
        $customer->delete();

        return redirect()->route('dashboard')->with('success', 'Pelanggan berhasil dihapus!');
    }
}

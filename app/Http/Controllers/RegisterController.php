<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('isAdmin', 0);
        return view('sales.index', [
            'users' => $users->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return ($request->all());
        $validateData = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'telepon' => 'required',
        ]);

        $validateData['password'] = bcrypt($validateData['password']);
        $validateData['isAdmin'] = '0';
        // return $validateData;

        User::create($validateData);
        return redirect()->route('sales.index')->with('success', 'Sales berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('sales.edit', [
            'sales' => User::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi data
        $validateData = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable', // Password hanya diperlukan jika ingin mengubah
            'telepon' => 'required',
        ]);

        // Jika password diisi, maka enkripsi dan simpan
        if ($request->filled('password')) {
            $validateData['password'] = bcrypt($validateData['password']);
        } else {
            // Jika password tidak diisi, gunakan password yang sudah ada
            unset($validateData['password']);
        }

        // return ($validateData);
        // Update data user
        $user->update($validateData);

        // Redirect kembali ke halaman index
        return redirect()->route('sales.index')->with('success', 'Sales berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        User::destroy($user->id);
        return redirect()->route('sales.index')->with('success', 'Sales berhasil dihapus');
    }
}

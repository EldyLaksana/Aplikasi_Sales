<?php

namespace App\Http\Controllers;

use App\Exports\TokoExport;
use App\Models\Kecamatan;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $toko = Toko::latest();
        $user = auth()->user();

        // Mulai query toko berdasarkan apakah user adalah admin atau bukan
        $toko = $user->isAdmin == 1 ? Toko::query() : Toko::where('sales_id', $user->id);

        // return (request('date_range'));
        if (request('date_range')) {
            $dates = explode("to", request('date_range'));

            if (count($dates) == 2) {
                $startDate = $dates[0];
                $endDate = $dates[1];

                $endDate = date('Y-m-d', strtotime($endDate . ' +1 day'));
                $toko->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Filter berdasarkan nama sales jika ada dalam request
        if (request('sales')) {
            $toko->where('sales_id', request('sales'));
        }

        // Filter berdasarkan status toko jika ada dalam request
        if (request('status')) {
            $toko->where('status', request('status'));
        }

        if (request('toko')) {
            // $toko->where('toko', request('toko'));
            $toko->where('tokos.toko', 'like', '%' . request('toko') . '%');
        }

        // Dapatkan data toko terbaru dan paginate hasilnya
        $toko = $toko->latest();

        return view('toko.index', [
            'tokos' => $toko->paginate(10),
            'users' => User::where('isAdmin', 0)->get(),
            'kecamatans' => Kecamatan::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('toko.create', [
            'kecamatans' => Kecamatan::all(),
            'sales_id' => auth()->user()->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return ($request->all());
        $validateData = $request->validate([
            'sales_id' => '',
            'toko' => 'required',
            'kecamatan_id' => 'required',
            'alamat' => 'required',
            'maps' => 'required',
            'status' => 'required',
            'alasan' => 'required',
            'foto' => 'required|image|file|mimes:jpg,jpeg,png',
        ]);

        if ($request->file('foto')) {
            $validateData['foto'] = $request->file('foto')->store('toko');
        }
        // return ($validateData);
        Toko::create($validateData);
        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Toko $toko)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toko $toko)
    {
        // return ($toko);

        if (Auth::user()->isAdmin !== 1 && $toko->sales_id !== Auth::user()->id) {
            return redirect('/toko')->with('error', 'Anda tidak memiliki izin untuk mengubah data toko ini.');
        }

        return view('toko.edit', [
            'toko' => $toko,
            'kecamatans' => Kecamatan::all(),
            'sales_id' => auth()->user()->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toko $toko)
    {
        // return ($request);
        $rules = [
            'sales_id' => '',
            'toko' => 'required',
            'kecamatan_id' => 'required',
            'alamat' => 'required',
            'maps' => 'required',
            'status' => 'required',
            'alasan' => 'required',
            'foto' => 'required|image|file|mimes:jpg,jpeg,png',
        ];

        $validateData = $request->validate($rules);

        if ($request->file('foto')) {
            if ($request->fotoLama) {
                Storage::delete($request->fotoLama);
            }

            $validateData['foto'] = $request->file('foto')->store('foto');
        }

        if ($toko->status == 'Tidak Prospek' && $request->status == 'Prospek') {
            $validateData['created_at'] = now();
        }

        Toko::where('id', $toko->id)->update($validateData);
        return redirect()->route('toko.index')->with('success', 'Toko berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toko $toko)
    {
        //
    }
}

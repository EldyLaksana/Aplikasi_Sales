<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Toko;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin == 0) {
            $prospek = Toko::where('status', 'Prospek')->where('sales_id', $user->id)->count();
            $tidakProspek = Toko::where('status', 'Tidak Prospek')->where('sales_id', $user->id)->count();
            $tokosByMonth = Toko::where('sales_id', $user->id)
                ->where('status', 'Prospek')
                ->selectRaw('count(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->pluck('count', 'month')
                ->all();

            $tokosByKecamatan = Toko::where('sales_id', $user->id)
                ->where('status', 'Prospek')
                ->selectRaw('count(*) as count, kecamatan_id')
                ->groupBy('kecamatan_id')
                ->pluck('count', 'kecamatan_id')
                ->all();
        } else {
            $prospek = Toko::where('status', 'Prospek')->count();
            $tidakProspek = Toko::where('status', 'Tidak Prospek')->count();
            $tokosByMonth = Toko::where('status', 'Prospek')
                ->selectRaw('count(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->pluck('count', 'month')
                ->all();

            $tokosByKecamatan = Toko::where('status', 'Prospek')
                ->selectRaw('count(*) as count, kecamatan_id')
                ->groupBy('kecamatan_id')
                ->pluck('count', 'kecamatan_id')
                ->all();
        }

        // Fetch kecamatan names
        $kecamatanNames = Kecamatan::whereIn('id', array_keys($tokosByKecamatan))
            ->pluck('kecamatan', 'id')
            ->toArray();

        // Map kecamatan_ids to names
        $mappedTokosByKecamatan = [];
        foreach ($tokosByKecamatan as $kecamatanId => $count) {
            if (isset($kecamatanNames[$kecamatanId])) {
                $mappedTokosByKecamatan[$kecamatanNames[$kecamatanId]] = $count;
            }
        }

        // dd($tokosByMonth);

        return view('dashboard.index', [
            'prospek' => $prospek,
            'tidakProspek' => $tidakProspek,
            'tokosByMonth' => $tokosByMonth,
            'tokosByKecamatan' => $tokosByKecamatan,
            'mappedTokosByKecamatan' => $mappedTokosByKecamatan,
        ]);
    }
}

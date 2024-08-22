<?php

namespace App\Http\Controllers;

use App\Exports\TokoExport;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        // return ($request->all());
        $filters = $request->all();

        $fileName = 'Laporan Sales Mingguan.xlsx';

        if (auth()->user()->isAdmin == 1) {
            if (!empty($filters['sales'])) {
                $sales = User::find($filters['sales']);
                if ($sales) {
                    $fileName = 'Laporan Sales ' . $sales->name . '.xlsx';
                }
            }
        } else {
            $fileName = 'Laporan Sales ' . auth()->user()->name . '.xlsx';
        }

        // return ($tokos);
        return Excel::download(new TokoExport($filters), $fileName);
    }
}

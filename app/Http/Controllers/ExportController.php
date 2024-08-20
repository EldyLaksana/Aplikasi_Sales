<?php

namespace App\Http\Controllers;

use App\Exports\TokoExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        // return ($request->all());
        return Excel::download(new TokoExport($request->all()), 'data-toko.xlsx');
    }
}

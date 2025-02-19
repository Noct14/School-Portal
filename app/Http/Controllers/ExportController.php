<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class ExportController extends Controller
{
        public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return Excel::download(new TransaksiExport($request->start_date, $request->end_date), 'laporan_transaksi.xlsx');
    }
}
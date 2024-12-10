<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportNilaiController extends Controller
{
    public function export()
    {   
        $categoryId = request('tableFilters.category_nilai_id.value');
        return "Export Nilai Category: $categoryId";
    }
}

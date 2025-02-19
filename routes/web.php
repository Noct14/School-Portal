<?php

use App\Http\Controllers\ExportController;
use App\Models\StudentHasClass;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Transaksi;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/export-transactions', [ExportController::class, 'export'])->name('export.transactions');

Route::get('/transaksi/{id}/bukti-bayar', function ($id) {
    $transaksi = Transaksi::findOrFail($id);
    $student = $transaksi->student;
    $studentClass = StudentHasClass::where('students_id', $student->id)->first();
    $kelas = $studentClass->classrooms;
    $periode = $studentClass->periode;

    // Ambil informasi pembayaran langsung dari transaksi
    $pembayaran = [
        'name' => $transaksi->name, // Nama pembayaran
        'amount' => $transaksi->amount, // Jumlah pembayaran
        // 'jumlah_pembayaran' => $transaksi->jumlah_pembayaran, // Jumlah yang dibayar
    ];

    // Total amount (kalo ada lebih dari satu transaksi, bisa di-update)
    $totalAmount = $transaksi->amount;

    return view('bukti_bayar', compact('transaksi', 'student', 'kelas', 'periode', 'pembayaran', 'totalAmount'));
})->name('transaksi.bukti-bayar');
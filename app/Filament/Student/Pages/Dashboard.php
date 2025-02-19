<?php

namespace App\Filament\Student\Pages;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentHasClass;
use Filament\Pages\Page;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Home';//nama sidebar
    protected static string $view = 'filament.student.pages.combine-resources';
    public $student;
    public $className;
    public $unpaidTransactions;
    public $paymentHistory;
    public $totalUnpaidAmount;

    public function mount()
    {
        // Ambil data siswa yang sedang login
        $this->student = Student::where('id', Auth::user()->id)->first();

        if (!$this->student) {
            // Tangani kasus jika siswa tidak ditemukan
            abort(403, 'Siswa tidak ditemukan');
        }

        // Ambil nama kelas berdasarkan relasi
        $this->className = StudentHasClass::where('students_id', $this->student->id)
            ->join('classrooms', 'student_has_classes.classrooms_id', '=', 'classrooms.id')
            ->value('classrooms.name') ?? 'Tidak Diketahui';

        // Ambil transaksi yang belum lunas untuk siswa ini
        $this->unpaidTransactions = Transaksi::where('students_id', $this->student->id)
            ->where('status', '!=', 'Paid')
            ->get();

        // Hitung total tagihan yang belum lunas
        $this->totalUnpaidAmount = $this->unpaidTransactions->sum('amount');

        // Ambil riwayat pembayaran 
        $this->paymentHistory = Transaksi::where('students_id', $this->student->id)
            ->where('status', 'Paid')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    


    public function payTransaction($transactionId)
    {
        $transaction = Transaksi::findOrFail($transactionId);
        
        // Logika pembayaran 
        // Misalnya, update status transaksi
        $transaction->update([
            'status' => 'Paid',
            'bank' => 'Manual Payment' // Sesuaikan dengan metode pembayaran
        ]);

        // Redirect kembali ke halaman dashboard
        $this->redirect(route('filament.pages.payment-dashboard'));
    }

    
}

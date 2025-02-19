<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{   
    public function mount()
    {
        // Ambil total tagihan yang Pending
        $this->totalPendingAmount = Transaksi::where('students_id', auth()->id())
            ->where('status', 'Pending')
            ->sum('amount');

        // Ambil total tagihan yang Paid
        $this->totalPaidAmount = Transaksi::where('students_id', auth()->id())
            ->where('status', 'Paid')
            ->sum('amount');
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', count(Student::all())),
             Stat::make('Total Tagihan Pending', 'Rp 1.000.000' )
                ->description('Total tagihan yang belum dibayar'),
            Stat::make('Total Tagihan Paid', 'Rp 500.000')
                ->description('Total tagihan yang sudah dibayar'),
            // Stat::make('Jumlah Tagihan', 'Rp 500.000'),
            
        ];
    }
}

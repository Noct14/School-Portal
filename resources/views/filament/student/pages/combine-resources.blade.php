<x-filament::page>

    <div class="space-y-6">
        <!-- Kartu Informasi Siswa -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-orange-100 shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Profil Siswa</h3>
                <div class="space-y-2">
                    <p class="flex justify-between">
                        <span class="font-medium text-gray-600">Nama:</span>
                        <span>{{ $student->name }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-medium text-gray-600">NIS:</span>
                        <span>{{ $student->nis }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-medium text-gray-600">Kelas:</span>
                        <span>{{ $className }}</span>
                    </p>
                </div>
            </div>

            <!-- Ringkasan Keuangan -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Ringkasan Keuangan</h3>
                <div class="space-y-2">
                    <p class="flex justify-between items-center">
                        <span class="font-medium text-gray-600">Total Tagihan Belum Lunas:</span>
                        <span class="text-red-600 font-bold">
                            Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="flex justify-between items-center">
                        <span class="font-medium text-gray-600">Jumlah Tagihan:</span>
                        <span>{{ $unpaidTransactions->count() }}</span>
                    </p>
                    {{-- <p class="flex justify-between items-center">
                        <span class="font-medium text-gray-600">Diskon:</span>
                        <span>Rp {{ number_format($student->diskon ?? 0, 0, ',', '.') }}</span>
                    </p> --}}
                </div>
            </div>
        </div>
        
        <!-- Daftar Tagihan Belum Lunas -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Tagihan Belum Lunas</h3>
            <div class="overflow-x-auto">
                @if($unpaidTransactions->isNotEmpty())
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="p-3">Nama Tagihan</th>
                                <th class="p-3">Jumlah</th>
                                {{-- <th class="p-3">Tipe</th> --}}
                                <th class="p-3">Status</th>
                                <th class="p-3">Virtual Account</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unpaidTransactions as $transaction)
                                <tr class="border-b">
                                    <td class="p-3">{{ $transaction->name }}</td>
                                    <td class="p-3">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    {{-- <td class="p-3">
                                        {{ optional($transaction->Bank)->name ?? 'Tidak Diketahui' }}
                                    </td> --}}
                                    <td class="p-3">
                                        <span class="
                                            @if($transaction->status == 'pending') text-yellow-600 
                                            @elseif($transaction->status == 'failed') text-red-600 
                                            @else text-gray-600 
                                            @endif
                                        ">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        {{ $transaction->va_number }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center">Tidak ada tagihan yang belum lunas</p>
                @endif
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Riwayat Pembayaran</h3>
            <div class="overflow-x-auto">
                @if($paymentHistory->isNotEmpty())
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Nama Tagihan</th>
                                <th class="p-3">Jumlah</th>
                                <th class="p-3">Bank</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentHistory as $transaction)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $transaction->updated_at->format('d M Y') }}
                                    </td>
                                    <td class="p-3">
                                        {{ $transaction->name }}
                                    </td>
                                    <td class="p-3 text-green-600">
                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3">
                                        {{ $transaction->bank ?? 'Tidak Diketahui' }}
                                    </td>
                                    <td class="p-3 text-green-600">
                                        {{ $transaction->status }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center">Belum ada riwayat pembayaran</p>
                @endif
            </div>
        </div>
    </div>

</x-filament::page>

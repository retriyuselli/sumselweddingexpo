<x-filament-panels::page>
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-6">
        <div
            class="fi-wi-stats-overview-stat relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-4">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                    <x-heroicon-o-arrow-trending-up class="h-6 w-6" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</span>
                    <div class="text-2xl font-semibold text-gray-950 dark:text-white">
                        Rp {{ number_format($summary['pemasukan'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div
            class="fi-wi-stats-overview-stat relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-4">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                    <x-heroicon-o-arrow-trending-down class="h-6 w-6" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</span>
                    <div class="text-2xl font-semibold text-gray-950 dark:text-white">
                        Rp {{ number_format($summary['pengeluaran'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div
            class="fi-wi-stats-overview-stat relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-4">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg {{ $summary['laba_rugi'] >= 0 ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
                    <x-heroicon-o-banknotes class="h-6 w-6" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Laba Bersih</span>
                    <div
                        class="text-2xl font-semibold {{ $summary['laba_rugi'] >= 0 ? 'text-primary-600 dark:text-primary-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp {{ number_format($summary['laba_rugi'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right divide-y divide-gray-200 dark:divide-white/5">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-950 dark:text-white">
                            Nama Expo
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-950 text-right dark:text-white">
                            Partisipasi
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-950 text-right dark:text-white">
                            Sponsor
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-950 text-right dark:text-white">
                            Total Pemasukan
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-950 text-right dark:text-white">
                            Total Pengeluaran
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-950 text-right dark:text-white">
                            Laba / Rugi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @forelse ($reportData as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-75">
                            <td class="px-6 py-4 font-medium text-gray-950 dark:text-white whitespace-nowrap">
                                {{ $data['nama_expo'] }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400 font-mono">
                                {{ number_format($data['pemasukan_partisipasi'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400 font-mono">
                                {{ number_format($data['pemasukan_sponsor'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-green-600 dark:text-green-400 font-mono">
                                {{ number_format($data['total_pemasukan'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-red-600 dark:text-red-400 font-mono">
                                {{ number_format($data['total_pengeluaran'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $data['laba_rugi'] >= 0 ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-inbox class="w-12 h-12 text-gray-400 mb-2" />
                                    <span class="text-base font-medium">Belum ada data transaksi expo.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>

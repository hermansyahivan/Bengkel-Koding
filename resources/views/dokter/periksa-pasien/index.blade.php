<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Alert Flash Message --}}
    @if (session('success'))
    <div class="alert alert-success mb-4 rounded-xl shadow-sm" role="alert">
        <i class="fas fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">

                    {{-- Head --}}
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse ($daftarPasien as $daftarPoli)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $daftarPoli->pasien->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $daftarPoli->keluhan }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $daftarPoli->no_antrian }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    @if ($daftarPoli->periksas->isNotEmpty())
                                        {{-- Sudah diperiksa --}}
                                        <span class="badge badge-success badge-outline gap-2 px-3 py-3 rounded-full">
                                            <i class="fas fa-circle text-[6px]"></i>
                                            Sudah Diperiksa
                                        </span>
                                    @else
                                        {{-- Belum diperiksa --}}
                                        <a href="{{ route('periksa-pasien.create', $daftarPoli->id) }}"
                                            class="btn btn-sm bg-amber-500 hover:bg-amber-600
                                                  text-white border-none rounded-lg px-4">
                                            <i class="fas fa-stethoscope"></i>
                                            Periksa
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-inbox text-3xl"></i>
                                    <span>Belum ada pasien yang akan diperiksa</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>

</x-layouts.app>
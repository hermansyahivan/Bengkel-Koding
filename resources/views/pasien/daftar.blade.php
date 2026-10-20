<x-layouts.app title="Daftar Poli">

    <div class="max-w-xl mx-auto">

        {{-- Alert Flash Message --}}
        @if (session('message'))
        <div class="alert alert-{{ session('type', 'success') }} mb-4 rounded-xl shadow-sm" role="alert">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('message') }}</span>
        </div>
        @endif

        {{-- Card --}}
        <div class="card bg-base-100 shadow-md rounded-2 border">
            <div class="card-body">

                {{-- Judul --}}
                <h2 class="text-2xl font-bold text-slate-800 text-center mb-4">
                    <i class="fas fa-notes-medical text-[#2d4499]"></i>
                    Pendaftaran Poli
                </h2>

                <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                    {{-- Nomor Rekam Medis --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nomor Rekam Medis
                        </label>
                        <input type="text" value="{{ $user->no_rm }}" readonly
                            class="input input-bordered w-full bg-slate-100 text-slate-600">
                    </div>

                    {{-- Pilih Poli --}}
                    <div class="mb-4">
                        <label for="id_poli" class="block text-sm font-semibold text-slate-700 mb-2">
                            Pilih Poli
                        </label>
                        <select name="id_poli" id="id_poli"
                            class="select select-bordered w-full @error('id_poli') select-error @enderror" required>
                            <option value="">-- Pilih Poli --</option>
                            @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_poli')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Jadwal Periksa --}}
                    <div class="mb-4">
                        <label for="id_jadwal" class="block text-sm font-semibold text-slate-700 mb-2">
                            Pilih Jadwal Periksa
                        </label>
                        <select name="id_jadwal" id="id_jadwal"
                            class="select select-bordered w-full @error('id_jadwal') select-error @enderror" required>
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach ($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}"
                                data-poli="{{ $jadwal->dokter->poli->id }}"
                                hidden disabled>
                                {{ $jadwal->dokter->nama }} - {{ $jadwal->hari }}
                                ({{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_jadwal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keluhan --}}
                    <div class="mb-6">
                        <label for="keluhan" class="block text-sm font-semibold text-slate-700 mb-2">
                            Keluhan
                        </label>
                        <textarea name="keluhan" id="keluhan" rows="3"
                            class="textarea textarea-bordered w-full @error('keluhan') textarea-error @enderror"
                            placeholder="Tulis keluhan anda...">{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="btn bg-[#1d5cff] hover:bg-[#1648cc] text-white border-none rounded-lg px-6">
                            Daftar Poli
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        const selectPoli   = document.getElementById('id_poli');
        const selectJadwal = document.getElementById('id_jadwal');

        // Tampilkan hanya jadwal milik poli yang dipilih
        function filterJadwal() {
            const poliId = selectPoli.value;

            Array.from(selectJadwal.options).forEach((opt) => {
                if (!opt.value) return; // lewati placeholder

                const cocok = opt.dataset.poli === poliId;
                opt.hidden   = !cocok;
                opt.disabled = !cocok;
            });
        }

        selectPoli.addEventListener('change', function () {
            selectJadwal.value = '';
            filterJadwal();
        });

        // Jalankan saat halaman dimuat (untuk old('id_poli') setelah validasi gagal)
        filterJadwal();

        // Auto-hide alert
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>

</x-layouts.app>
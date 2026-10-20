<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="text-slate-500 hover:text-slate-800">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body">

            <form action="{{ route('periksa-pasien.store') }}" method="POST" id="form-periksa">
                @csrf
                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">
                <input type="hidden" name="obat_json" id="obat-json" value="[]">
                <input type="hidden" name="biaya_periksa" id="biaya-periksa" value="0">

                {{-- Pilih Obat --}}
                <div class="mb-5">
                    <label for="pilih-obat" class="block text-sm font-semibold text-slate-700 mb-2">
                        Pilih Obat <span class="text-red-500">*</span>
                    </label>
                    <select id="pilih-obat" class="select select-bordered w-full">
                        <option value="">-- Pilih Obat --</option>
                        @foreach ($obats as $obat)
                        <option value="{{ $obat->id }}"
                            data-nama="{{ $obat->nama_obat }}"
                            data-harga="{{ $obat->harga }}">
                            {{ $obat->nama_obat }} - {{ $obat->kemasan }}
                            (Rp {{ number_format($obat->harga, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                    @error('obat_json')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Obat Terpilih --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Obat Terpilih
                    </label>
                    <ul id="daftar-obat" class="space-y-2"></ul>
                    <p id="obat-kosong" class="text-sm text-slate-400">Belum ada obat yang dipilih</p>
                </div>

                {{-- Total Harga --}}
                <div class="mb-5">
                    <label for="total-harga" class="block text-sm font-semibold text-slate-700 mb-2">
                        Total Harga
                    </label>
                    <input type="text" id="total-harga" value="Rp 0" readonly
                        class="input input-bordered w-full bg-slate-100 font-semibold">
                </div>

                {{-- Catatan --}}
                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-semibold text-slate-700 mb-2">
                        Catatan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="catatan" id="catatan" rows="4"
                        class="textarea textarea-bordered w-full"
                        placeholder="Tuliskan catatan...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-lg px-5">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>

                    <a href="{{ route('periksa-pasien.index') }}"
                        class="btn bg-slate-100 hover:bg-slate-200 text-slate-600 border-none rounded-lg px-5">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

    <script>
        const selectObat = document.getElementById('pilih-obat');
        const daftarObat = document.getElementById('daftar-obat');
        const obatKosong = document.getElementById('obat-kosong');
        const totalHarga = document.getElementById('total-harga');
        const obatJson   = document.getElementById('obat-json');
        const biayaInput = document.getElementById('biaya-periksa');
        const form       = document.getElementById('form-periksa');

        // { id: { nama, harga } }
        let obatTerpilih = {};

        const formatRupiah = (angka) => 'Rp ' + Number(angka).toLocaleString('id-ID');

        function render() {
            daftarObat.innerHTML = '';
            let total = 0;
            const ids = Object.keys(obatTerpilih);

            ids.forEach((id) => {
                const { nama, harga } = obatTerpilih[id];
                total += harga;

                const li = document.createElement('li');
                li.className = 'flex items-center justify-between bg-slate-50 border rounded-lg px-4 py-2';
                li.innerHTML = `
                    <span class="text-slate-700">
                        ${nama}
                        <span class="text-slate-400 text-sm">(${formatRupiah(harga)})</span>
                    </span>
                    <button type="button" data-id="${id}"
                        class="btn btn-xs bg-red-500 hover:bg-red-600 text-white border-none rounded-md">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                `;
                daftarObat.appendChild(li);
            });

            obatKosong.style.display = ids.length ? 'none' : 'block';
            totalHarga.value = formatRupiah(total);

            // Nilai yang dikirim ke controller
            obatJson.value   = JSON.stringify(ids.map(Number));
            biayaInput.value = total;
        }

        selectObat.addEventListener('change', function () {
            const id = this.value;
            if (!id) return;

            if (obatTerpilih[id]) {
                alert('Obat ini sudah dipilih.');
            } else {
                const option = this.options[this.selectedIndex];
                obatTerpilih[id] = {
                    nama: option.dataset.nama,
                    harga: parseInt(option.dataset.harga) || 0,
                };
                render();
            }

            this.value = '';
        });

        daftarObat.addEventListener('click', function (e) {
            const btn = e.target.closest('button[data-id]');
            if (!btn) return;

            delete obatTerpilih[btn.dataset.id];
            render();
        });

        form.addEventListener('submit', function (e) {
            if (Object.keys(obatTerpilih).length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu obat.');
            }
        });

        render();
    </script>

</x-layouts.app>    
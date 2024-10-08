@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Daftar Toko</h1>
    </div>
    <section class="section">
        <div class="card mb-3">
            <div class="card-header gap-2 d-grid d-lg-flex justify-content-lg-end">
                <a href="/toko" type="button" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </div>
            <form action="/toko/{{ $toko->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <input type="hidden" name="sales_id" value="{{ $sales_id }}">
                    <div class="mb-3 col-lg-6">
                        <label for="toko" class="form-label">Toko :</label>
                        <input type="text" name="toko" id="toko"
                            class="form-control @error('toko')
                            is-invalid
                        @enderror"
                            value="{{ old('toko', $toko->toko) }}" required>
                        @error('toko')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="kecamatan_id" class="form-label">Kecamatan :</label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-select">
                            <option selected>Pilih ...</option>
                            @foreach ($kecamatans as $kecamatan)
                                @if (old('kecamatan_id', $toko->kecamatan_id) == $kecamatan->id)
                                    <option value="{{ $kecamatan->id }}" selected>{{ $kecamatan->kecamatan }}</option>
                                @else
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->kecamatan }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="alamat" class="form-label">Alamat :</label>
                        <input type="text" name="alamat" id="alamat"
                            class="form-control @error('alamat')
                            is-invalid
                        @enderror"
                            value="{{ old('alamat', $toko->alamat) }}" required>
                        @error('alamat')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="maps" class="form-label">Google Maps :</label>
                        <input type="url" name="maps" id="maps"
                            class="form-control @error('maps')
                            is-invalid
                        @enderror"
                            value="{{ old('maps', $toko->maps) }}" required>
                        @error('maps')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="status" class="form-label">Status :</label>
                        <select class="form-select" name="status" id="status"
                            {{ $toko->status == 'Prospek' ? 'disabled' : '' }}>
                            <option value="Prospek" {{ $toko->status == 'Prospek' ? 'selected' : '' }}>Prospek</option>
                            <option value="Tidak Prospek" {{ $toko->status == 'Tidak Prospek' ? 'selected' : '' }}>Tidak
                                Prospek</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="alasan" class="form-label">Alasan :</label>
                        <textarea name="alasan" id="alasan" rows="3" class="form-control" required>{{ old('alasan', $toko->alasan) }}</textarea>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="foto" class="form-label">Foto :</label>
                        <input type="hidden" name="fotoLama" id="fotoLama" value="{{ $toko->foto }}">
                        <img src="{{ asset('storage/' . $toko->foto) }}" alt="{{ $toko->toko }}"
                            class="foto_preview img-fluid mb-3" width="200" height="200" style="display: block">
                        <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto"
                            name="foto" onchange="previewFoto()">
                        @error('foto')
                            <div class="invalid-feedback">
                                File tidak boleh lebih dari 2 mb
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer d-grid d-lg-flex justify-content-lg-end">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-shop"></i> Edit</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        function previewFoto() {
            const foto = document.querySelector('#foto');
            const fotoPreview = document.querySelector('.foto_preview');

            fotoPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(foto.files[0]);

            oFReader.onload = function(oFREvent) {
                fotoPreview.src = oFREvent.target.result;
            }
        }
    </script>
@endsection

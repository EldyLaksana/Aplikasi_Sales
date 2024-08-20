@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Sales</h1>
    </div>

    <section class="section">
        <div class="card mb-3">
            <div class="card-header d-grid gap-2 d-lg-flex justify-content-lg-end">
                <a href="/sales" type="button" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </div>
            <form action="/sales/{{ $sales->id }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3 col-lg-6">
                        <label for="name" class="form-label">Nama :</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $sales->name) }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="telepon" class="form-label">No Handphone :</label>
                        <input type="number" name="telepon" id="telepon"
                            class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ old('telepon', $sales->telepon) }}" required>
                        @error('telepon')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="username" class="form-label">Username :</label>
                        <input type="text" name="username" id="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $sales->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="password" class="form-label">Password (kosongkan jika tidak ingin mengubah):</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" value="">
                        @error('password')
                            <div class="invalid-feedback">
                                Harus diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer d-grid d-lg-flex justify-content-lg-end">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Edit</button>
                </div>
            </form>
        </div>
    </section>
@endsection

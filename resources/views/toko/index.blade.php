@extends('dashboard.layouts.main')

@php
    $isAdmin = auth()->user()->isAdmin;
@endphp

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Data Toko</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="card mb-3">
            <div class="card-header d-grid gap-2 d-lg-flex justify-content-lg-end">
                <a href="/toko/create" type="button" class="btn btn-primary"><i class="fa-solid fa-shop"></i>
                    Toko</a>
                <a href="{{ route('toko.export', request()->all()) }}" class="btn btn-primary" target="_blank">
                    <i class="fa-solid fa-file-export"></i> Ekspor Data
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('toko.index') }}">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            {{-- <div class="input-group"> --}}
                            <input type="text" class="form-control" placeholder="Pilih rentang tanggal..."
                                name="date_range" id="date_range" value="{{ request('date_range') }}">
                            {{-- <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                        class="fa-regular fa-calendar"></i></button> --}}
                            {{-- </div> --}}
                        </div>
                        @if (auth()->user()->isAdmin)
                            <div class="col-lg-2 mb-3">
                                {{-- <div class="input-group"> --}}
                                <select class="form-select" name="sales" id="sales">
                                    <option selected value="">Sales...</option>
                                    @foreach ($users as $user)
                                        <!-- Cek apakah user bukan admin -->
                                        <option value="{{ $user->id }}"
                                            {{ request('sales') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                            class="fa fa-search" aria-hidden="true"></i></button> --}}
                                {{-- </div> --}}
                            </div>
                        @endif
                        <div class="col-lg-2 mb-3">
                            {{-- <div class="input-group"> --}}
                            <select class="form-select" name="status" id="status">
                                <option selected value="">Pilih Status...</option>
                                <option value="Prospek" {{ request('status') == 'Prospek' ? 'selected' : '' }}>Prospek
                                </option>
                                <option value="Tidak Prospek" {{ request('status') == 'Tidak Prospek' ? 'selected' : '' }}>
                                    Tidak Prospek</option>
                            </select>
                            {{-- <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa fa-search"
                                        aria-hidden="true"></i></button> --}}
                            {{-- </div> --}}
                        </div>
                        <div class="col-lg-2 mb-3">
                            {{-- <div class="input-group"> --}}
                            <select class="form-select" name="kecamatan" id="kecamatan">
                                <option selected value="">Kecamatan...</option>
                                @foreach ($kecamatans as $kecamatan)
                                    {{-- @if (old('kecamatan_id') == $kecamatan->id)
                                            <option value="{{ $kecamatan->id }}" selected>{{ $kecamatan->kecamatan }}
                                            </option>
                                        @else
                                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->kecamatan }}</option>
                                        @endif --}}
                                    <option value="{{ $kecamatan->id }}"
                                        {{ request('kecamatan') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa fa-search"
                                        aria-hidden="true"></i></button> --}}
                            {{-- </div> --}}
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 gap-2 d-grid d-lg-flex">
                            <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa fa-search"
                                    aria-hidden="true"></i> Cari</button>
                            <a href="{{ route('toko.index') }}" class="btn btn-primary">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr class="table-primary">
                                <th class="text-center">No</th>
                                @if ($isAdmin)
                                    <th>Nama Sales</th>
                                @endif
                                <th>Tanggal</th>
                                <th>Toko</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Alasan</th>
                                <th>Foto</th>
                                <th>Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tokos as $toko)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration + ($tokos->currentPage() - 1) * $tokos->perPage() }}
                                    </td>
                                    @if ($isAdmin)
                                        <td>{{ $toko->user->name ?? 'Sales Tidak ada' }}</td>
                                    @endif
                                    <td>{{ \Carbon\Carbon::parse($toko->created_at)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $toko->toko }}</td>
                                    <td>
                                        <a href="{{ $toko->maps }}" target="_blank"
                                            style="text-decoration: none; color: black">{{ $toko->alamat }} <i
                                                class="fa-solid fa-location-dot"></i></a>
                                    </td>
                                    <td><strong>{{ $toko->status }}</strong></td>
                                    <td>{{ $toko->alasan }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $toko->foto) }}" alt="{{ $toko->toko }}"
                                            style="width: 150px; height: auto;">
                                    </td>
                                    <td>
                                        {{-- @if ($toko->created_at->diffInDays(now()) < 1 || auth()->user()->isAdmin == 1) --}}
                                        <a href="toko/{{ $toko->id }}/edit" class="badge bg-warning"
                                            style="text-decoration: none" title="">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $tokos->links() }}
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer">
                <div class="d-grid d-lg-flex justify-content-lg-end">
                    <a href="{{ route('toko.export', request()->all()) }}" class="btn btn-primary" target="_blank">
                        <i class="fa-solid fa-file-export"></i> Ekspor Data
                    </a>
                </div>
            </div> --}}
        </div>
    </section>

    <script>
        var today = new Date();
        var lastWeek = new Date();
        lastWeek.setDate(today.getDate() - 7);

        flatpickr("#date_range", {
            mode: "range",
            defaultDate: [lastWeek, today],
            dateFormat: "Y-m-d",
            // onChange: function(selectedDates, dateStr, instance) {

            // }
        });
    </script>

@endsection

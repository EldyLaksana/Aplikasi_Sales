@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Data Sales</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismiss fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="card mb-3">
            <div class="card-header d-grid gap-2 d-lg-flex justify-content-lg-end">
                <a href="/sales/create" type="button" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Tambah
                    Sales</a>
            </div>
            <div class="card-body">
                <div class="table-responsive col-lg-11">
                    <table id="table" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr class="table-info">
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>No. Telepon</th>
                                <th>Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->telepon }} <a href="https://wa.me/{{ '62' . substr($user->telepon, 1) }}"
                                            class="badge bg-success" target="_blank"><i class="fa-solid fa-phone"></i></a>
                                    </td>
                                    <td>
                                        <a href="sales/{{ $user->id }}/edit" class="badge bg-warning"
                                            style="text-decoration: none" title="">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <form action="sales/{{ $user->id }}" method="post" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <button class="badge bg-danger border-0"
                                                onclick="return confirm('Anda yakin menghapus karyawan ini?')"><i
                                                    class="fa-solid fa-trash"></i>
                                                Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

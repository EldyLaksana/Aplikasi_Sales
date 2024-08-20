@extends('dashboard.layouts.main')

@section('container')
    <style>
        .kartu {
            width: 18rem;
        }

        @media (max-width: 768px) {
            .kartu {
                width: 100%;
                margin: 0 auto;
            }
        }

        #tokoChart {
            width: 200px;
            height: 100px;
        }
    </style>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Monitoring Sales | PT. Ratu Makmur Abadi</h1>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="card mb-3">
            <div class="card-header d-grid gap-2 d-lg-flex justify-content-lg-end">
                <a href="/toko/create" type="button" class="btn btn-primary"><i class="fa-solid fa-shop"></i> Toko</a>
            </div>
        </div>
        <div class="container mt-2">
            <div class="row gap-4">
                <div class="card kartu text-center text-bg-success ">
                    <div class="card-body">
                        <i class="fa-solid fa-store fa-2xl mb-3 mt-4"></i>
                        <hr>
                        <h5 class="card-text mt-3">Toko Prospek</h5>
                        <h5 class="card-text">{{ $prospek }}</h5>
                    </div>
                </div>
                {{-- <div class="card kartu text-center text-bg-danger ">
                    <div class="card-body">
                        <i class="fa-solid fa-store-slash fa-2xl mb-3 mt-4"></i>
                        <hr>
                        <h5 class="card-text mt-3">Toko Tidak Prospek</h5>
                        <h5 class="card-text">{{ $tidakProspek }}</h5>
                    </div>
                </div> --}}
            </div>

            <div class="container mt-5 gap-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h1 class="h2">Grafik Data Toko per Bulan</h1>
                        <canvas id="tokoChart"></canvas>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h1 class="h2">Grafik Toko per Kecamatan</h1>
                        <canvas id="kecamatanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script>
        const ctx = document.getElementById('tokoChart').getContext('2d');
        const tokoChart = new Chart(ctx, {
            type: 'bar', // Bisa juga 'bar', 'pie', dll
            data: {
                labels: {!! json_encode(array_keys($tokosByMonth)) !!},
                datasets: [{
                    label: 'Jumlah Toko Prospek Tiap Bulan',
                    data: {!! json_encode(array_values($tokosByMonth)) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                // maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value, index, values) {
                                return Number(value.toFixed(0));
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('kecamatanChart').getContext('2d');
            var kecamatanData = @json($mappedTokosByKecamatan);

            var labels = Object.keys(kecamatanData);
            var data = Object.values(kecamatanData);

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Toko Prospek per Kecamatan',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    // maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value, index, values) {
                                    return Number(value.toFixed(0));
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection

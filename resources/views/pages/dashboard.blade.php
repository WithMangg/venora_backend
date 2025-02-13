@extends('index')

<?php

    $pasien = App\Models\Pasien::selectRaw('date(created_at) as tanggal, count(*) as jumlah')
        ->groupByRaw('date(created_at)')
        ->get();

    $totalpembayaran = App\Models\Pembayaran::count() > 0 ? App\Models\Pembayaran::sum('total') : 0;
    $pembayarandate = App\Models\Pembayaran::selectRaw('date(tanggal_pemeriksaan) as tanggal, sum(total) as total')
        ->groupByRaw('date(tanggal_pemeriksaan)')
        ->get();

    
    if ($pasien->count() == 0) {
        $pasien = collect();
    }
    if ($pembayarandate->count() == 0) {
        $pembayarandate = collect();
    }

    // dd($pasien);
?>

@section('content')
<div class="container-xl">
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">
                Overview
            </div>
            <h2 class="page-title">
                Menu Dashboard
                <!-- Page title actions -->
        </div>
    </div>

    <div class="container mt-5">
        <div class="row g-3">
            @if (Auth::user()->role_id == '1')
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                              <h3 class="card-title">Traffic summary</h3>
                              <div id="chart-mentions" class="chart-lg"></div>
                            </div>
                          </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="card">
                                  <div class="card-body">
                                    <div class="d-flex align-items-center">
                                      <div class="subheader">Total Dokter</div>
                                      <div class="ms-auto lh-1">
                                        {{-- <div class="dropdown">
                                          <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
                                          <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Last 7 days</a>
                                            <a class="dropdown-item" href="#">Last 30 days</a>
                                            <a class="dropdown-item" href="#">Last 3 months</a>
                                          </div>
                                        </div> --}}
                                      </div>
                                    </div>
                                    <div class="h1 mb-3">{{ App\Models\Dokter::count() }}</div>
                                    <div class="mb-5"></div>
                                    {{-- <div class="d-flex mb-2">
                                      <div>Conversion rate</div>
                                      <div class="ms-auto">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                          7% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                                          {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg> --}}
                                        {{-- </span>
                                      </div>
                                    </div>
                                    <div class="progress progress-sm">
                                      <div class="progress-bar bg-primary" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                                        <span class="visually-hidden">75% Complete</span>
                                      </div>
                                    </div> --}}
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="card">
                                  <div class="card-body">
                                    <div class="d-flex align-items-center">
                                      <div class="subheader">Jumlah Transaksi</div>
                                      <div class="ms-auto lh-1">
                                       
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                      <div class="h1 mb-0 me-2">Rp. {{ number_format($totalpembayaran, 0, ',', '.') }}</div>
                                      <div class="me-auto">
                                        @if($pembayarandate->count() == 0)
                                            <span class="text-green d-inline-flex align-items-center lh-1">
                                                0%
                                              <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                            </span>
                                        @else
                                            @if(($totalpembayaran - $pembayarandate->last()->total) / $totalpembayaran < 0)
                                                <span class="text-red d-inline-flex align-items-center lh-1">
                                                    {{ number_format((($totalpembayaran - $pembayarandate->last()->total) / $totalpembayaran) * 100, 2) }}%
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 14l-6 -6l-4 4l-8 8" /><path d="M10 17l-7 0l0 -7" /></svg>
                                                </span>
                                            @else
                                                <span class="text-green d-inline-flex align-items-center lh-1">
                                                    {{ number_format((($totalpembayaran - $pembayarandate->last()->total) / $totalpembayaran) * 100, 2) }}%
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                                </span>
                                            @endif
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                  <div id="chart-revenue-bg" class="chart-sm"></div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="card">
                                  <div class="card-body">
                                    <div class="d-flex align-items-center">
                                      <div class="subheader">Total Transaksi</div>
                                      {{-- <div class="ms-auto lh-1">
                                        <div class="dropdown">
                                          <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
                                          <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Last 7 days</a>
                                            <a class="dropdown-item" href="#">Last 30 days</a>
                                            <a class="dropdown-item" href="#">Last 3 months</a>
                                          </div>
                                        </div>
                                      </div> --}}
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                      <div class="h1 mb-3 me-2">{{ App\Models\Pembayaran::count() }}</div>
                                      <div class="me-auto">
                                        <span class="text-yellow d-inline-flex align-items-center lh-1">
                                          {{-- 0% <!-- Download SVG icon from http://tabler-icons.io/i/minus --> --}}
                                          {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg> --}}
                                        </span>
                                      </div>
                                    </div>
                                    <div id="chart-new-clients" class="chart-sm"></div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="card">
                                  <div class="card-body">
                                    <div class="d-flex align-items-center">
                                      <div class="subheader">Total Pasien</div>
                                      <div class="ms-auto lh-1">
                                        
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                      <div class="h1 mb-3 me-2">{{ \App\Models\Pasien::count() }}</div>
                                      <div class="me-auto">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                          @if(\App\Models\Pasien::count() == 0)
                                            0%
                                          @else
                                            {{ number_format((\App\Models\Pasien::count() - \App\Models\Pasien::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()) / \App\Models\Pasien::count() * 100, 2) }}%
                                          @endif
                                          {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg> --}}
                                        </span>
                                      </div>
                                    </div>
                                    <div id="chart-active-users" class="chart-sm"></div>
                                  </div>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Data Pendaftaran') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <table id="laravel_datatable" class="table table-striped table-bordered display nowrap"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>No. Pendaftaran</th>
                                            <th>No. Rekam Medis</th>
                                            <th>Nama Pasien</th>
                                            <th>Dokter</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Waktu</th>
                                            <th>Keluhan</th>
                                            <th>Status</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Data Dokter') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="data_dokter" class="table table-striped table-bordered display nowrap"
                                            style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Kode Dokter</th>
                                                    <th>Nama Dokter</th>
                                                    <th>Alamat</th>
                                                    <th>Email</th>
                                                    <th>Spesialisasi</th>
                                                    <th>No. HP</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Data Pasien') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <table id="data_pasien" class="table table-striped table-bordered display nowrap"
                                            style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>No. Rekam Medis</th>
                                                <th>NIK</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Agama</th>
                                                <th>Pekerjaan</th>
                                                <th>Alamat</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            


            <script type="text/javascript">
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#data_pasien').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('pasiens.index') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'no_rm',
                            name: 'no_rm'
                        },
                        {
                            data: 'nik',
                            name: 'nik'
                        },
                        {
                            data: 'tanggal_lahir',
                            name: 'tanggal_lahir'
                        },
                        {
                            data: 'jk',
                            name: 'jk'
                        },
                        {
                            data: 'agama',
                            name: 'agama'
                        },
                        {
                            data: 'pekerjaan',
                            name: 'pekerjaan'
                        },
                        {
                            data: 'alamat',
                            name: 'alamat'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    responsive: true,
                    scrollX: true,
                });

                    $('#data_dokter').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dokters.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'kd_dokter',
                    name: 'kd_dokter'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'spesialisasi',
                    name: 'spesialisasi'
                },
                {
                    data: 'no_hp',
                    name: 'no_hp'
                },
                {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir'
                },
                {
                    data: 'jk',
                    name: 'jk'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if (data == 'aktif') {
                            return '<span class="badge bg-success text-white">' +
                                'Aktif' + '</span>';
                        } else if (data == 'tidak') {
                            return '<span class="badge bg-danger text-white">' +
                                'Tidak Aktif' + '</span>';
                        } else {
                            return '<span class="badge bg-warning">' + 'Belum diatur' +
                                '</span>';
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            responsive: true,
            scrollX: true,

        });


                    $('#laravel_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('pendaftarans.listpendaftarans') }}",
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'no_pendaftaran',
                                name: 'no_pendaftaran'
                            },
                            {
                                data: 'pasien_id',
                                name: 'pasien_id'
                            },
                            {
                                data: 'nama_pasien',
                                name: 'nama_pasien'
                            },
                            {
                                data: 'dokter_id',
                                name: 'dokter_id'
                            },

                            {
                                data: 'tanggal_daftar',
                                name: 'tanggal_daftar'
                            },
                            {
                                data: 'waktu',
                                name: 'waktu'
                            },
                            {
                                data: 'keluhan',
                                name: 'keluhan'
                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function (data, type, row) {
                                    if (data == 'Terdaftar') {
                                        return '<span class="badge bg-primary text-white">' +
                                            'Terdaftar' + '</span>';
                                    } else if (data == 'Dalam Antrian') {
                                        return '<span class="badge bg-warning text-white">' +
                                            'Dalam Antrian' + '</span>';
                                    } else if (data == 'Selesai') {
                                        return '<span class="badge bg-success text-white">' +
                                            'Selesai' + '</span>';
                                    } else {
                                        return '<span class="badge bg-danger text-white">' + 'Gagal' +
                                            '</span>';
                                    }
                                }
                            },
                            // {
                            //     data: 'action',
                            //     name: 'action',
                            //     orderable: false,
                            //     searchable: false
                            // },
                        ],
                        responsive: true,
                        scrollX: true,

                    });
                });

            </script>

<script>

    var pendaftaran = @json(isset($pembayarandate) ? $pembayarandate->pluck('total')->toArray() : []);
    var date = @json(isset($pembayarandate) ? $pembayarandate->pluck('tanggal')->toArray() : []);
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions'), {
            chart: {
                type: "bar",
                fontFamily: 'inherit',
                height: 240,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: false
                },
                stacked: true,
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: 1,
            },
            series: [{
                name: "Pendaftaran",
                data: pendaftaran
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                padding: {
                    top: -20,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: date,
            colors: [tabler.getColor("green"), tabler.getColor("green", 0.8), tabler.getColor("green", 0.5)],
            legend: {
                show: false,
            },
        })).render();
    });
    // @formatter:on
  </script>

<script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-revenue-bg'), {
            chart: {
                type: "area",
                fontFamily: 'inherit',
                height: 40.0,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: false
                },
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: .16,
                type: 'solid'
            },
            stroke: {
                width: 2,
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "Profits",
                data: [
                    @foreach ($pembayarandate as $p)
                        {{ $p->total }},
                    @endforeach
                ]
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                strokeDashArray: 4,
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: [
                @foreach ($pembayarandate as $p)
                    '{{ $p->tanggal }}',
                @endforeach
            ],
            colors: [tabler.getColor("green")],
            legend: {
                show: false,
            },
        })).render();
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-new-clients'), {
            chart: {
                type: "line",
                fontFamily: 'inherit',
                height: 40.0,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: false
                },
            },
            fill: {
                opacity: 1,
            },
            stroke: {
                width: [2, 1],
                dashArray: [0, 3],
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "May",
                data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 4, 46, 39, 62, 51, 35, 41, 67]
            },{
                name: "April",
                data: [93, 54, 51, 24, 35, 35, 31, 67, 19, 43, 28, 36, 62, 61, 27, 39, 35, 41, 27, 35, 51, 46, 62, 37, 44, 53, 41, 65, 39, 37]
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                strokeDashArray: 4,
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: [
                '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
            ],
            colors: [tabler.getColor("green"), tabler.getColor("gray-600")],
            legend: {
                show: false,
            },
        })).render();
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-active-users'), {
            chart: {
                type: "bar",
                fontFamily: 'inherit',
                height: 40.0,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: false
                },
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: 1,
            },
            series: [{
                name: "Registered",
                data: [
                    
                    @foreach ($pasien as $p)
                        {{ $p->jumlah }},
                    @endforeach
                ]
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                strokeDashArray: 4,
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: [
                @foreach ($pasien as $p)
                    '{{ $p->tanggal }}',
                @endforeach
            ],
            colors: [tabler.getColor("green")],
            legend: {
                show: false,
            },
        })).render();
    });
    // @formatter:on
  </script>

            @endsection

@extends('index')

@section('content')
<div class="page-header d-print-none">

    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Menu {{ $title ?? env('APP_NAME') }}
                    <!-- Page title actions -->
            </div>
        </div>
    </div>
</div>
{{-- Alert --}}
<div id="alertPlaceholder" class="alert-position-top-right"></div>

<div class="container mt-5">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="laravel_datatable" class="table table-striped table-bordered display nowrap"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No. Transaksi</th>
                                <th>Nama</th>
                                <th>Total</th>
                                <th>Tanggal Transaksi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>

{{-- Modal loading --}}
<x-popup.loading />

{{-- Modal konfirmasi --}}
<x-popup.modal_delete_confirmation />


<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaksis.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'transaksi_id',
                    name: 'transaksi_id'
                },
                {
                    data: 'pasienId',
                    name: 'pasienIid'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'tanggal_transaksi',
                    name: 'tanggal_transaksi'
                },
                
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if (data == 'Pending') {
                            return '<span class="badge bg-warning text-white">' +
                                'Pending' + '</span>';
                        } else if (data == 'Lunas') {
                            return '<span class="badge bg-success text-white">' +
                                'Lunas' + '</span>';
                        } else {
                            return '<span class="badge bg-danger text-white">' + 'Belum Bayar' +
                                '</span>';
                        }
                    }
                },
            ],
            responsive: true,
            scrollX: true,

        });
        
    });

</script>
@endsection

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
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
            <p class="card-subtitle text-muted" id="tanggal_jam"></p>
            <script>
                function updateDateTime() {
                    var d = new Date();
                    var options = { year: 'numeric', month: 'long', day: 'numeric' };
                    var date = d.toLocaleDateString('id-ID', options);
                    var time = d.toLocaleTimeString();
                    document.getElementById("tanggal_jam").innerHTML = date + ' ' + time;
                }
                updateDateTime();
                setInterval(updateDateTime, 1000);
            </script>
            
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="laravel_datatable" class="table table-striped table-bordered display nowrap"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No. Antrian</th>
                                <th>No. Pendaftaran</th>
                                <th>No. Rekam Medis</th>
                                <th>Nama Pasien</th>
                                <th>Dokter</th>
                                <th>Tanggal Daftar</th>
                                <th>Waktu</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Action</th>
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


        var table = $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pemeriksaan-pasien.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'no_antrian',
                    name: 'no_antrian',
                    render: function (data, type, row) {
                        return data + '<span class="ms-2" onclick="speak(\'' + data + '\')">' + '<i class="ti ti-volume"></i>' + '</span>';
                    }
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
                    data: 'waktu',
                    name: 'waktu'
                },
                
                {
                    data: 'tanggal_daftar',
                    name: 'tanggal_daftar'
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
                        } else if (data == 'Gagal') {
                            return '<span class="badge bg-danger text-white">' + 'Gagal' +
                                '</span>';
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
                    searchable: false,
                }
            ],
            responsive: true,
            scrollX: true,

        });

        $('body').on('click', '.editProduct', function () {
var product_id = $(this).closest('tr').find('td:eq(2)').text();
            window.location.href = '{{ route("pemeriksaan-pasien.show", ":id") }}'.replace(':id', product_id);
        });

        // $('#laravel_datatable tbody').on('click', 'tr', function() {
        //     var data = table.row( this ).data();
        //     window.location.href = '{{ route("pemeriksaan-pasien.show", ":id") }}'.replace(':id', data.no_pendaftaran);
        //     })


    });

    function speak(antrian) {
        // Create a SpeechSynthesisUtterance
        const utterance = new SpeechSynthesisUtterance(`Antrian nomor ${antrian} ,Silahkan Masuk!`);
    
        // Select a voice
        const voices = speechSynthesis.getVoices();
        utterance.lang = 'id-ID';
        utterance.voice = voices.find(voice => voice.lang === 'id-ID');

        // Speak the text
        speechSynthesis.speak(utterance);
    }


</script>
@endsection

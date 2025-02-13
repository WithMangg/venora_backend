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
                    Menu Riwayat Pasien
                    <!-- Page title actions -->
            </div>
        </div>
    </div>
</div>
{{-- Alert --}}
<div id="alertPlaceholder" class="alert-position-top-right"></div>

<div class="container mt-5">
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">Cari Pasien</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="no_rm" name="no_rm" placeholder="No. Rekam Medis" aria-label="No. Rekam Medis" aria-describedby="button-addon2">
                            <button class="btn btn-gr" type="submit" id="button-addon2">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                                <th>No. Pendaftaran</th>
                                <th>Tanggal</th>
                                <th>Keluhan</th>
                                <th>Kondisi Kulit</th>
                                <th>Diagnosis</th>
                                <th>Rek. Treatment</th>
                                <th>Rek. Skincare</th>
                                <th>Catatan</th>
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


        var pasienId = '';
        $('#button-addon2').click(function (e) {
            e.preventDefault();
            pasienId = $('#no_rm').val();

            if (!pasienId) {
                toastr.error('No. Rekam Medis harus diisi!');
                return false;
            }

            $('#laravel_datatable').DataTable().ajax.url("{{ route('riwayatpasien.show', ':id') }}".replace(':id', pasienId)).load();
        });

        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('riwayatpasien.show', ':id') }}".replace(':id', pasienId),
                type: "GET",
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data: 'pemeriksaan_kdPendaftaran', name: 'pemeriksaan_kdPendaftaran'},
                {data: 'created_at', name: 'created_at'},
                {data: 'pemeriksaan_keluhan', name: 'pemeriksaan_keluhan'},
                {data: 'pemeriksaan_kondisiKulit', name: 'pemeriksaan_kondisiKulit'},
                {data: 'pemeriksaan_diagnosis', name: 'pemeriksaan_diagnosis'},
                {data: 'pemeriksaan_rekTreatment', name: 'pemeriksaan_rekTreatment'},
                {data: 'pemeriksaan_rekSkincare', name: 'pemeriksaan_rekSkincare'},
                {data: 'pemeriksaan_note', name: 'pemeriksaan_note'},
            ],
            responsive: true,
            scrollX: true,

        });
        
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

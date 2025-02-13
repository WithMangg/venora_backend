@extends('index')

<?php

    $tindakanmedis = App\Models\Diagnosa::all();
    $skincare = App\Models\Skincare::all();
    $treatment = App\Models\FacialTreatment::all();
?>
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

<div class="container mt-5" style="margin-bottom: 10vh">
    <div class="row g-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
                    <p class="card-subtitle text-muted" id="tanggal_jam"></p>
                    <script>
                        function updateDateTime() {
                            var d = new Date();
                            var options = {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            var date = d.toLocaleDateString('id-ID', options);
                            var time = d.toLocaleTimeString();
                            document.getElementById("tanggal_jam").innerHTML = date + ' ' + time;
                        }
                        updateDateTime();
                        setInterval(updateDateTime, 1000);

                    </script>

                </div>
                <div class="card-body" id="collapseExample">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="tagihanDetailsTable" class="table table-hover">
                                <colgroup>
                                    <col style="width: 30%;">
                                    <col style="width: 70%;">
                                </colgroup>
                                <tr>
                                    <td>No. Pendaftaran</td>
                                    <td>&emsp;&emsp;: <span
                                            id="no_pendaftarans">{{ $pendaftaran->no_pendaftaran ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>No. Rekam Medis</td>
                                    <td>&emsp;&emsp;: <span id="no_rm">{{ $pendaftaran->no_rm ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>&emsp;&emsp;: <span id="nik">{{ $pendaftaran->nik ?? ' -' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Nama Pasien</td>
                                    <td>&emsp;&emsp;: <span
                                            id="nama_pasien">{{ $pendaftaran->nama_pasien ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>&emsp;&emsp;: <span id="alamat">{{ $pendaftaran->alamat ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Lahir</td>
                                    <td>&emsp;&emsp;: <span
                                            id="tanggal_lahir">{{ $pendaftaran->tanggal_lahir ?? ' -'}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>&emsp;&emsp;: <span id="jk">{{ $pendaftaran->jk ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>Hasil Deteksi?</td>
                                    <td>&emsp;&emsp;: <span id="poli">{{ $pendaftaran->nama_poli ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>Dokter</td>
                                    <td>&emsp;&emsp;: <span id="dokter">{{ $pendaftaran->nama ?? ' -'}}</span></td>
                                </tr>
                                <tr>
                                    <td>Spesialis</td>
                                    <td>&emsp;&emsp;: <span
                                            id="spesialis">{{ $pendaftaran->spesialisasi ?? ' -'}}</span></td>
                                </tr>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">{{ __('Input Diagnosa Pasien') }}</h3>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs">
                                        <li class="nav-item">
                                            <a href="#tabs-home-7" class="nav-link active" data-bs-toggle="tab">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/stethoscope -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-plus">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                    <path
                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                    <path d="M12 11l0 6" />
                                                    <path d="M9 14l6 0" /></svg>
                                                Input Diagnosa</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#tabs-riwayat-pemeriksaan" class="nav-link" data-bs-toggle="tab">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12h4l3 8l4 -16l3 8h4" /></svg>

                                                Riwayat Pemeriksaan</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="tabs-home-7">
                                            <form id="userForm" name="userForm" class="form-horizontal">
                                                <input type="hidden" name="user_id" id="user_id">
                                                <input type="hidden" name="pasien_id" id="pasien_id" value="{{ $pendaftaran->no_rm }}">
                                                <input type="hidden" name="kd_pendaftaran" id="kd_pendaftaran" value="{{ $pendaftaran->no_pendaftaran }}">

                                                <div class="row">
                                                    <h3>Anamnesis</h3>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="diagnosis">Diagnosis <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="pemeriksaan_diagnosis"
                                                                id="pemeriksaan_diagnosis">
                                                                <span class="text-danger" id="pemeriksaan_diagnosisError"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="kondisi_kulit">Kondisi Kulit<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="pemeriksaan_kondisiKulit"
                                                                id="pemeriksaan_kondisiKulit">
                                                                <span class="text-danger" id="pemeriksaan_kondisiKulitError"></span>
                                                        </div>
                                                    </div>
                                                    <h3 class="mt-5">Pemeriksaan</h3>
                                                    
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="pemeriksaan_rekSkincare">Skincare</label>
                                                                <div id="pemeriksaan_rekSkincare_wrapper">
                                                                    <div class="d-flex align-items-center">
                                                                        <select class="form-control w-75" name="pemeriksaan_rekSkincare[]" id="pemeriksaan_rekSkincare" placeholder="Skincare 1">
                                                                            <option value="">Pilih Skincare</option>
                                                                            @foreach ($skincare as $skin)
                                                                                <option value="{{ $skin->skincare_id }}">{{ $skin->skincare_nama }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="number" class="form-control w-25 mx-2" name="pemeriksaan_jumlahSkincare[]" id="pemeriksaan_jumlahSkincare" placeholder="Jumlah">
                                                                        <button type="button" class="btn btn-primary btn-md w-25 mx-2" onclick="addSkincare()">Tambah</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            var count = 1;
                                                            function addSkincare() {
                                                                count++;
                                                                $('#pemeriksaan_rekSkincare_wrapper').append('<div class="d-flex align-items-center mt-2"><select class="form-control w-75" name="pemeriksaan_rekSkincare[]" id="pemeriksaan_rekSkincare' + count + '"><option value="">Pilih Skincare</option>@foreach ($skincare as $skin)<option value="{{ $skin->skincare_id }}">{{ $skin->skincare_nama }}</option>@endforeach</select><input type="number" class="form-control w-25 mx-2" name="pemeriksaan_jumlahSkincare[]" id="pemeriksaan_jumlahSkincare' + count + '" placeholder="Jumlah"><button type="button" class="btn btn-danger btn-md w-25 mx-2" onclick="removeSkincare(this)">Hapus</button></div>');
                                                            }
                                                            
                                                            function removeSkincare(element) {
                                                                $(element).parent().remove();
                                                            }
                                                        </script>                    
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="pemeriksaan_rekTreatment">Treatment</label>
                                                                <select class="form-control" name="pemeriksaan_rekTreatment" id="pemeriksaan_rekTreatment">
                                                                    <option value="">Pilih Treatment</option>
                                                                    @foreach ($treatment as $treat)
                                                                                <option value="{{ $treat->facialTreatment_id }}">{{ $treat->facialTreatment_nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger" id="rujukanError"></span>
                                                            </div>
                                                        </div>
                                                        <h3 class="mt-5">Instruksi Pasien</h3>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="pemeriksaan_note">Catatan</label>
                                                                <textarea class="form-control" name="pemeriksaan_note" id="pemeriksaan_note"></textarea>
                                                            </div>
                                                        </div>
                                                    

                                                </div>

                                                <div class="col-sm-offset-2 col-sm-10 mt-3">
                                                    <button type="submit" class="btn btn-gr" id="saveBtn" value="create">Simpan Data</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="tabs-riwayat-pemeriksaan">
                                            <table id="laravel_datatable"
                                                class="table table-striped table-bordered display nowrap"
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
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>




        {{-- Modal loading --}}
        <x-popup.loading />

        {{-- Modal konfirmasi --}}
        <x-popup.modal_delete_confirmation />




        <script type="text/javascript">
            $(document).ready(function () {
                $('#no_pendaftaran').val('{{ $pendaftaran->no_pendaftaran }}');

                $('#pasien_id').val('{{ $pendaftaran->pasien_id }}');



                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                var pasienId = '{{$pendaftaran->pasien_id}}';
                $('#laravel_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('layanans/pemeriksaan-pasien/getDataDiagnosis') }}/" + pasienId,
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


                $('#saveBtn').click(function (e) {
                    e.preventDefault();
                    $('#saveBtn').html('Sending..');

                    // memasang user_id



                    // Reset error messages
                    $('#kd_pendaftaranError').text('');
                    $('#pasien_idError').text('');
                    $('#pemeriksaan_keluhanError').text('');
                    $('#pemeriksaan_kondisiKulitError').text('');
                    $('#pemeriksaan_diagnosisError').text('');

                    var actionType = $(this).val();
                    var formData = new FormData($('#userForm')[0]);

                    $.ajax({
                        data: formData,
                        url: "{{ route('pemeriksaan-pasien.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            $('#userForm').trigger("reset");
                            $('#laravel_datatable').DataTable().ajax.reload();
                            $('#saveBtn').html('Save Changes');

                            // Tampilkan alert sukses
                            toastr.success('Diagnosa baru ditambahkan!', 'Success');
                            
                        },
                        error: function (xhr) {
                            $('#saveBtn').html('Save Changes');

                            // Tampilkan pesan error
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                if (errors.keluhan_utama) {
                                    $('#keluhan_utamaError').text(errors.keluhan_utama[0]);
                                }
                                if (errors.riwayat_penyakit_sekarang) {
                                    $('#riwayat_penyakit_sekarangError').text(errors.riwayat_penyakit_sekarang[0]);
                                }
                                if (errors.diagnosis_utama) {
                                    $('#diagnosis_utamaError').text(errors.diagnosis_utama[0]);
                                }
                                if (errors.rujukan) {
                                    $('#rujukanError').text(errors.rujukan[0]);
                                    $('#rujukan').val("").trigger('change');
                                }
                            } else {
                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Diagnosis gagal ditambahkan!'])
                            @endcomponent
                        `);
                            }
                        }
                    });
                });


            });

        </script>
        @endsection

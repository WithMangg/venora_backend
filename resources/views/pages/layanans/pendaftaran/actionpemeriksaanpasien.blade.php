@extends('index')

<?php

    $tindakanmedis = App\Models\Diagnosa::all();
    $obats = App\Models\Obat::all();
    $diagnosisutamas = App\Models\DiagnosisICD::all();
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
                                    <td>Poli</td>
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
                            <div class="mt-5 mb-5">
                                <table id="tagihanDetailsTable" class="table table-hover">
                                    <colgroup>
                                        <col style="width: 30%;">
                                        <col style="width: 70%;">
                                    </colgroup>
                                    <tr>
                                        <td>Tinggi Badan</td>
                                        <td>&emsp;&emsp;: <span id="sda">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Berat Badan</td>
                                        <td>&emsp;&emsp;: <span id="no_rm">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tekanan Darah</td>
                                        <td>&emsp;&emsp;: <span id="nik">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Daftar</td>
                                        <td>&emsp;&emsp;: <span
                                                id="tanggal_daftar">{{ $pendaftaran->tanggal_daftar ?? ' -'}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Keluhan</td>
                                        <td>&emsp;&emsp;: <span id="keluhan">{{ $pendaftaran->keluhan ?? ' -'}}</span>
                                        </td>
                                    </tr>

                                </table>
                            </div>
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
                                                            <label for="keluhan_utama">Keluhan Utama <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="keluhan_utama"
                                                                id="keluhan_utama">
                                                                <span class="text-danger" id="keluhan_utamaError"></span>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="riwayat_penyakit_sekarang">Riwayat Penyakit Sekarang <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="riwayat_penyakit_sekarang"
                                                                id="riwayat_penyakit_sekarang">
                                                            <span class="text-danger" id="riwayat_penyakit_sekarangError"></span>
                                                        </div>
                                                    </div>
                                                    <h3 class="mt-5">Pemeriksaan Fisik</h3>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tinggi_badan">Tinggi Badan (cm)</label>
                                                            <input type="text" class="form-control" name="tinggi_badan"
                                                                id="tinggi_badan">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="berat_badan">Berat Badan (kg)</label>
                                                            <input type="text" class="form-control" name="berat_badan"
                                                                id="berat_badan">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tekanan_darah">Tekanan Darah (mmHg)</label>
                                                            <input type="text" class="form-control" name="tekanan_darah"
                                                                id="tekanan_darah">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="suhu_tubuh">Suhu Tubuh (°C)</label>
                                                            <input type="text" class="form-control" name="suhu_tubuh" id="suhu_tubuh">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nadi">Frekuensi Nadi (bpm)</label>
                                                            <input type="text" class="form-control" name="nadi" id="nadi">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="frekuensi_napas">Frekuensi Napas (rpm)</label>
                                                            <input type="text" class="form-control" name="frekuensi_napas" id="frekuensi_napas">
                                                        </div>
                                                    </div>
                                                    <h3 class="mt-5">Diagnosis</h3>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="diagnosis_utama">Diagnosis Utama (ICD-10) <span class="text-danger">*</span></label>
                                                            <select class="select2-ajax" 
                                                                    id="diagnosis_utama"

                                                                    name="diagnosis_utama" 
                                                                    data-url="{{ route('api.diagnosas.diagnosisutamas') }}" 
                                                                    data-placeholder="Pilih Diagnosis Utama" 
                                                                    style="width: 100%">
                                                            </select>
                                                            <span class="text-danger" id="diagnosis_utamaError"></span>
                                                    
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="diagnosis_pendukung">Diagnosis Pendukung</label>
                                                            <select class="select2-ajax" 
                                                                    name="diagnosis_pendukung"
                                                                    id="diagnosis_pendukung" 
                                                                    data-url="{{ route('api.diagnosas.diagnosisutamas') }}" 
                                                                    data-placeholder="Pilih Diagnosis Pendukung" 
                                                                    style="width: 100%">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $(".select2-ajax").each(function () {
                                                                const $select = $(this);
                                                                const url = $select.data('url');
                                                                const placeholder = $select.data('placeholder');

                                                                $select.select2({
                                                                    ajax: {
                                                                        url: url,
                                                                        dataType: 'json',
                                                                        delay: 250,
                                                                        data: function (params) {
                                                                            return {
                                                                                q: params.term, // Kata kunci pencarian
                                                                                page: params.page || 1
                                                                            };
                                                                        },
                                                                        processResults: function (data, params) {
                                                                            params.page = params.page || 1;

                                                                            return {
                                                                                results: data.results,
                                                                                pagination: {
                                                                                    more: (params.page * 30) < data.total_count
                                                                                }
                                                                            };
                                                                        },
                                                                        cache: true
                                                                    },
                                                                    placeholder: placeholder,
                                                                    minimumInputLength: 1,
                                                                    theme: "bootstrap-5",
                                                                });
                                                            });
                                                        });

                                                    </script>
                                                    <h3 class="mt-5">Rencana Penatalaksanaan</h3>
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label for="tindakan_medis">Tindakan Medis <span class="text-danger">*</span></label>
                                                                <div id="tindakan_medis_wrapper">
                                                                    <div class="d-flex align-items-center">
                                                                        <select class="form-control w-75" name="tindakan_medis[]" id="tindakan_medis" placeholder="Tindakan Medis 1">
                                                                            <option value="">Pilih Tindakan Medis</option>
                                                                            @foreach ($tindakanmedis as $tindakan_medis)
                                                                                <option value="{{ $tindakan_medis->kd_diagnosa }}">{{ $tindakan_medis->kategori }} - {{ $tindakan_medis->diagnosa }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button" class="btn btn-primary w-25 mx-2 btn-md" onclick="addTindakanMedis()">Tambah</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function () {
                                                                let count = 1;
                                                                window.addTindakanMedis = function () {
                                                                    count++;
                                                                    const tindakanMedisWrapper = document.getElementById('tindakan_medis_wrapper');
                                                                    const div = document.createElement('div');
                                                                    div.className = 'input-group mt-2';
                                                                    div.innerHTML = `
                                                                        <select class="form-control w-50" name="tindakan_medis[]" id="tindakan_medis${count}">
                                                                            <option value="">Pilih Tindakan Medis</option>
                                                                            @foreach ($tindakanmedis as $tindakan_medis)
                                                                                <option value="{{ $tindakan_medis->kd_diagnosa }}">{{ $tindakan_medis->kategori }} - {{ $tindakan_medis->diagnosa }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button" class="btn btn-danger w-25 mx-2 btn-md" onclick="removeTindakanMedis(this)">Hapus</button>
                                                                    `;
                                                                    tindakanMedisWrapper.appendChild(div);
                                                                };

                                                                window.removeTindakanMedis = function (element) {
                                                                    element.parentElement.remove();
                                                                };
                                                            });
                                                        </script>
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label for="resep_obat">Resep Obat</label>
                                                                <div id="resep_obat_wrapper">
                                                                    <div class="d-flex align-items-center">
                                                                        <select class="form-control w-75" name="resep_obat[]" id="resep_obat" placeholder="Resep Obat 1">
                                                                            <option value="">Pilih Resep Obat</option>
                                                                            @foreach ($obats as $obat)
                                                                                <option value="{{ $obat->medicine_id }}">{{ $obat->nama_obat }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="number" class="form-control w-25 mx-2" name="jumlah_obat[]" id="jumlah_obat" placeholder="Jumlah">
                                                                        <button type="button" class="btn btn-primary btn-md w-25 mx-2" onclick="addResepObat()">Tambah</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            var count = 1;
                                                            function addResepObat() {
                                                                count++;
                                                                $('#resep_obat_wrapper').append('<div class="d-flex align-items-center mt-2"><select class="form-control w-75" name="resep_obat[]" id="resep_obat' + count + '"><option value="">Pilih Resep Obat</option>@foreach ($obats as $obat)<option value="{{ $obat->medicine_id }}">{{ $obat->nama_obat }}</option>@endforeach</select><input type="number" class="form-control w-25 mx-2" name="jumlah_obat[]" id="jumlah_obat' + count + '" placeholder="Jumlah"><button type="button" class="btn btn-danger btn-md w-25 mx-2" onclick="removeResepObat(this)">Hapus</button></div>');
                                                            }
                                                            
                                                            function removeResepObat(element) {
                                                                $(element).parent().remove();
                                                            }
                                                        </script>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="konsultasi_lanjutan">Konsultasi Lanjutan</label>
                                                                <input type="text" class="form-control" name="konsultasi_lanjutan" id="konsultasi_lanjutan">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rujukan">Rujukan</label>
                                                                <select class="form-control" name="rujukan" id="rujukan">
                                                                    <option value="">Pilih Status Rujukan</option>
                                                                    <option value="Ya">Ya</option>
                                                                    <option value="Tidak">Tidak</option>
                                                                </select>
                                                                <span class="text-danger" id="rujukanError"></span>
                                                            </div>
                                                        </div>
                                                        <h3 class="mt-5">Instruksi Pasien</h3>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="anjuran_dokter">Anjuran Dokter</label>
                                                                <input type="text" class="form-control" name="anjuran_dokter" id="anjuran_dokter">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="status_pulang">Status Pulang <span class="text-danger">*</span></label>
                                                                <select class="form-control" name="status_pulang" id="status_pulang">
                                                                    <option value="">Pilih Status Pulang</option>
                                                                    <option value="berobat_jalan">Berobat Jalan</option>
                                                                    <option value="sehat">Sehat</option>
                                                                    <option value="rujuk">Rujuk</option>
                                                                    <option value="meninggal">Meninggal</option>
                                                                </select>
                                                                <span class="text-danger" id="keluhan_utamaError"></span>
                                                                
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
                                                        <th>Kode Diagnosa</th>
                                                        <th>No. Pendaftaran</th>
                                                        <th>Tanggal Diagnosa</th>
                                                        <th>Keluhan</th>
                                                        <th>Riwayat Penyakit</th>
                                                        <th>Diagnosa Utama</th>
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
                        {
                            data: 'kd_diagnosa',
                            name: 'kd_diagnosa'
                        },
                        {
                            data: 'kd_pendaftaran',
                            name: 'kd_pendaftaran'
                        },
                        {
                            data: 'tanggal_diagnosa',
                            name: 'tanggal_diagnosa'
                        },
                        {
                            data: 'keluhan_utama',
                            name: 'keluhan_utama'
                        },
                        {
                            data: 'riwayat_penyakit_sekarang',
                            name: 'riwayat_penyakit_sekarang'
                        },
                        {
                            data: 'diagnosis_utama',
                            name: 'diagnosis_utama'
                        },

                        

                    ],
                    responsive: true,
                    scrollX: true,

                });


                $('#saveBtn').click(function (e) {
                    e.preventDefault();
                    $('#saveBtn').html('Sending..');

                    // memasang user_id



                    // Reset error messages
                    $('#keluhan_utamaError').text('');
                    $('#riwayat_penyakit_sekarangError').text('');
                    $('#diagnosis_utamaError').text('');
                    $('#rujukanError').text('');


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

                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Diagnosa baru ditambahkan!'])
                            @endcomponent
                        `);
                            
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

@extends('index')

<?php
    $treatment = App\Models\FacialTreatment::all();
    $skincare = App\Models\Skincare::where('skincare_stok', '>', 0)->get();
?>

@section('styles')
<style>
    form[disabled] {
            opacity: 0.5;
            pointer-events: none;
        }
</style>
    
@endsection

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

<div class="container-xl mt-3">
    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">Data Pasien</h3>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-outline-gr cariPasien" id="cari_pasien" value="search">Cari Pasien</button>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form id="FormPasien" name="userForm" class="form-horizontal">
                    <div class="row">
                    <input type="hidden" name="user_id" id="user_id">
                    @foreach ($form as $field)
                        <div class="form-group mb-3 col-md-{{ $field['width'] ?? 12 }}">
                            <label for="{{ $field['field'] }}" class="control-label">
                                {{ $field['label'] }}
                                @if ($field['required'] ?? false)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            @if ($field['type'] === 'textarea')
                                <textarea class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }}{{ $field['disabled'] ?? false ? 'disabled readonly' : '' }} ></textarea>
                            @elseif ($field['type'] === 'file')
                                <input type="file" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" {{ $field['required'] ?? false ? 'required' : '' }}{{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                            @elseif ($field['type'] === 'password')
                                <input type="password" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }}{{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                            @elseif ($field['type'] === 'email')
                                <input type="email" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }} {{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                            @elseif ($field['type'] === 'number')
                                <input type="number" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }} {{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                            @elseif ($field['type'] === 'date')
                                <input type="date" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }} {{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                            @elseif ($field['type'] === 'select')
                                <select class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" {{ $field['required'] ?? false ? 'required' : '' }}>
                                    <option value="" disabled selected>{{ $field['placeholder'] }}</option>
                                    @foreach ($field['options'] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }} {{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                            @endif
                            <span class="text-danger" id="{{ $field['field'] }}Error"></span>
                        </div>
                    @endforeach
                </div>
                    
                </form>
            </div>
        </div>
    </div>

    <div class="card disabled mt-4">
        <div class="card-header">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form id="FormPembelian" name="FormPembelian" class="form-horizontal">
                    <input type="hidden" name="no_rekam" id="no_rekam">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="pemeriksaan_rekTreatment">Treatment</label>
                                <div id="pemeriksaan_rekTreatment_wrapper">
                                    <div class="d-flex align-items-center treatment-row">
                                        <select class="form-control w-50 treatment-select" name="pemeriksaan_rekTreatment">
                                            <option value="">Pilih Treatment</option>
                                            @foreach ($treatment as $treat)
                                                <option value="{{ $treat->facialTreatment_id }}" data-harga="{{ $treat->facialTreatment_harga }}">
                                                    {{ $treat->facialTreatment_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" class="form-control w-25 mx-2 treatment-qty" name="pemeriksaan_jumlahTreatment" value="1" placeholder="Jumlah" readonly>
                                        <input type="number" class="form-control w-25 mx-2 treatment-total" name="totalHargaTreatment" placeholder="Total" disabled>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="pemeriksaan_rekSkincare">Skincare</label>
                                <div id="pemeriksaan_rekSkincare_wrapper">
                                    <div class="d-flex align-items-center skincare-row">
                                        <select class="form-control w-50 skincare-select" name="pemeriksaan_rekSkincare[]">
                                            <option value="">Pilih Skincare</option>
                                            @foreach ($skincare as $skin)
                                                <option value="{{ $skin->skincare_id }}" data-harga="{{ $skin->skincare_harga }}">
                                                    {{ $skin->skincare_nama }} - stok {{ $skin->skincare_stok }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" class="form-control w-25 mx-2 skincare-qty" name="pemeriksaan_jumlahSkincare[]" placeholder="Jumlah">
                                        <input type="number" class="form-control w-25 mx-2 skincare-total" name="totalHargaSkincare[]" placeholder="Total" disabled>
                                        <button type="button" class="btn btn-primary btn-md w-25 mx-2" onclick="addSkincare()">Tambah</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <label>Total Harga Semua:</label>
                                <input type="number" id="totalHargaSemua" class="form-control" disabled>
                            </div>
                        </div>
                        
                        <script>
                            function addSkincare() {
                                var newRow = `<div class="d-flex align-items-center skincare-row mt-2">
                                                <select class="form-control w-50 skincare-select" name="pemeriksaan_rekSkincare[]">
                                                    <option value="">Pilih Skincare</option>
                                                    @foreach ($skincare as $skin)
                                                        <option value="{{ $skin->skincare_id }}" data-harga="{{ $skin->skincare_harga }}">
                                                            {{ $skin->skincare_nama }} - stok {{ $skin->skincare_stok }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="number" class="form-control w-25 mx-2 skincare-qty" name="pemeriksaan_jumlahSkincare[]" placeholder="Jumlah">
                                                <input type="number" class="form-control w-25 mx-2 skincare-total" name="totalHargaSkincare[]" placeholder="Total" disabled>
                                                <button type="button" class="btn btn-danger btn-md w-25 mx-2" onclick="removeRow(this)">Hapus</button>
                                            </div>`;
                                $('#pemeriksaan_rekSkincare_wrapper').append(newRow);
                                updateListeners();
                            }
                        
                        
                            function removeRow(element) {
                                $(element).closest('.d-flex').remove();
                                updateTotalHarga();
                            }
                        
                            function updateListeners() {
                                $('.skincare-select, .skincare-qty, .treatment-select, .treatment-qty').off('change').on('change', function() {
                                    var row = $(this).closest('.d-flex');
                                    var harga = row.find('option:selected').data('harga') || 0;
                                    var jumlah = row.find('input[type=number]').val() || 0;
                                    var total = harga * jumlah;
                                    row.find('input[disabled]').val(total);
                                    updateTotalHarga();
                                });
                            }
                        
                            function updateTotalHarga() {
                                var totalHarga = 0;
                                $('.skincare-total, .treatment-total').each(function() {
                                    totalHarga += parseFloat($(this).val()) || 0;
                                });
                                $('#totalHargaSemua').val(totalHarga);
                            }
                        
                            $(document).ready(function() {
                                updateListeners();
                            });
                        </script> 
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gr mt-3" id="saveBtn">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>

{{-- Modal loading --}}
<x-popup.loading />

{{-- Modal konfirmasi --}}
<x-popup.modal_delete_confirmation />

{{-- Modal sukses struk pendaftaran --}}
@include('pages.layanans.pendaftaran.report.strukpendaftaran')



<script>
$(document).ready(function () { 

    $('#FormPembelian').find('select').prop('disabled', true);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').on('click', '.cariPasien', function () {
        var no_rm = $('#no_rm').val();

        if (!no_rm) {
            toastr.error('No. Rekam Medis harus diisi!');
            return false;
        }

        // $('#loadingModal').modal('show');

        $.get("{{ route('pendaftarans.show', '') }}" + '/' + no_rm)
            .done(function (data) {
                if (data) {
                    toastr.success('Pasien ditemukan!');

                    $('#nik').val(data.nik);
                    $('#nama_pasien').val(data.nama_pasien);
                    $('#tanggal_lahir').val(data.tanggal_lahir);
                    $('#jk').val(data.jk);
                    $('#agama').val(data.agama);
                    $('#pekerjaan').val(data.pekerjaan);
                    $('#kewarganegaraan').val(data.kewarganegaraan);
                    $('#alamat').val(data.alamat);
                    $('#no_hp').val(data.no_hp);
                    $('#email').val(data.email);
                    $('#no_rekam').val(data.no_rm);

                    $('#FormPembelian').find('select').prop('disabled', false);
                } else {
                    toastr.error('Pasien tidak ditemukan!');
                    $('#FormPembelian').find('select').prop('disabled', true);
                }

                // $('#loadingModal').modal('hide');

            })
            .fail(function () {
                toastr.error('Terjadi kesalahan saat mengambil data!');
                $('#FormPembelian').find('select').prop('disabled', true);
            })
            .always(function () {
                // $('#loadingModal').modal('hide');
            });
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $('#saveBtn').html('Sending..');

        $('#no_rmError').text('');

        var formData = new FormData($('#FormPembelian')[0]);
        $.ajax({
            data: formData,
            url: "{{ route('pembelian.store') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                var data = response.data;
                console.log(response);

                $('.error').text('');
                $('#saveBtn').html('Save Changes');

                toastr.success('Pendaftaran berhasil!');
                document.getElementById("nama_pasienstruk").innerHTML = data.nama_pasien;
                $('#pendaftaranTicketModal').modal('show');

                $('#FormPembelian').trigger("reset");
                $('#FormPasien').trigger("reset");

                setTimeout(function(){ location.reload(); }, 1000);

            },
            error: function (xhr) {
                $('#saveBtn').html('Save Changes');

                if (xhr.status === 421) {
                    toastr.error('Pasien sudah terdaftar di poli ini untuk tanggal yang dipilih!');
                } else if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.no_rm) {
                        $('#no_rmError').text(errors.no_rm[0]);
                    }
                    if (errors.waktu) {
                        $('#waktuError').text(errors.waktu[0]);
                    }
                    if (errors.dokter_id) {
                        $('#dokter_idError').text(errors.dokter_id[0]);
                    }
                    if (errors.tanggal_daftar) {
                        $('#tanggal_daftarError').text(errors.tanggal_daftar[0]);
                    }
                    if (errors.keluhan) {
                        $('#keluhanError').text(errors.keluhan[0]);
                    }
                } else {
                    toastr.error('Pendaftaran gagal ditambahkan!');
                }
            }
        });
    });
});

</script>
@endsection

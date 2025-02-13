@extends('index')

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
                {{-- Component form no modal --}}
                <x-form_nomodal :form="$form_daftar" :title="$title ?? env('APP_NAME')" />
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

    $('#userForm').find('input, select, textarea, button').prop('disabled', true);

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

        // Tampilkan loading
        $('#loadingModal').modal('show');

        $.get("{{ route('pendaftarans.show', '') }}" + '/' + no_rm, function (data) {

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

                $('#userForm').find('input, select, textarea, button').prop('disabled', false);
            } else {
                toastr.error('Pasien tidak ditemukan!');

                $('#userForm').find('input, select, textarea, button').prop('disabled', true);
            }

            // Tutup modal loading setelah data berhasil diambil atau tidak ditemukan
            $('#loadingModal').modal('hide');

        }).fail(function() {
            toastr.error('Terjadi kesalahan saat mengambil data!');

            $('#userForm').find('input, select, textarea, button').prop('disabled', true);

            // Tutup modal loading jika terjadi kesalahan
            $('#loadingModal').modal('hide');

        }).always(function() {
            // Pastikan modal loading tertutup setelah selesai proses (sukses atau gagal)
            $('#loadingModal').modal('hide');
            setTimeout(function() {
                $('#loadingModal').modal('hide');
            }, 500);
        });
    });

    $('#dokter_id').on('change', function() {
        $('#tanggal_daftar').attr('type', 'date');
        $('#tanggal_daftar').attr('min', new Date().toISOString().split('T')[0]);
    });

    $('#tanggal_daftar').on('change', function() {
        var dokterId = $('#dokter_id').val();
        var tanggal = $('#tanggal_daftar').val();
        $.ajax({
            type: 'POST',
            url: "{{ route('pendaftarans.getwaktubytanggal') }}",
            data: {
                tanggal: tanggal,
                dokter_id: dokterId
            },
            success: function(data) {
                $('#waktu').empty();
                if (data.waktu) {
                    $('#waktu').append('<option value="" selected>Pilih Jam</option>');
                    var now = new Date().getHours() + ':' + new Date().getMinutes();
                    $.each(data.waktu, function(index, value) {
                        if (value > now) {
                            $('#waktu').append('<option value="' + value + '">' + value + '</option>');
                        }
                    });
                    toastr.success('Jam tersedia!');
                } else {
                    $('#waktu').append('<option value="" selected>Tidak ada jam tersedia</option>');
                    toastr.error('Tidak ada jam tersedia!');
                }
            }
        });
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $('#saveBtn').html('Sending..');

        // Reset error messages
        $('#no_rmError').text('');
        $('#waktuError').text('');
        $('#dokter_idError').text('');
        $('#tanggal_daftarError').text('');
        $('#keluhanError').text('');

        $.ajax({
            data: {
                no_rm: $('#no_rm').val(),
                waktu: $('#waktu').val(),
                dokter_id: $('#dokter_id').val(),
                tanggal_daftar: $('#tanggal_daftar').val(),
                keluhan: $('#keluhan').val()
            },
            url: "{{ route('pendaftarans.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                var data = response.data;
                console.log(response);

                // Reset error messages
                $('.error').text('');
                $('#saveBtn').html('Save Changes');

                // Tampilkan alert sukses
                
                toastr.success('Pendaftaran berhasil!');
                
                document.getElementById("no_pendaftaranstruk").innerHTML = data.no_pendaftaran;
                document.getElementById("nama_dokterstruk").innerHTML = data.nama_dokter;
                document.getElementById("nama_pasienstruk").innerHTML = data.nama_pasien;

                $('#pendaftaranTicketModal').modal('show');

                //reset form
                $('#userForm').trigger("reset");
                $('#FormPasien').trigger("reset");
                
                
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

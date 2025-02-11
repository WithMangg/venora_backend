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
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="javascript:void(0)" class="btn btn-outline-gr d-none d-sm-inline-block" id="createNewUser">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="10" cy="10" r="7" />
                                    <line x1="7.5" y1="14.5" x2="17.5" y2="14.5" />
                                    <line x1="21" y1="21" x2="15" y2="15" /></svg>
                                {{ $title ?? env('APP_NAME') }}
                            </a>

                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

{{-- Alert --}}
<div id="alertPlaceholder" class="alert-position-top-right"></div>

<div class="container-xl mt-5">

    {{-- Modal Cek Pendaftaran --}}
    <div class="modal modal-blur fade" id="ajaxModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="cekPendaftaran" name="userForm" class="form-horizontal">
                        <div class="row">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="id_pendaftaran" name="id_pendaftaran" placeholder="Masukkan Nomor Pendaftaran" required>
                                <span class="text-danger" id="id_pendaftaranError"></span>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-12 mt-3 text-end">
                            <button type="" class="btn btn-gr" id="cariPendaftaranBarcode" value="scan">Scan Barcode</button>
                            <button type="submit" class="btn btn-gr" id="cariPendaftaran" value="create">Cari Nomor Pendaftaran</button>
                        </div>
                        
                    </form>

                </div>

            </div>
        </div>
    </div>


    <div class="row mb-5">
        <div class="col-md-6">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_form1 ?? env('APP_NAME') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <x-form_nobutton :form="$form1" :number="1" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_form2 ?? env('APP_NAME') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <x-form_nobutton :form="$form2" :number="2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-6">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_form3 ?? env('APP_NAME') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <x-form_nobutton :form="$form3" :number="3" />
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-gr" id="confirmBtn" value="confirm">Save changes</button>
            </div>
            
            
        </div>


        @include('pages.layanans.pendaftaran.report.strukantrian')
        {{-- Ajax Modal --}}

        {{-- Modal loading --}}
        <x-popup.loading />

        {{-- Modal Scanner --}}
        <x-scanner :form1="$form1" :form2="$form2" :form3="$form3" />

        {{-- Modal konfirmasi --}}
        <x-popup.modal_delete_confirmation />


        <script type="text/javascript">
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#createNewUser').click(function () {
                    $('#saveBtn').val("create-user");
                    $('#user_id').val('');
                    $('#cekPendaftaran').trigger("reset");
                    $('#modelHeading').html("{{ $title ?? env('APP_NAME') }}");
                    $('#ajaxModel').modal('show');
                });

                $('#cariPendaftaranBarcode').click(function () {
                    $('#ajaxModel').modal('hide');
                    $('#ajaxScanner').modal('show');
                });


                $('#cariPendaftaran').click(function (e) {
                    e.preventDefault();
                    $('#cariPendaftaran').html('Sending..');

                    // Reset error messages
                    $('#id_pendaftaranError').text('');

                    var no_pendaftaran = $('#no_pendaftaran').val()
                    $.ajax({
                        data: $('#cekPendaftaran').serialize(),
                        url: "{{ route('pendaftarans.cekpendaftaran.getinfopendaftaran') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#cekPendaftaran').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            $('#cariPendaftaran').html('Save Changes');

                            @foreach ($form1 as $item)
                                $('#{{ $item['field'] }}').val(data.{{ $item['field'] }});
                            @endforeach

                            @foreach ($form2 as $item)
                                $('#{{ $item['field'] }}').val(data.{{ $item['field'] }});
                            @endforeach

                            @foreach ($form3 as $item)
                                $('#{{ $item['field'] }}').val(data.{{ $item['field'] }});
                            @endforeach

                            // Tampilkan alert sukses
                            
                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Pencarian berhasil!'])
                            @endcomponent`)
            
                        },
                        error: function (xhr) {
                            $('#cariPendaftaran').html('Save Changes');

                            // Tampilkan pesan error
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                console.log(errors);
                                if (errors.no_pendaftaran) {

                                    $('#id_pendaftaranError').text(errors
                                        .no_pendaftaran[0]);
                                }
                            } else {
                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Pencarian gagal! Cek kembali Nomor Pendaftarannya'])
                            @endcomponent
                                `);
                            }
                        }
                    });
                });

                $('#confirmBtn').click(function (e) {
                e.preventDefault();
                $('#confirmBtn').html('Sending..');

                var no_pendaftaran = $('#no_pendaftaran').val()

                if ($('#tanggal_daftar').val() == '{{ date('Y-m-d') }}') {
                    $.ajax({
                        data: {
                            no_pendaftaran: no_pendaftaran
                        },
                        url: "{{ route('cek-pendaftarans.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (response) {
                            // console.log(response);
                            var data = response.data;
                            $('#confirmBtn').html('Save Changes');

                            // Tampilkan alert sukses
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'success', 'message' => 'Pendaftaran Berhasil di Konfirmasi'])
                                @endcomponent`)

                            document.getElementById("antrian_no").innerHTML = data.no_antrian;
                            document.getElementById("poli").innerHTML = data.nama_poli;
                            
                            $('#queueTicketModal').modal('show');


                        },
                        error: function (xhr) {
                            $('#confirmBtn').html('Save Changes');

                            // Tampilkan pesan error
                            if (xhr.status === 422) {
                                var error = `
                                @component('components.popup.alert', ['type' => 'danger', 'message' => 'Anda Belum Menginputkan Nomor Pendaftaran'])
                                @endcomponent`

                                $('#alertPlaceholder').append(error);

                            } else if (xhr.status === 400) {
                                var alertMessage = xhr.responseJSON.message;
                                var error = `
                                @component('components.popup.alert', ['type' => 'danger', 'message' => "Nomor Pendaftaran sudah di konfirmasi" ])
                                @endcomponent`

                                $('#alertPlaceholder').append(error);
                                
                            }
                        }
                    });
                } else {
                    $('#confirmBtn').html('Save Changes');

                    var error = `
                        @component('components.popup.alert', ['type' => 'danger', 'message' => "Lakukan Konfirmasi Pendaftaran pada Tanggal Pendaftaran" ])
                        @endcomponent`

                    $('#alertPlaceholder').append(error);
                }
            });

                
            });

            

        </script>
        @endsection


        
        
        

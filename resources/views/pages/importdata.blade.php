@extends('index')

@section('styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
    height: calc(2.25rem + 4px); 
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

.select2-container--bootstrap-5 .select2-selection__rendered {
    line-height: 1.5;
    font-size: 1rem;
}

.select2-container--bootstrap-5 .select2-selection__arrow {
    height: calc(2.25rem + 2px);
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

<div class="container-xl mt-5">
    <div class="row g-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                      <a href="#tabs-pasien-ex2" class="nav-link active" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <circle cx="12" cy="7" r="4" />
                          <path d="M5.5 21h13a2 2 0 0 0 2 -2v-1.5a5 5 0 0 0 -5 -5h-7a5 5 0 0 0 -5 5v1.5a2 2 0 0 0 2 2" />
                        </svg> Import Pasien</a>
                    </li>
                    <li class="nav-item">
                      <a href="#tabs-dokter-ex2" class="nav-link" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5.5 21h13a2 2 0 0 0 2 -2v-1.5a5 5 0 0 0 -5 -5h-7a5 5 0 0 0 -5 5v1.5a2 2 0 0 0 2 2" />
                      </svg> Import Dokter</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-obat-ex2" class="nav-link" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -7.5a5 5 0 1 1 7.5 7.5z" /></svg> Import Obat</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-kamar-ex2" class="nav-link" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 21c0 -2.599 2.101 -4.599 4.5 -4.599h1.79l-1.413 -1.413c-1.972 -1.971 -1.972 -5.181 0 -7.152h12.585c2.399 0 4.5 1.999 4.5 4.599v5.5c0 2.599 -2.101 4.599 -4.5 4.599h-13.79l1.414 1.414c1.972 1.969 1.972 5.181 0 7.152h-2.585c-2.399 0 -4.5 -1.999 -4.5 -4.599v-5.5" /></svg> Import Kamar</a>
                      </li>
                      <li class="nav-item">
                        <a href="#tabs-poli-ex2" class="nav-link" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 12m-3 3h6m-6 0h6" /><path d="M12 12m-3 3l3 -3m0 0l-3 3" /><path d="M3.458 10c0 1.054 -.989 2 -.989 2h1.026v3.999h-1.026c-.991 0 -1.026 -.947 -1.026 -2z" /><path d="M20.542 10c0 1.054 .987 2 .987 2h-1.026v3.999h1.026c.991 0 1.026 -.947 1.026 -2z" /></svg> Import Poli</a>
                      </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-pasien-ex2">
                        <div class="mb-3">
                            <form id="importPasienForm" name="importPasienForm" enctype="multipart/form-data">
                                <div class="mb-3">
                                <img src="{{ asset('images/importdata/importpasien.png') }}" class="text-center mb-3" width="50%" alt="Contoh Isi Data Pasien">

                                    <label for="file" class="form-label">File Excel (*.csv)</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary" id="importPasienBtn" value="import">Import</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabs-dokter-ex2">
                        <form id="importForm" name="importForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <img src="{{ asset('images/importdata/datalayanan.png') }}" class="text-center mb-3" width="50%" alt="Contoh Isi Data Layanan">

                                <label for="file" class="form-label">File Excel (*.csv)</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="importBtn" value="import">Import</button>
                        </form>
                    </div>
                    <div class="tab-pane" id="tabs-obat-ex2">
                        <form id="importObatForm" name="importObatForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <img src="{{ asset('images/importdata/importObat.png') }}" class="text-center mb-3" width="50%" alt="Contoh Isi Data Layanan">

                                <label for="file" class="form-label">File Excel (*.csv)</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="importObatBtn" value="import">Import</button>
                        </form>
                    </div>
                    <div class="tab-pane" id="tabs-kamar-ex2">
                        <form id="importKamarForm" name="importKamarForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <img src="{{ asset('images/importdata/importkamar.png') }}" class="text-center mb-3" width="50%" alt="Contoh Isi Data Layanan">

                                <label for="file" class="form-label">File Excel (*.csv)</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="importKamarBtn" value="import">Import</button>
                        </form>
                    </div>
                    <div class="tab-pane" id="tabs-poli-ex2">
                        <form id="importPoliForm" name="importPoliForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <img src="{{ asset('images/importdata/importpoli.png') }}" class="text-center mb-3" width="50%" alt="Contoh Isi Data Layanan">

                                <label for="file" class="form-label">File Excel (*.csv)</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="importPoliBtn" value="import">Import</button>
                        </form>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#importPasienBtn').click(function (e) {
                    e.preventDefault();
                    var formData = new FormData($('#importPasienForm')[0]);
                    $('#saveBtn').html('Sending..');


                    $.ajax({
                        type: 'POST',
                        url: "{{ route('import.pasien') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#importPasienForm').trigger("reset");
                            $('#saveBtn').html('Import');
                            
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'success', 'message' => 'Data berhasil diimport!'])
                                @endcomponent
                            `);
                        },
                        error: function (xhr) {
                            $('#saveBtn').html('Import');

                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'danger', 'message' => 'Data gagal diimport!'])
                                @endcomponent
                            `);
                        }
                    });
                });

                $('#importPoliBtn').click(function (e) {
                    e.preventDefault();
                    var formData = new FormData($('#importPoliForm')[0]);
                    $('#saveBtn').html('Sending..');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('import.poli') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#importPoliForm').trigger("reset");
                            $('#saveBtn').html('Import');
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'success', 'message' => 'Data berhasil diimport!'])
                                @endcomponent
                            `);
                        },
                        error: function (xhr) {
                            $('#saveBtn').html('Import');

                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'danger', 'message' => 'Data gagal diimport!'])
                                @endcomponent
                            `);
                        }
                    });
                });

                $('#importKamarBtn').click(function (e) {
                    e.preventDefault();
                    var formData = new FormData($('#importKamarForm')[0]);
                    $('#saveBtn').html('Sending..');


                    $.ajax({
                        type: 'POST',
                        url: "{{ route('import.kamar') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#importKamarForm').trigger("reset");
                            $('#saveBtn').html('Import');
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'success', 'message' => 'Data berhasil diimport!'])
                                @endcomponent
                            `);
                        },
                        error: function (xhr) {
                            $('#saveBtn').html('Import');

                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'danger', 'message' => 'Data gagal diimport!'])
                                @endcomponent
                            `);
                        }
                    });
                });

                $('#importObatBtn').click(function (e) {
                    e.preventDefault();
                    var formData = new FormData($('#importObatForm')[0]);
                    $('#saveBtn').html('Sending..');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('import.obat') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#importObatForm').trigger("reset");
                            $('#saveBtn').html('Import');
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'success', 'message' => 'Data berhasil diimport!'])
                                @endcomponent
                            `);
                        },
                        error: function (xhr) {
                            $('#saveBtn').html('Import');

                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'danger', 'message' => 'Data gagal diimport!'])
                                @endcomponent
                            `);
                        }
                    });
                });

                
            });

        </script>
        @endsection

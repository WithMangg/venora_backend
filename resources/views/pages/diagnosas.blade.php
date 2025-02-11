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
                      <a href="#tabs-home-ex2" class="nav-link active" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <polyline points="5 12 3 12 12 3 21 12 19 12" />
                          <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                          <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                        </svg> Input Layanan</a>
                    </li>
                    <li class="nav-item">
                      <a href="#tabs-profile-ex2" class="nav-link" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg> Import Layanan</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-home-ex2">
                        <div class="mb-3">
                            <form id="userForm" name="userForm" class="form-horizontal">
                                <div class="row">
                                <input type="hidden" name="user_id" id="user_id">
                                @foreach ($form as $field)
                                    <div class="form-group mb-3 col-md-{{ $field['width'] ?? 12 }}">
                                        <label for="{{ $field['field'] }}" class="control-label" {{ $field['hidden'] ?? false ? 'hidden' : '' }}>
                                            {{ $field['label'] }}
                                            @if ($field['required'] ?? false)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        @if ($field['type'] === 'number')
                                            <input type="number" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }} {{ $field['disabled'] ?? false ? 'disabled' : '' }}>
                                        @elseif ($field['type'] === 'select')
                                            <select class="select2 form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" {{ $field['required'] ?? false ? 'required' : '' }}>
                        <option value="" disabled selected>{{ $field['placeholder'] }}</option>
                                                
                                                @foreach ($field['options'] as $value => $label)
                                                    <option value="{{ $label }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}" placeholder="{{ $field['placeholder'] }}" {{ $field['required'] ?? false ? 'required' : '' }} {{ $field['disabled'] ?? false ? 'disabled' : '' }} {{ $field['hidden'] ?? false ? 'hidden' : '' }}>
                                        @endif
                                        <span class="text-danger" id="{{ $field['field'] }}Error"></span>
                                    </div>
                                @endforeach
                            </div>
                                <div class="col-sm-offset-2 col-sm-10 mt-3">
                                    <button type="submit" class="btn btn-gr" id="saveBtn" value="create">Simpan Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabs-profile-ex2">
                        <form id="importForm" name="importForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <img src="{{ asset('images/importdata/datalayanan.png') }}" class="text-center mb-3" width="50%" alt="Contoh Isi Data Layanan">
                                <label for="file" class="form-label">File Excel (*.csv)</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="importBtn" value="import">Import</button>
                        </form>
                    </div>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Data Layanan Pemeriksaan') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <table class="table" id="laravel_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kategori</th>
                                    <th>Nama Layanan</th>
                                    <th>Harga Layanan</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
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


                $('.select2').select2({
                    placeholder: "Masukkan atau pilih kategori",
                    tags: true, 
                    tokenSeparators: [','],
                    theme: "bootstrap-5",
                });



                $('#laravel_datatable').DataTable({
                    layout: {
                        topStart: {
                            buttons: [{
                                    extend: 'copy',
                                    text: '<i class="fas fa-copy"></i> Copy',
                                    className: 'btn btn-primary btn-sm'
                                    
                                },
                                {
                                    extend: 'csv',
                                    text: '<i class="fas fa-file-csv"></i> CSV',
                                    className: 'btn btn-success btn-sm'
                                },
                                {
                                    extend: 'excel',
                                    text: '<i class="fas fa-file-excel"></i> Excel',
                                    className: 'btn btn-info btn-sm',
                                    exportOptions: {
                                        columns: [ 0,1,2,3]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="fas fa-file-pdf"></i> PDF',
                                    className: 'btn btn-danger btn-sm'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print"></i> Print',
                                    className: 'btn btn-secondary btn-sm'
                                }
                            ],
                        },
                    },
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('diagnosas.index') }}",
                    columns: [{
                            data: 'id',
                            name: 'id',
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'kategori',
                            name: 'kategori'
                        },
                        {
                            data: 'diagnosa',
                            name: 'diagnosa'
                        },

                        {
                            data: 'harga',
                            name: 'harga',
                            render: function (data, type, row) {
                                return 'Rp ' + parseFloat(data).toLocaleString('id-ID', {
                                    minimumFractionDigits: 0
                                });
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

                $('body').on('click', '.editProduct', function () {
                    var id = $(this).data('id');
                    $.get("{{ route('diagnosas.index') }}" + '/' + id + '/edit', function (
                        data) {
                        $('#modelHeading').html("Edit {{ $title ?? env('APP_NAME') }}");
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModel').modal('show');
                        $('#user_id').val(data.kd_diagnosa);
                        $('#diagnosa').val(data.diagnosa);
                        $('#kategori').val(data.kategori).trigger('change');
                        $('#harga').val(data.harga);
                    })

                });

                $('#saveBtn').click(function (e) {
                    e.preventDefault();
                    $('#saveBtn').html('Mengirim..');

                    // Reset error messages
                    $('#diagnosaError').text('');
                    $('#hargaError').text('');


                    var actionType = $(this).val();
                    var url = actionType === "create" ? "{{ route('diagnosas.store') }}" :
                        "{{ route('diagnosas.index') }}/" + $('#user_id').val();

                    // Tentukan jenis permintaan (POST atau PUT)
                    var requestType = actionType === "create" ? "POST" : "PUT";

                    $.ajax({
                        data: $('#userForm').serialize(),
                        url: url,
                        type: requestType,
                        dataType: 'json',
                        success: function (data) {
                            $('#userForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            $('#laravel_datatable').DataTable().ajax.reload();
                            $('#saveBtn').html('Save Changes');

                            // Tampilkan alert sukses
                            if (actionType === "create-user") {
                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Layanan baru ditambahkan!'])
                            @endcomponent
                        `);
                            } else {
                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Layanan diperbarui!'])
                            @endcomponent
                        `);
                            }

                            document.location.reload(true);
                            window.location.reload(true);
                        },
                        error: function (xhr) {
                            $('#saveBtn').html('Simpan Data');

                            // Tampilkan pesan error
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                if (errors.diagnosa) {
                                    $('#diagnosaError').text(errors.diagnosa[0]);
                                }
                                if (errors.harga) {
                                    $('#hargaError').text(errors.harga[0]);
                                }

                            } else {
                                $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Layanan gagal ditambahkan!'])
                            @endcomponent
                        `);
                            }

                            document.location.reload(true);
                            window.location.reload(true);
                        }
                    });
                });

                
            });

            $('body').on('click', '.deleteProduct', function () {
                var user_id = $(this).data('id');
                $('#confirmDeleteModal').modal('show');

                // Handle konfirmasi hapus
                $('#confirmDeleteBtn').off('click').on('click', function () {
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('diagnosas.index') }}/" + user_id,
                        success: function (data) {

                            $('#laravel_datatable').DataTable().ajax.reload();
                            $('#confirmDeleteModal').modal('hide');



                            // Tampilkan alert sukses
                            $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Layanan berhasil dihapus!'])
                            @endcomponent
                        `);

                            document.location.reload(true);
                            window.location.reload(true);
                        },
                        error: function (xhr) {
                            $('#confirmDeleteModal').modal('hide');

                            // Tampilkan alert error
                            $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Layanan gagal dihapus!'])
                            @endcomponent
                        `);
                        }
                    });
                });               

            });

            $('#importBtn').click(function (e) {
                    e.preventDefault();
                    var formData = new FormData($('#importForm')[0]);

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('diagnosas.import') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#importForm').trigger("reset");
                            $('#laravel_datatable').DataTable().ajax.reload();
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'success', 'message' => 'Data berhasil diimport!'])
                                @endcomponent
                            `);
                        },
                        error: function (xhr) {
                            $('#alertPlaceholder').html(`
                                @component('components.popup.alert', ['type' => 'danger', 'message' => 'Data gagal diimport!'])
                                @endcomponent
                            `);
                        }
                    });
                });

        </script>
        @endsection

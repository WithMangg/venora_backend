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
                            <a href="javascript:void(0)" class="btn btn-gr d-none d-sm-inline-block" id="createNewUser">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" /></svg>
                                Create {{ $title ?? env('APP_NAME') }}
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <table class="table" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Poli</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

{{-- Ajax Modal --}}
<x-form :form="$form" :title="$title ?? env('APP_NAME')" />

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
            layout: {
                topStart: {
                    buttons: [
                        {
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
                            className: 'btn btn-info btn-sm'
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
            ajax: "{{ route('polis.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                },
                {
                    data: 'nama_poli',
                    name: 'nama_poli'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
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

        $('#createNewUser').click(function () {
            $('#saveBtn').val("create-user");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Add New {{ $title ?? env('APP_NAME') }}");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editProduct', function () {
            var user_id = $(this).data('id');
            $.get("{{ route('polis.index') }}" + '/' + user_id + '/edit', function (
                data) {
                $('#modelHeading').html("Edit {{ $title ?? env('APP_NAME') }}");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#user_id').val(data.id);
                $('#kd_poli').val(data.kd_poli);
                $('#nama_poli').val(data.nama_poli);
                $('#deskripsi').val(data.deskripsi);
            })

        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Sending..');

            // Reset error messages
            $('#nama_poliError').text('');
            $('#kd_poliError').text('');


            var actionType = $(this).val();
            var url = actionType === "create-user" ? "{{ route('polis.store') }}" :
                "{{ route('polis.index') }}/" + $('#user_id').val();

            // Tentukan jenis permintaan (POST atau PUT)
            var requestType = actionType === "create-user" ? "POST" : "PUT";

            $.ajax({
                data: $('#userForm').serialize(),
                url: url,
                type: requestType,
                dataType: 'json',
                success: function (data) {
                    $('#userForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#saveBtn').html('Simpan Data');

                    // Tampilkan alert sukses
                    if (actionType === "create-user") {
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Obat baru ditambahkan!'])
                            @endcomponent
                        `);
                    } else {
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Obat diperbarui!'])
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
                        if (errors.kd_poli) {
                            $('#kd_poliError').text(errors.kd_poli[0]);
                        }
                        if (errors.nama_poli) {
                            $('#nama_poliError').text(errors.nama_poli[0]);
                        }

                    } else {
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Obat gagal ditambahkan!'])
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
                url: "{{ route('polis.index') }}/" + user_id,
                success: function (data) {
                    
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#confirmDeleteModal').modal('hide');
                    


                    // Tampilkan alert sukses
                    $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Obat berhasil dihapus!'])
                            @endcomponent
                        `);

                        document.location.reload(true);
                        window.location.reload(true);
                },
                error: function (xhr) {
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert error
                    $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Obat gagal dihapus!'])
                            @endcomponent
                        `);
                }
            });
        });
    });

</script>
@endsection

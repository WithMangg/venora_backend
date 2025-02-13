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
                            <a href="javascript:void(0)" class="btn btn-gr" id="createNewUser">
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
                            @foreach ($form as $key => $value)
                                <th>{{ $value['label'] }}</th>
                            @endforeach
                            <th>Tindakan</th>
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
                            className: 'btn btn-info btn-sm',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude last column (action column)
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude last column (action column)
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Print',
                            className: 'btn btn-secondary btn-sm',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude last column (action column)
                            }
                        }
                    ],
                },
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('facialtreatment.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                },
                @foreach ($form as $key => $value)
                    {
                        data: '{{ $value['field'] }}',
                        name: '{{ $value['field'] }}',
                        @if ($value['type'] == 'file')
                            render: function(data, type, row) {
                                return '<div style="display: flex; align-items: center; justify-content: center;"><img src="data:image/png;base64,' + data + '" alt="Image" style="width: 50px; height: 50px;" /></div>';
                            }
                        @endif
                    },
                @endforeach
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
            $.get("{{ route('facialtreatment.index') }}" + '/' + user_id + '/edit', function (
                data) {
                $('#modelHeading').html("Edit {{ $title ?? env('APP_NAME') }}");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#user_id').val(data.id);
                @foreach ($form as $key => $value)
                    if (data.{{ $value['field'] }}) {
                        if ('{{ $value['type'] }}' === 'file') {
                            $('#{{ $value['field'] }}').attr('src', 'data:image/png;base64,' + data.{{ $value['field'] }});
                        } else if ('{{ $value['type'] }}' === 'select') {
                            $('#{{ $value['field'] }}').val(data.{{ $value['field'] }}).trigger('change');
                        } else {
                            $('#{{ $value['field'] }}').val(data.{{ $value['field'] }});
                        }
                    }
                @endforeach
            })

        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Sending..');

            // Reset error messages
            @foreach ($form as $key => $value)
                $('#{{ $value['field'] }}Error').text('');
            @endforeach

            var actionType = $(this).val();
            var url = actionType === "create-user" ? "{{ route('facialtreatment.store') }}" :
                "{{ route('facialtreatment.index') }}/" + $('#user_id').val();

            // Tentukan jenis permintaan (POST atau PUT)
            var requestType = actionType === "create-user" ? "POST" : "POST";

            var formData = new FormData($('#userForm')[0]);

            console.log('Form Data:', Array.from(formData.entries()));


            $.ajax({
                data: formData,
                url: "{{ route('facialtreatment.store') }}",
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#userForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#saveBtn').html('Simpan Data');

                    // Tampilkan alert sukses
                    if (actionType === "create-user") {
                        toastr.success('Facial Treatment baru ditambahkan!');
                    } else {
                        toastr.success('Facial Treatment diperbarui!');
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').html('Simpan Data');

                    // Tampilkan pesan error
                    if (xhr.status === 422) {
                        // displayValidationErrors(xhr.responseJSON.errors);
                        let errors = xhr.responseJSON.errors;
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('#'+key+'Error').text(value[0]);
                        });
                    } else {
                        toastr.error('Facial Treatment gagal ditambahkan!');
                    }
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
                url: "{{ route('facialtreatment.index') }}/" + user_id,
                success: function (data) {
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert sukses
                    toastr.success('Facial Treatment berhasil dihapus!');
                },
                error: function (xhr) {
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert error
                    toastr.error('Obat gagal dihapus!');
                }
            });
        });
    });

</script>
@endsection

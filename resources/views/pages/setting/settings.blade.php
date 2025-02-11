<?php 
    $web = App\Models\SettingWeb::first();
?>

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
            <x-form_nomodal :form="$form" :title="$title ?? env('APP_NAME')" />
        </div>



{{-- Modal loading --}}
<x-popup.loading />

{{-- Modal konfirmasi --}}
<x-popup.modal_delete_confirmation />


<script type="text/javascript">
    $(document).ready(function () {
        $('#site_name').val('{{ $web ? $web->site_name : '' }}');
        $('#alamat').val('{{ $web ? $web->alamat : '' }}');
        $('#email').val('{{ $web ? $web->email : '' }}');
        $('#phone').val('{{ $web ? $web->phone : '' }}');
        $('#description').val('{{ $web ? $web->description : '' }}');
        $('#address').val('{{ $web ? $web->address : '' }}');
        


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('polis.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
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


        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Sending..');

            // Reset error messages
            $('#site_nameError').text('');
            $('#faviconError').text('');
            $('#logoError').text('');


            var formData = new FormData($('#userForm')[0]);
            

            $.ajax({
                data: formData,
                url: "{{ route('settings.store') }}",
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#saveBtn').html('Save Changes');

                    // Tampilkan alert sukses
                    
                    $('#alertPlaceholder').html(`
                        @component('components.popup.alert', ['type' => 'success', 'message' => 'Pengaturan Web Berhasil diperbarui!'])
                        @endcomponent
                    `);
                    
                },
                error: function (xhr) {
                    $('#saveBtn').html('Save Changes');

                    // Tampilkan pesan error
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.favicon) {
                            $('#faviconError').text(errors.favicon[0]);
                        }
                        if (errors.logo) {
                            $('#logoError').text(errors.logo[0]);
                        }
                        if (errors.site_name) {
                            $('#site_nameError').text(errors.site_name[0]);
                        }

                    } else {
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Obat gagal ditambahkan!'])
                            @endcomponent
                        `);
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

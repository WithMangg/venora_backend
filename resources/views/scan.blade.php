<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>


</head>
<body>
    <!-- resources/views/scan.blade.php -->
    <div id="qr-reader" style="width: 300px;"></div>
    <div id="qr-reader-results"></div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Tampilkan hasil yang dipindai
            // console.log(`Code matched = ${decodedText}`, decodedResult);

            // Anda dapat mengirim hasil ini ke Laravel dengan AJAX
            alert(`Hasil QR Code: ${decodedText}`);

            $.ajax({
                        url: "{{ route('pendaftarans.cekpendaftaran.getinfopendaftaran') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                            qr_code: decodedText
                        },
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
        }

        function onScanFailure(error) {
            // Handle scan failure
            console.warn(`QR Code scan failed. Error: ${error}`);
        }

        // Inisialisasi pemindai QR Code
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>

</body>
</html>
<style>
    .ticket .header {
        font-size: 10px;
        text-align: right;
    }

    .ticket .logo {
        font-weight: bold;
        font-size: 24px;
        margin-top: 10px;
    }

    .ticket .logo span {
        color: green;
    }

    .ticket .address {
        font-size: 12px;
        margin-top: 5px;
    }

    .ticket .divider {
        border-top: 1px solid #000;
        margin: 15px 0;
    }

    .ticket .queue-title {
        font-weight: bold;
        font-size: 18px;
    }

    .ticket .department {
        font-size: 14px;
        margin-top: 10px;
    }

    .ticket .queue-number {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .ticket .footer {
        margin-top: 20px;
        font-size: 12px;
        color: #555;
    }
</style>

<?php 
$web = App\Models\SettingWeb::first();
?>

<div class="modal fade" id="pendaftaranTicketModal" tabindex="-1" aria-labelledby="pendaftaranTicketModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="pendaftaranTicketModalLabel">Nomor Pendaftaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="ticket">
                <div class="header">{{ now()->format('d F Y H:i:s') }}</div>
                <div class="logo">
                @if(isset($web) && $web->logo)
                    <img src="data:image/png;base64,{{ $web->logo }}" width="150" height="48" alt="Opni Care" class="navbar-brand-image">
                @else
                    <img src="{{ asset('dist/img/opnicare.png') }}" width="150" height="48" alt="Opni Care" class="navbar-brand-image">
                @endif
                </div>
                <div class="address">
                    {{ $web->address }}
                </div>
                <div class="divider"></div>
                <table class="">
                    <tr>
                        <td>Nama Dokter</td>
                        <td>:</td>
                        <td id="nama_dokterstruk"></td>
                    </tr>
                    <tr>
                        <td>No. Pendaftaran</td>
                        <td>:</td>
                        <td id="no_pendaftaranstruk"></td>
                    </tr>
                    <tr>
                        <td>Nama Pasien</td>
                        <td>:</td>
                        <td id="nama_pasienstruk"></td>
                    </tr>

                </table>
                <div class="footer">Lanjutkan ke bagian Validasi untuk mendapat no antrian</div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .modal-header {
        background-color: #007037;
        color: white;
    }

    .modal-footer {
        background-color: #ecf0f1;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 10px;
        color: #333;
    }

    .modal-body ul {
        margin-bottom: 20px;
    }

    .modal-body ul li {
        margin-bottom: 8px;
    }

    .table th,
    .table td {
        padding: 8px;
        text-align: left;
    }

    .table th {
        background-color: #f0f0f0;
    }

</style>

<div class="modal fade" id="ajaxModel" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel"></h5>
                <button type="button" class="btn btn-secondary" id="editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data">
                    Request Edit
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>No Transaksi</th>
                                <td id="no_pembayaran"></td>
                            </tr>
                            <tr>
                                <th>No Rekam Medis</th>
                                <td id="no_rm"></td>
                            </tr>
                            <tr>
                                <th>Nama Pasien</th>
                                <td id="nama_pasien"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Dokter</th>
                                <td id="dokter"></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td id="tanggal_pemeriksaan"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="section-title">Administrasi</div>            
                <table class="table table-bordered mb-0" id="tindakanTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="65%">Daftar Tindakan</th>
                            <th width="10%">Jumlah</th>
                            <th width="20%">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    
                    
                </table>

                <div class="row mt-0">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <table class="table table-bordered">
                            <tr>
                                <th>Total</th>
                                <input type="hidden" id="totalharga">
                                <td width="62%" id="total"></td>
                            </tr>
                            <tr>
                                <th>Bayar</th>
                                <input type="hidden" id="user_id">
                                <td id="fillbayar"></td>
                                <td id="checkbayar" width="62%"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">Rp. </span></div><input type="number" id="bayar" class="form-control text-end" /></div></td>
                                <script>
                                    document.getElementById('bayar').addEventListener('input', function (e) {
                                        // Ambil nilai input dan hapus format sebelumnya
                                        let inputVal = e.target.value.replace(/\./g, '');
                                
                                        // Format nilai menjadi ribuan
                                        let formattedVal = new Intl.NumberFormat('id-ID').format(inputVal);
                                
                                        // Tampilkan kembali nilai dengan format
                                        e.target.value = formattedVal;

                                        let total = document.getElementById('totalharga').value;
                                        console.log(total);

                                        let bayar = e.target.value.replace(/\./g, '');
                                        console.log(bayar);

                                        if (bayar != '') {
                                            let kembali = parseInt(bayar) - parseInt(total);
                                            document.getElementById('kembaliharga').value = kembali;
                                            console.log(kembali);
                                            $('#kembali').html(`<span style="float: left;">Rp.</span><span style="float: right;">${kembali.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span>`);
                                        }
                                    });
                                </script>
                                
                            </tr>
                            <tr>
                                <th>Kembali</th>
                                <input type="hidden" id="kembaliharga">
                                <td width="62%" id="kembali"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Anjuran Dokter -->
                <div class="mt-3">
                    <h3>Peringatan dalam Transaksi</h3>
                    <ul>
                        <li>Pastikan data transaksi sesuai sebelum melakukan pembayaran.</li>
              
                    </ul>
                </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveBtn">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
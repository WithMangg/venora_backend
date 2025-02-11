<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ObatImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Generate kode unik no_rm
        $obatCount = Obat::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        $obatKD = str_pad($obatCount + 1, 2, '0', STR_PAD_LEFT);

        $medicinePart = substr($obatKD, -3);
        $uniqueCode = "OB" . date('ym')  . $medicinePart . rand(100, 999);

        // Simpan data pasien
        $medicine = Obat::updateOrCreate(
            [
                'nama_obat' => $row[0],],
            [
                'medicine_id' => $uniqueCode,
                'nama_obat' => $row[0],
                'nama_generik' => $row[1],
                'kategori' => $row[2],
                'bentuk_dosis' => $row[3],
                'kekuatan' => $row[4],
                'harga' => $row[5],
                'jumlah_stok' => $row[6],
                'tanggal_kedaluwarsa' => $row[7],
                'produsen' => $row[8],
                'instruksi_penggunaan' => $row[9],
                'efek_samping' => $row[10],
                'instruksi_penyimpanan' => $row[11],
            ]
        );

        // Kembalikan objek Pasien yang baru dibuat
        return $medicine;
    }
}

<?php

namespace App\Imports;

use App\Models\Diagnosa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DiagnosaImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ambil kode diagnosa terakhir untuk generate kode unik
        $latestDiagnosa = Diagnosa::orderBy('id', 'desc')->first();
        $newCode = '001';

        if ($latestDiagnosa) {
            $lastCode = intval(substr($latestDiagnosa->kd_diagnosa, -3));
            $newCode = str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        // Cek apakah ada diagnosa yang sama
        $diagnosaExists = Diagnosa::where([
            ['kategori', '=', $row[0]],
            ['diagnosa', '=', $row[1]],
        ])->first();

        if ($diagnosaExists) {
            $diagnosaExists->update([
                'kd_diagnosa' => $newCode,
                'harga' => $row[2],
            ]);

            return $diagnosaExists;
        } else {
            // Jika tidak, buat data diagnosa baru
            return Diagnosa::Create(
                [
                    'kd_diagnosa' => $newCode,
                    'kategori'    => $row[0], // Kolom kategori
                    'diagnosa'    => $row[1], // Kolom diagnosa
                    'harga'       => $row[2], // Kolom harga
                ]
            );
        }
    }
}

<?php

namespace App\Imports;

use App\Models\Kamar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KamarImport implements ToModel, WithStartRow
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
        $data = Kamar::updateOrCreate(
            ['tipe_kamar' => $row[0]],
            [
                'tipe_kamar' => $row[0],
                'fasilitas' => $row[1],
                'tarif_kamar' => $row[2] ?? null,
                'jumlah_kamar' => $row[3] ?? null,
                'status' => $row[4],
            ]
        );

        return $data;
    }
}

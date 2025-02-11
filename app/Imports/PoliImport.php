<?php

namespace App\Imports;

use App\Models\Poli;
use Maatwebsite\Excel\Concerns\ToModel;

class PoliImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $poli = Poli::Create(
            [
                'kd_poli' => $row[0],
                'nama_poli' => $row[1],
                'deskripsi' => $row[2] ?? null,
            ]
        );

        return $poli;
    }
}

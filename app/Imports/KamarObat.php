<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;

class KamarObat implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Obat([
            //
        ]);
    }
}

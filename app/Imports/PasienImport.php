<?php

namespace App\Imports;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PasienImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Generate kode unik no_rm
        $pasienCount = Pasien::whereYear('created_at', date('Y'))
                            ->whereMonth('created_at', date('m'))
                            ->count();
        $pasienRM = str_pad($pasienCount + 1, 3, '0', STR_PAD_LEFT);
        $no_rm = "RM" . date('ym') . substr($pasienRM, -3);

        // Simpan data pasien
        $pasien = Pasien::create([
            'nik' => $row[0],
            'nama_pasien' => $row[1],
            'alamat' => $row[2] ?? null,
            'email' => $row[3] ?? null,
            'no_hp' => $row[4] ?? null,
            'tanggal_lahir' => $row[5],
            'jk' => $row[6],
            'pekerjaan' => $row[7] ?? null,
            'kewarganegaraan' => $row[8] ?? null,
            'agama' => $row[9] ?? null,
            'pendidikan' => $row[10] ?? null,
            'status' => $row[11]  ?? null,
            'no_rm' => $no_rm
        ]);

        Log::info('Row being processed:', $row);


        // Update atau buat entitas User yang terkait dengan pasien
        $user = User::updateOrCreate(
            ['email' => $row[3]],  // Jika email sudah ada, update; jika tidak, buat baru
            [
                'name' => $row[1], 
                'email' => $row[3], 
                'password' => Hash::make('password'), // Password default bisa diganti sesuai kebutuhan
                'role_id' => '2', // Role pasien
            ]
        );

        // Kembalikan objek Pasien yang baru dibuat
        return $pasien;
    }

}

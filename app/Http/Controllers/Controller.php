<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;

 /**
    * @OA\Info(
    *      version="1.0.0",
    *      title="Dokumentasi API MINI OPNICARE",
    *      description="Dokumentasi ini berisi panduan lengkap tentang penggunaan API Mini Opnicare, sebuah antarmuka yang dirancang untuk mendukung sistem rekam medis elektronik di klinik. API ini menyediakan berbagai endpoint untuk mengakses dan mengelola data kesehatan, seperti informasi pasien, jadwal janji temu, resep elektronik, dan layanan lainnya.",
    *      @OA\Contact(
    *          email="rohmanuyeoke@gmail.com"
    *      ),
    *      @OA\License(
    *          name="Apache 2.0",
    *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
    *      )
    * )
    *
    * @OA\Server(
    *      url=L5_SWAGGER_CONST_HOST,
    *      description="Demo API Server Mini Opnicare"
    * )
    */
abstract class Controller
{
    public function storeRiwayat($userId, $table, $action, $description)
    {
        // Validasi input jika diperlukan
        $data = [
            'user_id' => $userId,
            'table' => $table,
            'aksi' => $action,
            'keterangan' => $description,
        ];

        // Simpan ke tabel riwayat
        Riwayat::create($data);
    }
}

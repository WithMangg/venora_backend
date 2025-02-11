<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataPoli;
use App\Models\DetailTransaksiObat;
use App\Models\Dokter;
use App\Models\Kamar;
use App\Models\Keluhan;
use App\Models\Keranjang;
use App\Models\KeranjangObat;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\TransaksiObat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login",
     *     description="Login",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="user@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="token", type="string", example="1|SxK4s3Jn0XxZT3L7D7U7xuqjZm4m"),
     *             @OA\Property(property="no_rm", type="string", example="1234567890"),
     *             @OA\Property(property="nama", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }


        $user = User::where('email', $request->email)->first();
        $pasien = Pasien::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'no_rm' => $pasien->no_rm,
                'nama' => $pasien->nama_pasien
            ], 200);
        }

        // Authentication failed
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"User"},
     *     summary="Get user data",
     *     description="Get user data",
    *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User data",
     *         @OA\JsonContent(
     *             @OA\Property(property="no_rm", type="string", example="1234567890"),
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="jenis_kelamin", type="string", example="Laki-laki"),
     *             @OA\Property(property="tanggal_lahir", type="string", example="1990-01-01"),
     *             @OA\Property(property="alamat", type="string", example="Jl. Jend. Sudirman No.123"),
     *             @OA\Property(property="no_hp", type="string", example="081234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getUserData(Request $request)
    {
        // Mendapatkan pengguna yang terautentikasi
        $user = $request->user();

        $pasien = Pasien::where('email', $user->email)->first();

        return response()->json($pasien);
    }

    public function generateUniqueCode($id, $poli_id, $tanggal) {
        $dokterPart = substr($id, -3);

        $poli = Poli::find($poli_id);
        
        $namaPoli = $poli->kd_poli;
        $date = date('Ymd', strtotime($tanggal));
        

        $uniqueCode = $namaPoli . $date . $dokterPart;

        return $uniqueCode;
    }

    /**
     * @OA\Get(
     *     path="/api/getKamar",
     *     tags={"Kamar"},
     *     summary="Get all kamar",
     *     description="Get all kamar",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="tipe_kamar", type="string", example="Kelas 1"),
     *                 @OA\Property(property="fasilitas", type="string", example="AC, TV"),
     *                 @OA\Property(property="tarif_kamar", type="integer", example=100000),
     *                 @OA\Property(property="jumlah_kamar", type="integer", example=10),
     *                 @OA\Property(property="status", type="string", example="tersedia")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getKamar()
    {
        $Kamar = Kamar::all();
        return response()->json($Kamar);
    }

    /**
     * @OA\Get(
     *     path="/api/getDokter",
     *     tags={"Dokter"},
     *     summary="Get all active dokter",
     *     description="Retrieve a list of all active dokter",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="Dr. John Doe"),
     *                 @OA\Property(property="spesialisasi", type="string", example="Cardiology"),
     *                 @OA\Property(property="status", type="string", example="Aktif")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getDokter()
    {
        $dokter = Dokter::where('status', 'Aktif')->get();
        return response()->json($dokter);
    }

    /**
     * @OA\Get(
     *     path="/api/getObat",
     *     tags={"Obat"},
     *     summary="Get all obat",
     *     description="Get all obat",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama_obat", type="string", example="Paracetamol"),
     *                 @OA\Property(property="nama_generik", type="string", example="Paracetamol"),
     *                 @OA\Property(property="kategori", type="string", example="Obat Analgesik"),
     *                 @OA\Property(property="bentuk_dosis", type="string", example="Tablet"),
     *                 @OA\Property(property="kekuatan", type="string", example="500mg"),
     *                 @OA\Property(property="harga", type="integer", example=10000),
     *                 @OA\Property(property="jumlah_stok", type="integer", example=10),
     *                 @OA\Property(property="tanggal_kedaluwarsa", type="string", example="2024-01-01"),
     *                 @OA\Property(property="produsen", type="string", example="PT. Generik"),
     *                 @OA\Property(property="instruksi_penggunaan", type="string", example="2x1 tablet"),
     *                 @OA\Property(property="efek_samping", type="string", example="Sakit kepala"),
     *                 @OA\Property(property="instruksi_penyimpanan", type="string", example="Simpan di tempat yang sejuk"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getObat()
    {
        $Obat = Obat::all();
        return response()->json($Obat);
    }

    /** 
     * @OA\Post(
     *     path="/api/send-data-pendaftaran",
     *     tags={"Pendaftaran"},
     *     summary="Buat pendaftaran",
     *     description="Buat pendaftaran",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="date", type="string", example="2024-01-01"),
     *             @OA\Property(property="dokter_id", type="integer", example=1),
     *             @OA\Property(property="pasien_id", type="string", example="1234567890"),
     *             @OA\Property(property="keluhan", type="string", example="Saya sakit"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pendaftaran berhasil disimpan.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Pendaftaran berhasil disimpan."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Data tidak valid",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="The date field is required."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=421,
     *         description="Pasien sudah terdaftar di poli ini untuk tanggal yang dipilih",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Pasien sudah terdaftar di poli ini untuk tanggal yang dipilih"),
     *         )
     *     )
     * )
     */
    public function sendDataPendaftaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'dokter_id' => 'required',
            'pasien_id' => 'required',
            'keluhan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $dateOnly = date('Y-m-d', strtotime($request->date));

        $dokter = DataPoli::where('dokter_id', $request->dokter_id)->first();
        // dd($dokter);


        $Pasien = Pasien::where('no_rm', $request->pasien_id)->first();
        $user_id = User::where('email', $Pasien->email)->first();

        if (Pendaftaran::where('pasien_id', $request->pasien_id)
            ->where('poli_id', $dokter->poli_id)
            ->where('tanggal_daftar', $dateOnly)
            ->exists()) {
            return response()->json(['error' => 'Pasien sudah terdaftar di poli ini untuk tanggal yang dipilih'], 421);
        }
        $pendaftaranCount = Pendaftaran::where('tanggal_daftar', $dateOnly)
        ->where('poli_id', $dokter->poli_id)
        ->count();
        $dokterKD = str_pad($pendaftaranCount + 1, 4, '0', STR_PAD_LEFT);
        
        $pendaftaran = Pendaftaran::create([
            'no_pendaftaran' => $this->generateUniqueCode($dokterKD, $dokter->poli_id, $dateOnly),
            'pasien_id' => $request->pasien_id,
            'poli_id' => $dokter->poli_id,
            'dokter_id' => $request->dokter_id,
            'tanggal_daftar' => Date($request->date),
            'keluhan' => $request->keluhan,
            'status' => 'Terdaftar'
    ]);

        $this->storeRiwayat($user_id->id, "pendaftaran", "INSERT", json_encode($pendaftaran));

        return response()->json(['success' => 'Pendaftaran berhasil disimpan.']);
    }

    /**
     * @OA\Get(
     *     path="/api/get-riwayat-pendaftaran/{no_rm}",
     *     tags={"Pendaftaran"},
     *     summary="Get riwayat pendaftaran",
     *     description="Get riwayat pendaftaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         description="No RM",
     *         in="path",
     *         name="no_rm",
     *         required=true,
     *         example="1234567890",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getRiwayatPendaftaran($no_rm)
    {
        $riwayat = Pendaftaran::where('pasien_id', $no_rm)
            ->leftjoin('mspoli', 'data_pendaftaran.poli_id', '=', 'mspoli.id')
            ->leftjoin('msdokter', 'data_pendaftaran.dokter_id', '=', 'msdokter.id')
            ->select('data_pendaftaran.*', 'mspoli.nama_poli', 'msdokter.nama')
            ->orderBy('data_pendaftaran.created_at', 'desc')->get();

        return response()->json($riwayat);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout",
     *     description="Logout the authenticated user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }

    
    /**
     * @OA\Post(
     *     path="/api/refresh-token",
     *     tags={"Auth"},
     *     summary="Refresh token",
     *     description="Refresh token",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="Authorization", type="string", example="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImYzMjIzYWVkMWQ2MmUxMzRlYmUyMjM4OGI4MjA2YTU3YTg4MjYyNzQyIn0.eyJhdWQiOiIxIiwianRpIjoiZjMyMjNhZWRtZDYyZTEzNGViZTIyMzg4YjgyMDZhNTdhODgyNjI3NDIiLCJpYXQiOjE2NDA2MjgwMjMsIm5iZiI6MTY0MDYyODAyMywiZXhwIjoxNjQwNzEyNDIzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.EuqG7Yv0sYJj6O6qJx4XZu0s4X0Jq2X0Jq2X0Jq2X0Jq2X0Jq2X0Jq2X0Jq2X0"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImYzMjIzYWVkMWQ2MmUxMzRlYmUyMjM4OGI4MjA2YTU3YTg4MjYyNzQyIn0.eyJhdWQiOiIxIiwianRpIjoiZjMyMjNhZWRtZDYyZTEzNGViZTIyMzg4YjgyMDZhNTdhODgyNjI3NDIiLCJpYXQiOjE2NDA2MjgwMjMsIm5iZiI6MTY0MDYyODAyMywiZXhwIjoxNjQwNzEyNDIzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.EuqG7Yv0sYJj6O6qJx4XZu0s4X0Jq2X0Jq2X0Jq2X0Jq2X0Jq2X0Jq2X0")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function refreshToken(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['error' => 'Token tidak ditemukan'], 401);
        }
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 401);
        }
        $newToken = $user->createToken('MyApp')->plainTextToken;
        return response()->json(['token' => $newToken]);
    }

    
    /**
     * @OA\Post(
     *     path="/api/send-keluhan",
     *     tags={"Keluhan"},
     *     summary="Send keluhan",
     *     description="Send keluhan",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="foto", type="string", format="binary"),
     *             @OA\Property(property="keluhan", type="string", example="Saya sakit kepala")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Keluhan berhasil disimpan.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function sendKeluhan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'nullable',
            'keluhan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $image = $request->file('foto');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move('images/keluhan', $imageName);

        $keluhan = Keluhan::create([
            'user_id' => $user->id,
            'foto' => $imageName,
            'keluhan' => $request->keluhan
        ]);
        return response()->json(['success' => 'Keluhan berhasil disimpan.']);
    }

/**
     * @OA\Post(
     *     path="/api/tambah-obat",
     *     tags={"Keranjang"},
     *     summary="Tambah obat ke keranjang",
     *     description="Tambah obat ke keranjang",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="pasienId", type="string", example="1234567890"),
     *             @OA\Property(property="obatId", type="string", example="1"),
     *             @OA\Property(property="jumlah", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product added to cart successfully!"),
     *             @OA\Property(property="id", type="string", example="1"),
    *     @OA\Property(property="pasienId", type="string", example="1234567890"),
    *     @OA\Property(property="obatId", type="string", example="1"),
    *     @OA\Property(property="jumlah", type="integer", example=3),
    *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-21T10:00:00Z"),
    *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-21T10:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function tambahObat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pasienId' => 'required',
            'obatId' => 'required',
            'jumlah' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $cart = Keranjang::updateOrCreate([
            'pasienId' => $request->pasienId,
            'obatId' => $request->obatId
        ], [
            'jumlah' => $request->jumlah
        ]);


        return response()->json(['message' => 'Product added to cart successfully!', 'cart' => $cart->toArray()]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-data-keranjang/{no_rm}",
     *     tags={"Keranjang"},
     *     summary="Get data keranjang by no rm",
     *     description="Get data keranjang by no rm",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         description="no rm",
     *         in="path",
     *         name="no_rm",
     *         required=true,
     *         example="1234567890",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getDataKeranjang($no_rm) {

        $keranjang = Keranjang::where('pasienId', $no_rm)
        ->leftJoin('msobat', 'data_keranjangobat.obatId', '=', 'msobat.medicine_id')
        ->get();
        return response()->json($keranjang);
    }

    public function generateUniqueCodeTRX($id) {
        $trxId = substr($id, -3);
        $date = date('Ymd', strtotime(Carbon::now()));
        

        $uniqueCode = "TRX" . $date . $trxId;

        return $uniqueCode;
    }

    /**
     * @OA\Post(
     *     path="/api/transaksi-obat",
     *     tags={"Transaksi"},
     *     summary="Transaksi Obat",
     *     description="Melakukan transaksi obat",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="pasienId", type="string", example="1234567890"),
     *             @OA\Property(property="total", type="number", example=100000),
     *             @OA\Property(
     *                 property="obat",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="obatId", type="string", example="1"),
     *                     @OA\Property(property="jumlah", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaksi obat berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaksi obat berhasil!"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function transaksiObat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pasienId' => 'required',
            'total' => 'required',
            'obat.*.obatId' => 'required',
            'obat.*.jumlah' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $trxCount = TransaksiObat::count();
        $trxKD = str_pad($trxCount + 1, 4, '0', STR_PAD_LEFT);

        $kodeTRX = $this->generateUniqueCodeTRX($trxKD);

        foreach ($request->obat as $obat) {
            try {
                DetailTransaksiObat::create([
                    'transaksi_id' => $kodeTRX,
                    'obatId' => $obat['obatId'],
                    'jumlah' => $obat['jumlah']
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        $transaksi = TransaksiObat::create([
            'transaksi_id' => $kodeTRX,
            'pasienId' => $request->pasienId,
            'total' => $request->total,
            'tanggal_transaksi' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 'Belum Bayar'
        ]);

        Keranjang::where('pasienId', $request->pasienId)->delete();

        return response()->json(['message' => 'Transaksi obat berhasil!']);
    }

}

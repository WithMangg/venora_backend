<?php

namespace App\Http\Controllers\layanan;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\FacialTreatment;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Skincare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    protected $form;
    protected $form_daftar;

    protected $title;

    public function __construct()
    {
        $this->title = 'Pembelian';

        $this->form = array(
            array(
                'label' => 'Nomor Rekam Medis',
                'field' => 'no_rm',
                'type' => 'text',
                'placeholder' => '',
                'width' => 3,
            ),
            array(
                'label' => 'NIK',
                'field' => 'nik',
                'width' => 3,
                'type' => 'number',
                'placeholder' => '',
                'required' => true,
                'disabled' => true,
            ),
            array(
                'label' => 'Nama Pasien',
                'field' => 'nama_pasien',
                'type' => 'text',
                'width' => 3,
                'placeholder' => '',
                'required' => true,
                'disabled' => true,


            ),
            array(
                'label' => 'Alamat',
                'field' => 'alamat',
                'type' => 'text',
                'width' => 3,
                'placeholder' => '',
                'required' => true,
                'disabled' => true,


            ),
            array(
                'label' => 'Email',
                'field' => 'email',
                'type' => 'email',
                'width' => 3,
                'placeholder' => '',
                'disabled' => true,

            ),
            array(
                'label' => 'NO. Hp (62xxx-xxxx-xxxx)',
                'field' => 'no_hp',
                'width' => 3,
                'type' => 'number',
                'placeholder' => '',
                'disabled' => true,

            ),
            array(
                'label' => 'Tanggal Lahir',
                'field' => 'tanggal_lahir',
                'type' => 'date',
                'placeholder' => '',
                'width' => 3,
                'required' => true,
                'disabled' => true,


            ),
            array(
                'label' => 'Jenis Kelamin (L/P)',
                'field' => 'jk',
                'type' => 'text',
                'width' => 3,
                'placeholder' => '',
                'required' => true,
                'disabled' => true,


            ),
        );

    }

    public function index(Request $request)
    {
        return view('pages.layanans.pembelians', ['form' => $this->form, 'form_daftar' => $this->form_daftar ,'title' => $this->title]);
    }

    public function show($id)
    {
        $user = Pasien::where('no_rm', $id)->first(); 

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rekam' => 'required|exists:mspasien,no_rm',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $skincare = json_encode($request->pemeriksaan_rekSkincare);
        $jumlahSkincare = json_encode($request->pemeriksaan_jumlahSkincare);

        $total = 0;
        if ($request->pemeriksaan_rekTreatment && $request->pemeriksaan_jumlahTreatment) {
            $total += FacialTreatment::where('facialTreatment_id', $request->pemeriksaan_rekTreatment)->value('facialTreatment_harga');
        }

        if ($request->pemeriksaan_rekSkincare && $request->pemeriksaan_jumlahSkincare) {
            $resepObat = json_decode($skincare);
            $jumlahObat = json_decode($jumlahSkincare);
            foreach ($resepObat as $key => $skincareItem) {
                $total += Skincare::where('skincare_id', $skincareItem)->value('skincare_harga') * $jumlahObat[$key];
            }
        }

        if ($request->pemeriksaan_rekSkincare || $request->pemeriksaan_rekTreatment) {
            $pembelian = new Pembayaran();
            $pembelian->no_pembayaran = $this->generateUniqueCodePembayaran();
            $pembelian->pasien_id = Pasien::where('no_rm', $request->input('no_rekam'))->first()->id;
            $pembelian->treatment = $request->pemeriksaan_rekTreatment ?? '';
            $pembelian->skincare = $skincare;
            $pembelian->jumlahSkincare = $jumlahSkincare;
            $pembelian->total = $total;
            $pembelian->tanggal_pemeriksaan = now();
            $pembelian->status = 'Belum Bayar';
            $pembelian->save();

            // update stok skincare
            if ($request->pemeriksaan_rekSkincare && $request->pemeriksaan_jumlahSkincare) {
                $resepObat = json_decode($skincare);
                $jumlahObat = json_decode($jumlahSkincare);
                foreach ($resepObat as $key => $skincareItem) {
                    $skincare = Skincare::where('skincare_id', $skincareItem)->first();
                    $skincare->skincare_stok = $skincare->skincare_stok - $jumlahObat[$key];
                    $skincare->save();
                }
            }

            return response()->json(['message' => 'Data berhasil disimpan'], 201);

        } else {
            return response()->json(['message' => 'Data Gagal disimpan'], 404);
        }


    }

    public function generateUniqueCodePembayaran() {
        $date = date('ym');
        $pembayaranCount = Pembayaran::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        $no_pem = str_pad($pembayaranCount + 1, 4, '0', STR_PAD_LEFT);
        $pelangganPart = substr($no_pem, -2);


        $uniqueCode = "TRX" . $date . $pelangganPart;

        return $uniqueCode;
    }
}

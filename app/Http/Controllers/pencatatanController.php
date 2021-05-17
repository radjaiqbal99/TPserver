<?php

namespace App\Http\Controllers;

use App\Models\pencatatan;
use App\Models\tglTransaksi;
use App\Models\hargaPasir;
use App\Models\daftarKasir;
use App\Models\daftarPegawai;
use App\Models\dompetKasir;
use App\Models\dompetPegawai;
use App\Models\upahKasir;
use App\Models\upahPegawai;
use App\Models\bonTruk;
use App\Models\transaksiBonTruk;
use App\Models\transaksiDompetKasir;
use App\Models\transaksiDompetPegawai;


use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use LengthException;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class pencatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function resourcesForm()
    {
        $hargaPasir = hargaPasir::get();
        $daftarKasir = daftarKasir::get();
        $daftarPegawai = daftarPegawai::get();

        $response = [
            'hargaPasir' => $hargaPasir,
            'daftarKasir' => $daftarKasir,
            'daftarPegawai' => $daftarPegawai,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function index()
    {
        $resultTanggal = tglTransaksi::get();
        $resultPencatatan = pencatatan::get();
        $resultTanggalLenght = tglTransaksi::count();
        $resultPencatatanLenght = pencatatan::count();

        for ($i = 0; $i < $resultTanggalLenght; $i++) {
            $sum = 0;
            $resultMatchDate = pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->get();
            for ($z = 0; $z < count($resultMatchDate); $z++) {
                $sum = $sum + $resultMatchDate[$z]['Harga'];
            };
            $count = strval(count($resultMatchDate));
            $response[$i] = [
                'tgl' => $resultTanggal[$i]["tgl"],
                'jml_transaksi' => $count,
                'total_harga' => strval($sum),
                'transaksi' => $resultMatchDate,
            ];
        };

        $finalresponse = [
            'data' => $response,

        ];
        return response()->json($finalresponse, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tglTransaksi = tglTransaksi::where("tgl", $request->transactionDate)->first();
        if (!$tglTransaksi) {
            tglTransaksi::create([
                'tgl' => $request->transactionDate
            ]);
        }
        if ($request->jenisTransaksi === "Pembelian pasir") {
            $upahKasir = upahKasir::where("satuan", $request->satuan)->get();
            $upahPegawai = upahPegawai::where("satuan", $request->satuan)->get();
            $jumlahPegawai = count($request->pegawai);
            $listPegawai = $request->pegawai;
            $upahKasirtotal = intval($upahKasir[0]["upah"]) * intval($request->qty);
            $upahPegawaitotal = intval($upahPegawai[0]["upah"]) * intval($request->qty);
            $upahPegawaifinal = floatval($upahPegawaitotal) / floatval($jumlahPegawai);
            $pendapatanBersih = (intval($request->harga) - $upahKasirtotal) - $upahPegawaitotal;
            
            pencatatan::create([
                'no_transaksi' => $request->transactionNumber,
                'tgl_transaksi' => $request->transactionDate,
                'jenis_transaksi' => $request->jenisTransaksi,
                'satuan' => $request->satuan,
                'qty' => $request->qty,
                'pekerja' => join(",", $request->pegawai),
                'kasir' => $request->kasir,
                'harga' => $request->harga,
                'keterangan' => $request->keterangan,
                'pendapatanBersih' => $pendapatanBersih
            ]);
            for ($i = 0; $i < $jumlahPegawai; $i++) {
                $pendapatanPegawai = 0;
                $matchPegawai = dompetPegawai::where("name", $listPegawai[$i])->first();
                $pendapatanPegawai = floatval($matchPegawai->saldo) + $upahPegawaifinal;
                $matchPegawai->update([
                    'saldo' => $pendapatanPegawai
                ]);
                transaksiDompetPegawai::create([
                    "id_dompet" => $matchPegawai->id_dompet,
                    "nomor_transaksi" => $request->transactionNumber,
                    "transaksi" => "Deposit",
                    "kasir" => $request->kasir,
                    "tgl_transaksi" => $request->transactionDate,
                    "nominal"=>$upahPegawaifinal
                ]);
            };
            $pendapatanKasir = 0;
            $matchKasir = dompetKasir::where("name", $request->kasir)->first();
            $pendapatanKasir = floatval($matchKasir->saldo) + $upahKasirtotal;
            $matchKasir->update([
                'saldo' => $pendapatanKasir
            ]);
            transaksiDompetKasir::create([
                "id_dompet" => $matchKasir->id_dompet,
                "nomor_transaksi" => $request->transactionNumber,
                "transaksi" => "Deposit",
                "kasir" => $request->kasir,
                "tgl_transaksi" => $request->transactionDate,
                "nominal" => $upahKasirtotal
            ]);
        } elseif ($request->jenisTransaksi === "Pengeluaran tambang"){
            $pendapatanBersih = 0;
            $pendapatanBersih = floatval( $pendapatanBersih ) - floatval( $request->harga );
            pencatatan::create([
                'no_transaksi' => $request->transactionNumber,
                'tgl_transaksi' => $request->transactionDate,
                'jenis_transaksi' => $request->jenisTransaksi,
                'kasir' => $request->kasir,
                'keterangan' => $request->keterangan,
                'harga' => $request->harga,
                'pendapatanBersih' => $pendapatanBersih
            ]);
        } elseif ($request->jenisTransaksi === "Penarikan deposit pegawai"){
            $pendapatanBersih = 0;
            $jumlahPegawai = count($request->pegawai);
            $listPegawai = $request->pegawai;
            pencatatan::create([
                'no_transaksi' => $request->transactionNumber,
                'tgl_transaksi' => $request->transactionDate,
                'jenis_transaksi' => $request->jenisTransaksi,
                'pekerja' => join(",", $request->pegawai),
                'kasir' => $request->kasir,
                'harga' => $request->harga,
                'pendapatanBersih' => $pendapatanBersih
            ]);
            for ($i = 0; $i < $jumlahPegawai; $i++) {
                $pendapatanPegawai = 0;
                $matchPegawai = dompetPegawai::where("name", $listPegawai[$i])->first();
                $pendapatanPegawai = floatval($matchPegawai->saldo) - floatval($request->harga);
                $matchPegawai->update([
                    'saldo' => $pendapatanPegawai
                ]);
                transaksiDompetPegawai::create([
                    "id_dompet" => $matchPegawai->id_dompet,
                    "nomor_transaksi" => $request->transactionNumber,
                    "transaksi" => "Credit",
                    "kasir" => $request->kasir,
                    "tgl_transaksi" => $request->transactionDate,
                    "nominal" => $request->harga
                ]);
            };
        } elseif ($request->jenisTransaksi === "Penarikan deposit kasir") {
            $pendapatanBersih = 0;
            pencatatan::create([
                'no_transaksi' => $request->transactionNumber,
                'tgl_transaksi' => $request->transactionDate,
                'jenis_transaksi' => $request->jenisTransaksi,
                'kasir' => $request->kasir,
                'harga' => $request->harga,
                'pendapatanBersih' => $pendapatanBersih
            ]);
            $pendapatanKasir = 0;
            $matchKasir = dompetKasir::where("name", $request->kasir)->first();
            $pendapatanKasir = floatval($matchKasir->saldo) - floatval($request->harga);
            $matchKasir->update([
                'saldo' => $pendapatanKasir
            ]);
            transaksiDompetKasir::create([
                "id_dompet" => $matchKasir->id_dompet,
                "nomor_transaksi" => $request->transactionNumber,
                "transaksi" => "credit",
                "kasir" => $request->kasir,
                "tgl_transaksi" => $request->transactionDate,
                "nominal" => $request->harga
            ]);
        } elseif ($request->jenisTransaksi === "Bon truk") {
            $upahKasir = upahKasir::where("satuan", $request->satuan)->get();
            $upahPegawai = upahPegawai::where("satuan", $request->satuan)->get();
            $jumlahPegawai = count($request->pegawai);
            $listPegawai = $request->pegawai;
            $upahKasirtotal = intval($upahKasir[0]["upah"]) * intval($request->qty);
            $upahPegawaitotal = intval($upahPegawai[0]["upah"]) * intval($request->qty);
            $upahPegawaifinal = floatval($upahPegawaitotal) / floatval($jumlahPegawai);
            $pendapatanBersih = (intval($request->harga) - $upahKasirtotal) - $upahPegawaitotal;
            $tglTransaksi = tglTransaksi::where("tgl", $request->transactionDate)->first();
            if (!$tglTransaksi) {
                tglTransaksi::create([
                    'tgl' => $request->transactionDate
                ]);
            }
            pencatatan::create([
                'no_transaksi' => $request->transactionNumber,
                'tgl_transaksi' => $request->transactionDate,
                'jenis_transaksi' => $request->jenisTransaksi,
                'satuan' => $request->satuan,
                'qty' => $request->qty,
                'pekerja' => join(",", $request->pegawai),
                'kasir' => $request->kasir,
                'harga' => $request->harga,
                'keterangan' => $request->keterangan,
                'pendapatanBersih' => $pendapatanBersih
            ]);
            for ($i = 0; $i < $jumlahPegawai; $i++) {
                $pendapatanPegawai = 0;
                $matchPegawai = dompetPegawai::where("name", $listPegawai[$i])->first();
                $pendapatanPegawai = floatval($matchPegawai->saldo) + $upahPegawaifinal;
                $matchPegawai->update([
                    'saldo' => $pendapatanPegawai
                ]);
                transaksiDompetPegawai::create([
                    "id_dompet" => $matchPegawai->id_dompet,
                    "nomor_transaksi" => $request->transactionNumber,
                    "transaksi" => "Deposit",
                    "kasir" => $request->kasir,
                    "tgl_transaksi" => $request->transactionDate,
                    "nominal" => $upahPegawaifinal
                ]);
            };
            $pendapatanKasir = 0;
            $matchKasir = dompetKasir::where("name", $request->kasir)->first();
            $pendapatanKasir = floatval($matchKasir->saldo) + $upahKasirtotal;
            $matchKasir->update([
                'saldo' => $pendapatanKasir
            ]);
            transaksiDompetKasir::create([
                "id_dompet" => $matchKasir->id_dompet,
                "nomor_transaksi" => $request->transactionNumber,
                "transaksi" => "Deposit",
                "kasir" => $request->kasir,
                "tgl_transaksi" => $request->transactionDate,
                "nominal" => $upahKasirtotal
            ]);
            $bonTruk = bonTruk::where("name", $request->name)->first();
            if (!$bonTruk) {
                bonTruk::create([
                    'id_bon' => $request->transactionNumber,
                    'name' => $request->name
                ]);
            }
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

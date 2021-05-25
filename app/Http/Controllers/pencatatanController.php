<?php

namespace App\Http\Controllers;

use App\Models\pencatatan;
use App\Models\tglTransaksi;
use App\Models\hargaPasir;
use App\Models\DaftarKasir;
use App\Models\DaftarPegawai;
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
        $daftarKasir = DaftarKasir::get();
        $daftarPegawai = DaftarPegawai::get();
        $bonTrukNama = bonTruk::get();

        $response = [
            'hargaPasir' => $hargaPasir,
            'daftarKasir' => $daftarKasir,
            'daftarPegawai' => $daftarPegawai,
            'bonTrukNama' => $bonTrukNama,
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
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            $sum4 = 0;
            $sum5 = 0;
            $sum6 = 0;
            $sum7 = 0;
            $sum8 = 0;
            $resultMatchDate = pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->get();
            $resultMatchTransaksiPenjualanPasir = pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->where('jenis_transaksi','Pembelian pasir')->orWhere('jenis_transaksi','Bon truk')->get();
            $resultMatchTransaksiBonTruk = pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->where('jenis_transaksi','Bon truk')->get();
            $resultMatchPendapatanBersih = pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->where('jenis_transaksi','Pembelian pasir')->orWhere('jenis_transaksi', 'Pembayaran Bon Truk')->get();
            $resultMatchPengeluaranTambang= pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->where('jenis_transaksi','Pengeluaran tambang')->get();
            $resultMatchDepositPegawai= pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->where('jenis_transaksi','Penarikan deposit pegawai')->get();
            $resultMatchdepositKasir= pencatatan::where('tgl_transaksi', $resultTanggal[$i]["tgl"])->where('jenis_transaksi','Penarikan deposit Kasir')->get();
            for ($z = 0; $z < count($resultMatchTransaksiPenjualanPasir); $z++) {
                // $sum1 += $resultMatchTransaksiPenjualanPasir[$z]['Harga'];
                $sum2 += $resultMatchTransaksiPenjualanPasir[$z]['upahPegawai'];
                $sum3 += $resultMatchTransaksiPenjualanPasir[$z]['upahKasir'];
            };
            for ($z = 0; $z < count($resultMatchTransaksiBonTruk); $z++) {
                $sum4 += $resultMatchTransaksiBonTruk[$z]['Harga'];
            };
            for ($z = 0; $z < count($resultMatchPengeluaranTambang); $z++) {
                $sum6 += $resultMatchPengeluaranTambang[$z]['Harga'];
            };
            for ($z = 0; $z < count($resultMatchPendapatanBersih); $z++) {
                $sum5 += $resultMatchPendapatanBersih[$z]['pendapatanBersih'];
                $sum1 += $resultMatchPendapatanBersih[$z]['Harga'];
            };
            for ($z = 0; $z < count($resultMatchDepositPegawai); $z++) {
                $sum7 += $resultMatchDepositPegawai[$z]['Harga'];
            };
            for ($z = 0; $z < count($resultMatchdepositKasir); $z++) {
                $sum8 += $resultMatchdepositKasir[$z]['Harga'];
            };
            $pendapatanBersih=$sum5-$sum6;
            $count1 = strval(count($resultMatchDate));
            $count2 = strval(count($resultMatchTransaksiPenjualanPasir));
            $count3 = strval(count($resultMatchTransaksiBonTruk));
            $response[$i] = [
                'id' => $i+1,
                'jumlahTransaksi' => $count1,
                'jumlahPenjualanPasir' => $count2,
                'jumlahBonTruk'=>$count3,
                'penjualanPasir' => $sum1,
                'pendapatanBersih' => $pendapatanBersih,
                'pengeluaranTambang' => $sum6,
                'upahPegawai' => $sum2,
                'upahKasir' => $sum3,
                'bonTruk' => $sum4,
                'depositPegawai' => $sum7,
                'depositKasir' => $sum8,
                'tgl' => $resultTanggal[$i]["tgl"],
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
                'upahPegawai' => $upahPegawaitotal,
                'upahKasir' => $upahKasirtotal,
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
        } elseif ($request->jenisTransaksi === "Pengeluaran tambang") {
            // $pendapatanBersih = 0;
            $pendapatanBersih = 0 - floatval($request->harga);
            pencatatan::create([
                'no_transaksi' => $request->transactionNumber,
                'tgl_transaksi' => $request->transactionDate,
                'jenis_transaksi' => $request->jenisTransaksi,
                'kasir' => $request->kasir,
                'keterangan' => $request->keterangan,
                'harga' => $request->harga,
                'upahPegawai' => 0,
                'upahKasir' => 0,
                'pendapatanBersih' => $pendapatanBersih
            ]);
        } elseif ($request->jenisTransaksi === "Penarikan deposit pegawai") {
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
                'upahPegawai' => 0,
                'upahKasir' => 0,
                'pendapatanBersih' => $pendapatanBersih,
                'keterangan'=>'Penarikan Deposit atas nama'.' '. join(",", $request->pegawai)
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
                'upahPegawai' => 0,
                'upahKasir' => 0,
                'pendapatanBersih' => $pendapatanBersih,
                'keterangan' => 'Penarikan deposit atas nama'.' '.$request->kasir
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

            if ($request->keterangan === "Bon") {
                $upahKasir = upahKasir::where("satuan", $request->satuan)->get();
                $upahPegawai = upahPegawai::where("satuan", $request->satuan)->get();
                $jumlahPegawai = count($request->pegawai);
                $listPegawai = $request->pegawai;
                $upahKasirtotal = intval($upahKasir[0]["upah"]) * intval($request->qty);
                $upahPegawaitotal = intval($upahPegawai[0]["upah"]) * intval($request->qty);
                $upahPegawaifinal = floatval($upahPegawaitotal) / floatval($jumlahPegawai);
                // $Bon = 0 -($upahPegawaifinal+$upahKasirtotal);
                $Bon = 0;
                // $pendapatanBersih = (intval($request->harga) - $upahKasirtotal) - $upahPegawaitotal;
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
                    'upahPegawai' => $upahPegawaitotal,
                    'upahKasir' => $upahKasirtotal,
                    'keterangan' => "Bon atas nama"." ".$request->name,
                    'pendapatanBersih' => $Bon
                ]);
                // if ($request->keterangan === "Bon") {
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
                $jumlahBonTruk = 0;
                $idBon = "";
                $bonTruk = bonTruk::where("name", $request->name)->first();
                // $jumlahBonTruk =  0;
                if (!$bonTruk) {
                    $idBon = uniqid();
                    bonTruk::create([
                        'id_bon' => $idBon,
                        'name' => $request->name,
                        'saldo' => $request->harga
                    ]);
                } else {
                    $idBon = $bonTruk->id_bon;
                    $jumlahBonTruk =  intval($bonTruk->saldo) + intval($request->harga);
                    $bonTruk->update([
                        'saldo' => $jumlahBonTruk
                    ]);
                }
                transaksiBonTruk::create([
                    'id_bon' => $idBon,
                    'no_transaksi' => $request->transactionNumber,
                    'tgl_transaksi' => $request->transactionDate,
                    'kasir' => $request->kasir,
                    'jenis_transaksi' => 'Bon',
                    'satuan' => $request->satuan,
                    'qty' => $request->qty,
                    'Harga' => $request->harga
                ]);
            } else {
                $Bon = intval($request->harga);
                // $pendapatanBersih = (intval($request->harga) - $upahKasirtotal) - $upahPegawaitotal;
                $tglTransaksi = tglTransaksi::where("tgl", $request->transactionDate)->first();
                if (!$tglTransaksi) {
                    tglTransaksi::create([
                        'tgl' => $request->transactionDate
                    ]);
                }
                pencatatan::create([
                    'no_transaksi' => $request->transactionNumber,
                    'tgl_transaksi' => $request->transactionDate,
                    'jenis_transaksi' => "Pembayaran Bon Truk",
                    // 'satuan' => $request->satuan,
                    // 'qty' => $request->qty,
                    // 'pekerja' => join(",", $request->pegawai),
                    'kasir' => $request->kasir,
                    'harga' => $Bon,
                    'upahPegawai' => 0,
                    'upahKasir' => 0,
                    'keterangan' => "Pembayaran bon atas nama"." ".$request->name,
                    'pendapatanBersih' => $Bon
                ]);
                $jumlahBonTruk = 0;
                $idBon = "";
                $bonTruk = bonTruk::where("name", $request->name)->first();
                // $jumlahBonTruk =  0;
                $idBon = $bonTruk->id_bon;
                $jumlahBonTruk =  intval($bonTruk->saldo) - intval($request->harga);
                $bonTruk->update([
                    'saldo' => $jumlahBonTruk
                ]);
                transaksiBonTruk::create([
                    'id_bon' => $idBon,
                    'no_transaksi' => $request->transactionNumber,
                    'tgl_transaksi' => $request->transactionDate,
                    'kasir' => $request->kasir,
                    'jenis_transaksi' => 'Pembayaran',
                    // 'satuan' => $request->satuan,
                    // 'qty' => $request->qty,
                    'Harga' => $request->harga
                ]);
            }
            // } else {
            //     $jumlahBonTruk = 0;
            //     $bonTruk = bonTruk::where("name", $request->name)->first();
            //     $jumlahBonTruk =  floatval($bonTruk->saldo) - $request->harga;
            //     $bonTruk->update([
            //         'saldo' => $jumlahBonTruk
            //     ]);
            //     transaksiBonTruk::create([
            //         'no_transaksi' => $request->transactionNumber,
            //         'tgl_transaksi' => $request->transactionDate,
            //         'kasir' => $request->kasir,
            //         'jenis_transaksi' => 'Pembayaran',
            //         'satuan' => $request->satuan,
            //         'qty' => $request->qty,
            //         'Harga' => $request->harga
            //     ]);
            // };
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

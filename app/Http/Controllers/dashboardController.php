<?php

namespace App\Http\Controllers;

use App\Models\pencatatan;
use App\Models\dompetPegawai;
use App\Models\dompetKasir;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class dashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = date("Y-m");
        $maxDate= date("t");
        $pendaptanBersih=0;
        for ($i = 1; $i <= intval($maxDate); $i++) {
            // if (strlen($i)<10){
            //     $find = pencatatan::where("tgl_transaksi", "$date" . "-0" . "$i")->where("jenis_transaksi", "Pembelian pasir")->get();
            // };
            $find = pencatatan::where("tgl_transaksi", "$date" . "-" . "$i")->where("jenis_transaksi", "Pembelian pasir")->orWhere("jenis_transaksi",'Bon truk')->get();
            $date1[$i - 1] = "$date" . "-" . "$i";
            $jumlah[$i - 1] = count($find);
        }
        //PPENDAPATAN BERSIH
        $pendapatan= pencatatan::where("jenis_transaksi","Pembelian pasir")->orWhere("jenis_transaksi",'Pembayaran Bon Truk')->get();
        for($i=0;$i<count($pendapatan);$i++){
            $pendaptanBersih+=$pendapatan[$i]['pendapatanBersih'];
        }
        //JUMLAH PENJUALAN
        $jumlahpenjualan= pencatatan::where("jenis_transaksi", "Pembelian pasir")->orWhere("jenis_transaksi", 'Bon truk')->get();
        //JUMLAH TRANSAKSI
        $jumlahtransaksi= pencatatan::get();

        //BON
        $bon1=0;
        $bon= pencatatan::where("jenis_transaksi","Bon truk")->get();
        for ($i = 0; $i < count($bon); $i++) {
            $bon1 += $bon[$i]['Harga'];
        }
        $bon2=0;
        $pembayaranBon= pencatatan:: where("jenis_transaksi", 'Pembayaran Bon Truk')->get();
        for ($i = 0; $i < count($pembayaranBon); $i++) {
            $bon2 += $pembayaranBon[$i]['Harga'];
        }
        $bon3=$bon1-$bon2;
        //UPAH PEGAWAI
        $upahPegawaiuang=0;
        $upahPegawai=dompetPegawai::get();
        for ($i = 0; $i < count($upahPegawai); $i++) {
            $upahPegawaiuang += $upahPegawai[$i]['saldo'];
        }
        //UPAH KASIR
        $upahKasiruang=0;
        $upahKasir=dompetKasir::get();
        for ($i = 0; $i < count($upahKasir); $i++) {
            $upahKasiruang += $upahKasir[$i]['saldo'];
        }
        //PENGELUARAN TAMBANG
        $pengeluaran = 0;
        $pengeluaranTambang = pencatatan::where("jenis_transaksi", 'Pengeluaran tambang')->get();
        for ($i = 0; $i < count($pengeluaranTambang); $i++) {
            $pengeluaran += $pengeluaranTambang[$i]['Harga'];
        }

        $response = [
            "jumlahpenjualan" => count($jumlahpenjualan),
            "jumlahtransaksi" => count($jumlahtransaksi),
            "pendapatanBersih" => $pendaptanBersih,
            "jumlahBon" => $bon3,
            "upahpegawai"=>$upahPegawaiuang,
            "upahkasir"=>$upahKasiruang,
            "pengeluarantambang"=>$pengeluaran,
            "jumlahpegawai"=>count($upahPegawai),
            "jumlahkasir"=>count($upahKasir),
            "date" => $date1,
            "jumlah" => $jumlah,
        ];
        return response()->json($response, Response::HTTP_OK);
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
        //
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

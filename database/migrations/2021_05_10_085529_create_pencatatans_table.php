<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePencatatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pencatatans', function (Blueprint $table) {
            $table->id();
            $table->string("no_transaksi");
            $table->date("tgl_transaksi");
            $table->enum("jenis_transaksi",['Pembelian pasir', 'Pengeluaran tambang', 'Penarikan deposit pegawai', 'Penarikan deposit Kasir', 'Bon truk', 'Pembayaran Bon Truk', 'Bon Pegawai']);
            $table->string("satuan")->nullable();
            $table->integer("qty")->nullable();
            $table->string("pekerja")->nullable();
            $table->string("kasir")->nullable();
            $table->bigInteger("Harga");
            $table->bigInteger("upahPegawai")->nullable();
            $table->bigInteger("upahKasir")->nullable();
            $table->bigInteger("pendapatanBersih");
            $table->string("keterangan")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pencatatans');
    }
}

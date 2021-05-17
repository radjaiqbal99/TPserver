<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiDompetPegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_dompet_pegawais', function (Blueprint $table) {
            $table->id();
            $table->string("id_dompet");
            $table->string('nomor_transaksi');
            $table->enum('transaksi',["Deposit","Credit"]);
            $table->string('kasir');
            $table->date('tgl_transaksi');
            $table->bigInteger('nominal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_dompet_pegawais');
    }
}

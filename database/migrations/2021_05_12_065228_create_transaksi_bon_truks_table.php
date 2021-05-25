<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiBonTruksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_bon_truks', function (Blueprint $table) {
            $table->id();
            $table->string("id_bon");
            $table->string("no_transaksi")->nullable();
            $table->date("tgl_transaksi")->nullable();
            $table->string("kasir")->nullable();
            $table->enum("jenis_transaksi", ['Bon','Pembayaran'])->nullable();
            $table->string("satuan")->nullable();
            $table->integer("qty")->nullable();
            $table->bigInteger("Harga")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_bon_truks');
    }
}

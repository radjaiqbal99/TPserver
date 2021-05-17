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
            $table->string("no_transaksi");
            $table->date("tgl_transaksi");
            $table->string("kasir");
            $table->enum("jenis_transaksi", ["Bon,Pembayaran"]);
            $table->string("satuan");
            $table->integer("qty");
            $table->bigInteger("Harga");
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

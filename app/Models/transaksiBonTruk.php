<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksiBonTruk extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id_bon','no_transaksi', 'tgl_transaksi', 'kasir', 'jenis_transaksi','satuan','qty','Harga'
    ];
}

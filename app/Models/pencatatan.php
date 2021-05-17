<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pencatatan extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'no_transaksi', 'tgl_transaksi', 'jenis_transaksi', 'satuan', 'qty', 'pekerja','kasir','harga', 'keterangan','pendapatanBersih'
    ];
}

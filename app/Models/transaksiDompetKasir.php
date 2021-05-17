<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksiDompetKasir extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id_dompet', 'nomor_transaksi', 'transaksi', 'kasir', 'tgl_transaksi', 'nominal'
    ];
}

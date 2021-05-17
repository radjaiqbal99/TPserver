<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dompetPegawai extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id_dompet','name', 'saldo'
    ];
}

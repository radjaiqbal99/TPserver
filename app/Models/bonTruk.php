<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bonTruk extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id_bon', 'name','saldo'
    ];
}

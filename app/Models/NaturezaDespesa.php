<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class NaturezaDespesa extends Model
{
    use HasFactory;
    

    protected $table = 'naturezas_despesas';

    protected $fillable = [
        'nome',
        'codigo',
        'tipo'
    ];
}

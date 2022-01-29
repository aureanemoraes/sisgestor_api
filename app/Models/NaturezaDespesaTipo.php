<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class NaturezaDespesaTipo extends Model
{
    use HasFactory;
    

    protected $table = 'naturezas_despesas_tipos';

    protected $fillable = [
        'nome',
        'codigo',
        'tipo'
    ];
}

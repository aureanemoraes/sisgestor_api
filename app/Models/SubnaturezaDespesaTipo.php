<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SubnaturezaDespesaTipo extends Model
{
    use HasFactory;
    

    protected $table = 'subnaturezas_despesas_tipos';

    protected $fillable = [
        'nome',
        'codigo',
        'natureza_despesa_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NaturezaDespesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'naturezas_despesas';

    protected $fillable = [
        'nome',
        'codigo',
        'tipo'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubnaturezaDespesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subnaturezas_despesas';

    protected $fillable = [
        'nome',
        'codigo',
        'natureza_despesa_id'
    ];
}

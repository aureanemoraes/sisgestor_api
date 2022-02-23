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
        'tipo',
        'fav'
    ];

    protected $with = [
        'subnatureza_despesas:id,nome'
    ];

    public function subnatureza_despesas()
    {
        return $this->hasMany(SubnaturezaDespesa::class);
    }
}

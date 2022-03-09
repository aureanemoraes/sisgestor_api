<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'valor_inicial',
        'valor_final',
        'valor_atingido',
        'objetivo_id',
        'unidade_gestora_id'
    ];

    public function objetivo()
    {
        return $this->belongsTo(Objetivo::class);
    } 

    public function unidade_gestora()
    {
        return $this->belongsTo(UnidadeGestora::class);
    } 
}

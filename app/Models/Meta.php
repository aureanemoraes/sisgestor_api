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

    protected $appends = ['porcentagem_atual'];

    public function getPorcentagemAtualAttribute()
    {
        if(isset($this->attributes['valor_atingido'])) {
            $porcentagem_atual = ($this->attributes['valor_atingido'] * 100)/$this->attributes['valor_final'];
            return $porcentagem_atual;
        } else 
            return null;
    }

    public function objetivo()
    {
        return $this->belongsTo(Objetivo::class);
    } 

    public function unidade_gestora()
    {
        return $this->belongsTo(UnidadeGestora::class);
    } 
}

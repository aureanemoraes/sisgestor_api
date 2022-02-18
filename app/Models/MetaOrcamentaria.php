<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaOrcamentaria extends Model
{
    use HasFactory;

    protected $table = 'metas_orcamentarias';

    protected $fillable = [
        'nome',
        'qtd_estimada',
        'qtd_alcancada',
        'natureza_despesa_id',
        'instituicao_id'
    ];

    public function getQtdEstimadaAttribute($value)
    {
        if(!isset($value)) {
            if(isset($this->natureza_despesa_id)) {
                $qtd_estimada = Despesa::where('natureza_despesa_id', $this->natureza_despesa_id)->sum('valor_total');
                return $qtd_estimada;
            }
        } else {
            return $value;
        }
    }

    public function natureza_despesa()
    {
        return $this->belongsTo(NaturezaDespesa::class);
    } 

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    } 
}

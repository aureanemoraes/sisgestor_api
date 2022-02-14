<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'qtd',
        'qtd_pessoas',
        'tipo',
        'fonte_acao_id',
        'centro_custo_id',
        'natureza_despesa_id',
        'subnatureza_despesa_id',
        'unidade_administrativa_id'
    ];

    protected $with = [
        'fonte_acao',
        'centro_custo',
        'natureza_despesa',
        'subnatureza_despesa',
        'unidade_administrativa'
    ];

    protected $appends = [
        'valor_total'
    ];


    public function getValorTotalAttribute()
    {
        if(isset($this->attributes['valor'])) {
            $valor_total = $this->attributes['valor'];
            if(isset($this->attributes['qtd'])) {
                $valor_total = $valor_total  * $this->attributes['qtd'];
                if(isset($this->attributes['qtd_pessoas'])) {
                    $valor_total = $valor_total * $this->attributes['qtd_pessoas'];
                }
            }
            return $valor_total;
        } else {
            return null;
        }
    }

    public function fonte_acao()
    {
        return $this->belongsTo(FonteAcao::class);
    } 

    public function centro_custo()
    {
        return $this->belongsTo(CentroCusto::class);
    } 

    public function natureza_despesa()
    {
        return $this->belongsTo(NaturezaDespesa::class);
    } 

    public function subnatureza_despesa()
    {
        return $this->belongsTo(SubnaturezaDespesa::class);
    } 

    public function unidade_administrativa()
    {
        return $this->belongsTo(UnidadeAdministrativa::class);
    } 
}

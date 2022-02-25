<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditoPlanejado extends Model
{
    use HasFactory;

    protected $table = 'creditos_planejados';

    protected $fillable = [
        'descricao',
        'valor_disponivel',
        'despesa_id',
        'unidade_administrativa_id'
    ];

    protected $appends = [
        'valor_solicitado' // valor planejado na despesa
    ];

    protected $with = [
        'despesa:id,nome', 
        'unidade_administrativa:id,nome'
    ];

    public function getValorSolicitadoAttribute($value) {
        if(isset($this->despesa_id))
            return $this->despesa->valor_total;
        else
            return $value;
    }

    public function despesa()
    {
        return $this->belongsTo(Despesa::class);
    } 

    public function unidade_administrativa()
    {
        return $this->belongsTo(UnidadeAdministrativa::class);
    } 

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Exercicio extends Model
{
    use HasFactory;
    

    protected $table = 'exercicios';

    protected $fillable = [
        'nome',
        'data_inicio',
        'data_fim',
        'data_inicio_loa',
        'data_fim_loa',
        'aprovado',
        'instituicao_id'
    ];

    protected $casts = [
        'data_inicio' => 'datetime:Y-m-d',
        'data_fim' => 'datetime:Y-m-d',
        'data_inicio_loa' => 'datetime:Y-m-d',
        'data_fim_loa' => 'datetime:Y-m-d',
    ];

    protected $with = [
        'instituicao'
    ];

    protected $appends = [
        'total_matriz'
    ];

    public function getTotalMatrizAttribute() {
        return isset($this->attributes['total_matriz']) ? $this->attributes['total_matriz'] : 0;
    }

    public static function getOpcoes() {
        $opcoes = Exercicio::select('id', 'nome')->get()->toArray();

        return $opcoes;
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
}

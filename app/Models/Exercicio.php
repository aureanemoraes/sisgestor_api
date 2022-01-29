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
        'aprovado',
        'instituicao_id'
    ];

    protected $casts = [
        'data_inicio' => 'datetime:Y-m-d',
        'data_fim' => 'datetime:Y-m-d'
    ];

    public static function getOpcoes() {
        $opcoes = Exercicio::select('id', 'nome')->get()->toArray();

        return $opcoes;
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
}

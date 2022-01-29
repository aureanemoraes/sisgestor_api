<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pessoa extends Model
{
    use HasFactory;
    

    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf',
        'logradouro',
        'numero',
        'bairro',
        'complemento',
        'filiacao_1',
        'filiacao_2',
        'telefone',
        'email',
        'instituicao_id'
    ];

    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = strtoupper($value);
    }

    // public function usuarios()
    // {
    //     return $this->hasMany(Usuario::class);
    // }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
}

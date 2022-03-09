<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimensao extends Model
{
    use HasFactory;

    protected $table = 'dimensoes';

    protected $fillable = [
        'nome',
        'descricao',
        'instituicao_id',
        'exercicio_id'
    ];

    public function objetivos()
    {
        return $this->hasMany(Objetivo::class);
    } 
}

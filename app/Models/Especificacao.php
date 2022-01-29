<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Especificacao extends Model
{
    use HasFactory;
    

    protected $table = 'especificacoes';

    protected $fillable = [
        'nome'
    ];

    // Adicionar accessor para exibir o 0 na frente de números com somente uma casa decimal
}

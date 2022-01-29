<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GrupoFonte extends Model
{
    use HasFactory;
    

    protected $table = 'grupos_fontes';

    protected $fillable = [
        'nome'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FonteTipo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fontes_tipos';

    protected $fillable = [
        'grupo_fonte_id',
        'especificacao_id',
        'nome'
    ];

}

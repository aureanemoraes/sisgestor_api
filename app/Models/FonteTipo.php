<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FonteTipo extends Model
{
    use HasFactory;

    protected $table = 'fontes_tipos';

    protected $fillable = [
        'grupo_fonte_id',
        'especificacao_id',
        'nome',
        'fav'
    ];

    protected $with = [
        'grupo_fonte:id,nome',
        'especificacao:id,nome'
    ];

    public function grupo_fonte()
    {
        return $this->belongsTo(GrupoFonte::class);
    }

    public function especificacao()
    {
        return $this->belongsTo(Especificacao::class, 'especificacao_id', 'id');
    }
}

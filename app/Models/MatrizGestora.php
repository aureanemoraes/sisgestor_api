<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizGestora extends Model
{
    use HasFactory;

    protected $table = ['matrizes_gestoras'];

    protected $fillable = [
        'unidade_gestora_id',
        'exercicio_id',
        'matriz_id'
    ];
    public function unidade_gestora()
    {
        return $this->belongsTo(UnidadeGestora::class);
    }

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class);
    }

    public function matriz()
    {
        return $this->belongsTo(Matriz::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acao extends Model
{
    use HasFactory;

    protected $table = 'acoes';

    protected $fillable = [
        'acao_tipo_id',
        'exercicio_id',
        'fav'
    ];

    // protected $with = [
    //     'acao_tipo',
    //     'exercicio'
    // ];

    public function acao_tipo()
    {
        return $this->belongsTo(AcaoTipo::class);
    }
    
    public function exercicio()
    {
        return $this->belongsTo(exercicio::class);
    }

    public function fontes()
    {
        return $this->belongsToMany(Fonte::class, 'fontes_acoes', 'acao_id', 'fonte_id');
    }
}

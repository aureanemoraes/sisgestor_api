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
        'exercicio_id'
    ];

    public function acao_tipo()
    {
        return $this->belongsTo(AcaoTipo::class);
    }
    
    public function matriz()
    {
        return $this->belongsTo(Matriz::class);
    }
}

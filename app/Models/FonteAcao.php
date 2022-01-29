<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FonteAcao extends Model
{
    use HasFactory;

    protected $table = 'fontes_acoes';

    protected $fillable = [
        'fonte_id',
        'acao_id',
        'matriz_id',
        'valor'
    ];

    public function fonte()
    {
        return $this->belongsTo(Fonte::class);
    }

    public function acao_tipo()
    {
        return $this->belongsTo(AcaoTipo::class);
    }
    
    public function matriz()
    {
        return $this->belongsTo(Matriz::class);
    }
}

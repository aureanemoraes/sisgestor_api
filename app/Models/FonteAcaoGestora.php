<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FonteAcaoGestora extends Model
{
    use HasFactory;

    protected $table = 'fontes_acoes_gestoras';

    protected $fillable = [
        'fonte_acao_id',
        'matriz_gestora_id',
        'valor'
    ];

    public function fonte_acao()
    {
        return $this->belongsTo(FonteAcao::class);
    }

    public function matriz_gestora()
    {
        return $this->belongsTo(MatrizGestora::class);
    }
}

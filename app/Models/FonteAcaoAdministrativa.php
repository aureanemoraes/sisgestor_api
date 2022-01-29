<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FonteAcaoAdministrativa extends Model
{
    use HasFactory;

    protected $table = 'fontes_acoes_administrativas';

    protected $fillable = [
        'fonte_acao_gestora_id',
        'matriz_administrativa_id',
        'valor'
    ];

    public function fonte_acao_gestora()
    {
        return $this->belongsTo(FonteAcaoGestora::class);
    }

    public function matriz_gestora()
    {
        return $this->belongsTo(MatrizAdministrativa::class);
    }
}

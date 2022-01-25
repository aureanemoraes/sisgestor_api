<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NumberFormatter;

class RecursoAdministrativa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'recursos_administrativas';

    protected $fillable = [
        'valor',
        'instituicao_id',
        'recurso_gestora_id'
    ];

    // Accessors
    public function getValorAttribute($value)
    {
        $moeda = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
   
        return $moeda->formatCurrency($value, 'BRL');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function recurso_gestora()
    {
        return $this->belongsTo(RecursoGestora::class);
    }
}

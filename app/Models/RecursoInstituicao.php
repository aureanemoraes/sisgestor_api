<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use NumberFormatter;

class RecursoInstituicao extends Model
{
    use HasFactory;
    

    protected $table = 'recursos_instituicoes';

    protected $fillable = [
        'valor',
        'instituicao_id'
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
}

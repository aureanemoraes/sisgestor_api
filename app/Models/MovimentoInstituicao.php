<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use NumberFormatter;

class MovimentoInstituicao extends Model
{
    use HasFactory;
    

    protected $table = 'movimentos_instituicoes';

    protected $fillable = [
        'valor',
        'recurso_instituicao_id',
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

    public function recurso_instituicao()
    {
        return $this->belongsTo(RecursoInstituicao::class);
    } 
}

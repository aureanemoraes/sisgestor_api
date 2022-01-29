<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use NumberFormatter;

class MovimentoAdministrativa extends Model
{
    use HasFactory;
    

    protected $table = 'movimentos_administrativas';

    protected $fillable = [
        'valor',
        'movimento_gestora_id',
        'recurso_admistrativa_id',
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

    public function recurso_administrativa()
    {
        return $this->belongsTo(RecursoAdministrativa::class);
    } 

    public function movimento_gestora()
    {
        return $this->belongsTo(MovimentoGestora::class);
    } 
}

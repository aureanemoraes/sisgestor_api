<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use NumberFormatter;

class MovimentoGestora extends Model
{
    use HasFactory;
    

    protected $table = 'movimentos_gestoras';

    protected $fillable = [
        'valor',
        'recurso_gestora_id',
        'movimento_instituicao_id',
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

    public function recurso_gestora()
    {
        return $this->belongsTo(RecursoGestora::class);
    } 

    public function movimento_instituicao()
    {
        return $this->belongsTo(MovimentoInstituicao::class);
    } 
}

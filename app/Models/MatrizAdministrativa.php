<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizAdministrativa extends Model
{
    use HasFactory;

    protected $table = 'matrizes_administrativas';

    protected $fillable = [
        'unidade_administrativa_id',
        'exercicio_id',
        'matriz_gestora_id'
    ];

    public function unidade_administrativa()
    {
        return $this->belongsTo(UnidadeAdministrativa::class);
    }
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class);
    } 

    public function matriz_gestora()
    {
        return $this->belongsTo(MatrizGestora::class);
    } 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matriz extends Model
{
    use HasFactory;

    protected $table = 'matrizes';

    protected $fillable = [
        'instituicao_id',
        'exercicio_id'
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class);
    } 
}

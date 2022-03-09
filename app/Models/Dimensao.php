<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimensao extends Model
{
    use HasFactory;

    protected $table = 'dimensoes';

    protected $fillable = [
        'nome',
        'descricao',
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

    public function objetivos()
    {
        return $this->hasMany(Objetivo::class);
    } 
}

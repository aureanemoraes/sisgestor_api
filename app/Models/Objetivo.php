<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    use HasFactory;

    protected $table = 'objetivos';

    protected $fillable = [
        'nome',
        'descricao',
        'dimensao_id'
    ];

    public function dimensao()
    {
        return $this->belongsTo(Dimensao::class);
    } 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas';

    protected $fillable = [
        'programa_tipo_id',
        'matriz_id'
    ];

    public function programa_tipo()
    {
        return $this->belongsTo(ProgramaTipo::class);
    }
    
    public function matriz()
    {
        return $this->belongsTo(Matriz::class);
    } 
}

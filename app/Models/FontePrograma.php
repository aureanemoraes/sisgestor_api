<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FontePrograma extends Model
{
    use HasFactory;

    protected $table = 'fontes_programas';

    protected $fillable = [
        'fonte_id',
        'programa_id',
        'exercicio_id'
    ];

    public function fonte()
    {
        return $this->belongsTo(Fonte::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function matriz()
    {
        return $this->belongsTo(Matriz::class);
    }
}

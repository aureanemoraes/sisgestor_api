<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonte extends Model
{
    use HasFactory;

    protected $table = 'fontes';

    protected $fillable = [
        'fonte_tipo_id',
        'exercicio_id',
        'valor'
    ];

    public function fonte_tipo()
    {
        return $this->belongsTo(FonteTipo::class);
    }
    
    public function matriz()
    {
        return $this->belongsTo(Matriz::class);
    } 
}

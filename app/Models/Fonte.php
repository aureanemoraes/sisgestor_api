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

    protected $appends = [
        'valor_utilizado'
    ];

    public function getValorUtilizadoAttribute() {
        return isset($this->attributes['valor_utilizado']) ? $this->attributes['valor_utilizado'] : 0;
    }

    public function fonte_tipo()
    {
        return $this->belongsTo(FonteTipo::class);
    }
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class);
    } 
}

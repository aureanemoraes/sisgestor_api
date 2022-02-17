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
        'valor',
        'fav'
    ];

    // protected $with = [
    //     'fonte_tipo',
    //     'exercicio'
    // ];

    protected $appends = [
        'valor_utilizado',
        'valor_distribuido'
    ];

    public function getValorUtilizadoAttribute() {
        return isset($this->attributes['valor_utilizado']) ? $this->attributes['valor_utilizado'] : 0;
    }

    public function getValorDistribuidoAttribute() {
        return isset($this->attributes['valor_distribuido']) ? $this->attributes['valor_distribuido'] : 0;
    }

    public function fonte_tipo()
    {
        return $this->belongsTo(FonteTipo::class);
    }
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class);
    } 

    public function acoes()
    {
        return $this->belongsToMany(Acao::class, 'fontes_acoes', 'fonte_id', 'acao_id')->withPivot(
            'exercicio_id',
            'valor',
            'instituicao_id',
            'unidade_gestora_id',
            'unidade_administrativa_id'
        );
    }
}

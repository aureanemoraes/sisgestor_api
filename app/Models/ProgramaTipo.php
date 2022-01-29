<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaTipo extends Model
{
    use HasFactory;

    protected $table = 'programas_tipos';

    protected $fillable = [
        'codigo',
        'nome'
    ];
}
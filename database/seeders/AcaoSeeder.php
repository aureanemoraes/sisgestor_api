<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acao;

class AcaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Acao::create([
            'acao_tipo_id' => 1294,
            'exercicio_id' => 1,
            'fav' => 1 
        ]);

        Acao::create([
            'acao_tipo_id' => 365,
            'exercicio_id' => 1
        ]);

        Acao::create([
            'acao_tipo_id' => 2049,
            'exercicio_id' => 1,
            'fav' => 1 
        ]);
    }
}

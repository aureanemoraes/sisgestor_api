<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetaOrcamentaria;

class MetaOrcamentariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MetaOrcamentaria::create([
            'nome' => 'Meta Orçamentária 1',
            'qtd_estimada' => 100,
            'qtd_alcancada' => 50,
            'instituicao_id' => 1
        ]);

        MetaOrcamentaria::create([
            'nome' => 'Meta Orçamentária 2',
            'natureza_despesa_id' => 1,
            'instituicao_id' => 1
        ]);
    }
}

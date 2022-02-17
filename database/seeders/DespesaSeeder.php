<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Despesa;

class DespesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // fonte_acao_id in (5,6,12,13)
        Despesa::create([
            'descricao' => 'Despesa Teste 1',
            'valor' => 100,
            'valor_total' => 100,
            'qtd' => 1,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_fixa',
            'fonte_acao_id' => 5,
            'centro_custo_id' => 1,
            'natureza_despesa_id' => 1,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1
        ]);
    }
}

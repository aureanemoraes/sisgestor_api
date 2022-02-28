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
            'fonte_acao_id' => 6,
            'centro_custo_id' => 1,
            'natureza_despesa_id' => 1,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);

        Despesa::create([
            'descricao' => 'Despesa Diáris Civil Fixa 1',
            'valor' => 120.84,
            'valor_total' => 120.84,
            'qtd' => 1,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_fixa',
            'fonte_acao_id' => 5,
            'centro_custo_id' => 1,
            'natureza_despesa_id' => 103,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);

        Despesa::create([
            'descricao' => 'Despesa Diáris Civil Variável 1',
            'valor' => 202.83,
            'valor_total' => 406.66,
            'qtd' => 1,
            'qtd_pessoas' => 2,
            'tipo' => 'despesa_variavel',
            'fonte_acao_id' => 12,
            'centro_custo_id' => 1,
            'natureza_despesa_id' => 103,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);

        Despesa::create([
            'descricao' => 'Despesa Diáris Civil Variável 2',
            'valor' => 85,
            'valor_total' => 170,
            'qtd' => 2,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_variavel',
            'fonte_acao_id' => 5,
            'centro_custo_id' => 2,
            'natureza_despesa_id' => 103,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);

        Despesa::create([
            'descricao' => 'Despesa Material de Consumo Fixa 1',
            'valor' => 25,
            'valor_total' => 25,
            'qtd' => 1,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_fixa',
            'fonte_acao_id' => 5,
            'centro_custo_id' => 2,
            'natureza_despesa_id' => 111,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);

        Despesa::create([
            'descricao' => 'Despesa Material de Consumo Variável 1',
            'valor' => 30.25,
            'valor_total' => 60.50,
            'qtd' => 2,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_variavel',
            'fonte_acao_id' => 12,
            'centro_custo_id' => 1,
            'natureza_despesa_id' => 111,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);

        Despesa::create([
            'descricao' => 'Despesa Material de Processament de Dados Fixa 1',
            'valor' => 60,
            'valor_total' => 60,
            'qtd' => 1,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_fixa',
            'fonte_acao_id' => 12,
            'centro_custo_id' => 2,
            'natureza_despesa_id' => 111,
            'subnatureza_despesa_id' => 34,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);
        // 192

        Despesa::create([
            'descricao' => 'Despesa Obras e Instalações Variável 1',
            'valor' => 50,
            'valor_total' => 50,
            'qtd' => 1,
            'qtd_pessoas' => 1,
            'tipo' => 'despesa_variavel',
            'fonte_acao_id' => 12,
            'centro_custo_id' => 1,
            'natureza_despesa_id' => 192,
            'subnatureza_despesa_id' => null,
            'unidade_administrativa_id' => 1,
            'exercicio_id' => 1
        ]);
    }
}

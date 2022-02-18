<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercicio;

class ExercicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exercicio::create([
            'nome' => '2020',
            'data_inicio' => '01/03/2019',
            'data_fim' => '31/12/2019',
            'data_inicio_loa' => '01/01/2020',
            'data_fim_loa' => '31/12/2020',
            'aprovado' => false,
            'instituicao_id' => 1
        ]);

        Exercicio::create([
            'nome' => '2023',
            'data_inicio' => '01/03/2022',
            'data_fim' => '31/12/2022',
            'data_inicio_loa' => '01/01/2023',
            'data_fim_loa' => '31/12/2023',
            'aprovado' => false,
            'instituicao_id' => 1
        ]);
    }
}

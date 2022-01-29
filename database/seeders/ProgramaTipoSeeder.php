<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramaTipo;

class ProgramaTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgramaTipo::create([ 'codigo' => '1061', 'nome' => 'Brasil Escolarizado' ]);
        ProgramaTipo::create([ 'codigo' => '1062', 'nome' => 'Desenvolvimento da Educação Profissional e Tecnológica' ]);
        ProgramaTipo::create([ 'codigo' => '0089', 'nome' => 'Previdência de Inativos e Pensionistas da União' ]);
        ProgramaTipo::create([ 'codigo' => '0750', 'nome' => 'Apoio Administrativo' ]);
        ProgramaTipo::create([ 'codigo' => '0901', 'nome' => 'Cumprimento de Senteça Judicial Transitado em Julgado (Precatórios)' ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GrupoFonteSeeder::class,
            EspecificacaoSeeder::class,
            AcaoTipoSeeder::class,
            NaturezaDespesaTipoSeeder::class,
            SubnaturezaDespesaTipoSeeder::class,
            ProgramaTipoSeeder::class
        ]);
    }
}

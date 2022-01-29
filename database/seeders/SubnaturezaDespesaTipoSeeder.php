<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubnaturezaDespesaTipo;

class SubnaturezaDespesaTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubnaturezaDespesaTipo::create([ 'codigo' => '31900101', 'nome' => 'Proventos - Pessoa Civil', 'natureza_despesa_id' => '4' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31900109', 'nome' => 'Gratificação Tempo de Serviço', 'natureza_despesa_id' => '4' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31900187', 'nome' => 'Complementação de Aposentadorias', 'natureza_despesa_id' => '4' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31900401', 'nome' => 'Professores Substitutos/Visitantes', 'natureza_despesa_id' => '6' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31900420', 'nome' => 'Outras Vantagens Cont. Temp - Verba de Pessoal', 'natureza_despesa_id' => '6' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31900805', 'nome' => 'Auxílio Natalidade Ativo Civil', 'natureza_despesa_id' => '8' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901101', 'nome' => 'Vencimentos e Salários', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901104', 'nome' => 'Adicional Noturno', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901105', 'nome' => 'Incorporações', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901107', 'nome' => 'Abono Permanência EC 41/2003', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901109', 'nome' => 'Adicional de Periculosidade', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901110', 'nome' => 'Adicional de Insalubridade', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901128', 'nome' => 'Vantagem Pecuniária Individual', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901131', 'nome' => 'Gratificação de Exercício de Cargo', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901133', 'nome' => 'Gratificação de Exercício de Funções', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901137', 'nome' => 'Gratificação de Tempo de Serviço', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901142', 'nome' => 'Férias Indenizadas', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901143', 'nome' => '13º Salário', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901145', 'nome' => 'Férias Abono Art. 7 XVIII CF', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901146', 'nome' => 'Férias - Pagamento Antecipado', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901187', 'nome' => 'Complementação Salarial - Pessoal Civil', 'natureza_despesa_id' => '10' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31901632', 'nome' => 'Substituições', 'natureza_despesa_id' => '13' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31909127', 'nome' => 'Ação não Trans. Julg. Car. Contínuo Ativo', 'natureza_despesa_id' => '17' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31909129', 'nome' => 'Ação não Trans. Julg. Car. Contínuo Aposentado', 'natureza_despesa_id' => '17' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31909132', 'nome' => 'Sent. Judic. Trans. Julg. Car. Único - Ativo C', 'natureza_despesa_id' => '17' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '31909134', 'nome' => 'Sent. Judic. Trans. Julg. Car. Único - Inativo/Pensionista C', 'natureza_despesa_id' => '17' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33900420', 'nome' => 'Outras Vantagens Cont. Temp - Verba de Custe', 'natureza_despesa_id' => '97' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33900855', 'nome' => 'Auxílio Creche', 'natureza_despesa_id' => '100' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33903607', 'nome' => 'Estagiários', 'natureza_despesa_id' => '116' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33903628', 'nome' => 'Serviço de Seleção e Treinamento', 'natureza_despesa_id' => '116' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33904601', 'nome' => 'Indenização Auxílio Alimentação', 'natureza_despesa_id' => '122' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33904901', 'nome' => 'Indenização Auxílio Transporte', 'natureza_despesa_id' => '125' ]);
        SubnaturezaDespesaTipo::create([ 'codigo' => '33909308', 'nome' => 'Ressarcimento Assistência Medico/Odonto', 'natureza_despesa_id' => '129' ]);
    }
}

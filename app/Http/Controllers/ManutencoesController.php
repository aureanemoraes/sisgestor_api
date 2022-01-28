<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManutencoesController extends Controller
{
    public function teste() {
       $array = [
            [
                "pk"=> 1, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31900101", 
                    "natureza_despesa"=> 4, 
                    "nome"=> "Proventos - Pessoa Civil", 
                    "codigo_subelemento"=> "01"
                ]
            ], 
            [
                "pk"=> 2, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31900109", 
                    "natureza_despesa"=> 4, 
                    "nome"=> "Gratificação Tempo de Serviço", 
                    "codigo_subelemento"=> "09"
                ]
            ], 
            [
                "pk"=> 3, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31900187", 
                    "natureza_despesa"=> 4, 
                    "nome"=> "Complementação de Aposentadorias", 
                    "codigo_subelemento"=> "87"
                ]
            ], 
            [
                "pk"=> 4, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31900401", 
                    "natureza_despesa"=> 6, 
                    "nome"=> "Professores Substitutos/Visitantes", 
                    "codigo_subelemento"=> "01"
                ]
            ], 
            [
                "pk"=> 5, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31900420", 
                    "natureza_despesa"=> 6, 
                    "nome"=> "Outras Vantagens Cont. Temp - Verba de Pessoal", 
                    "codigo_subelemento"=> "20"
                ]
            ], 
            [
                "pk"=> 6, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31900805", 
                    "natureza_despesa"=> 8, 
                    "nome"=> "Auxílio Natalidade Ativo Civil", 
                    "codigo_subelemento"=> "05"
                ]
            ], 
            [
                "pk"=> 7, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901101", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Vencimentos e Salários", 
                    "codigo_subelemento"=> "01"
                ]
            ], 
            [
                "pk"=> 8, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901104", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Adicional Noturno", 
                    "codigo_subelemento"=> "04"
                ]
            ], 
            [
                "pk"=> 9, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901105", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Incorporações", 
                    "codigo_subelemento"=> "05"
                ]
            ], 
            [
                "pk"=> 10, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901107", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Abono Permanência EC 41/2003", 
                    "codigo_subelemento"=> "07"
                ]
            ], 
            [
                "pk"=> 11, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901109", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Adicional de Periculosidade", 
                    "codigo_subelemento"=> "09"
                ]
            ], 
            [
                "pk"=> 12, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901110", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Adicional de Insalubridade", 
                    "codigo_subelemento"=> "10"
                ]
            ], 
            [
                "pk"=> 13, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901128", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Vantagem Pecuniária Individual", 
                    "codigo_subelemento"=> "28"
                ]
            ], 
            [
                "pk"=> 14, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901131", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Gratificação de Exercício de Cargo", 
                    "codigo_subelemento"=> "31"
                ]
            ], 
            [
                "pk"=> 15, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901133", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Gratificação de Exercício de Funções", 
                    "codigo_subelemento"=> "33"
                ]
            ], 
            [
                "pk"=> 16, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901137", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Gratificação de Tempo de Serviço", 
                    "codigo_subelemento"=> "37"
                ]
            ], 
            [
                "pk"=> 17, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901142", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Férias Indenizadas", 
                    "codigo_subelemento"=> "42"
                ]
            ], 
            [
                "pk"=> 18, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901143", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "13º Salário", 
                    "codigo_subelemento"=> "43"
                ]
            ], 
            [
                "pk"=> 19, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901145", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Férias Abono Art. 7 XVIII CF", 
                    "codigo_subelemento"=> "45"
                ]
            ], 
            [
                "pk"=> 20, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901146", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Férias - Pagamento Antecipado", 
                    "codigo_subelemento"=> "46"
                ]
            ], 
            [
                "pk"=> 21, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901187", 
                    "natureza_despesa"=> 10, 
                    "nome"=> "Complementação Salarial - Pessoal Civil", 
                    "codigo_subelemento"=> "87"
                ]
            ], 
            [
                "pk"=> 22, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31901632", 
                    "natureza_despesa"=> 13, 
                    "nome"=> "Substituições", 
                    "codigo_subelemento"=> "32"
                ]
            ], 
            [
                "pk"=> 23, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31909127", 
                    "natureza_despesa"=> 17, 
                    "nome"=> "Ação não Trans. Julg. Car. Contínuo Ativo", 
                    "codigo_subelemento"=> "27"
                ]
            ], 
            [
                "pk"=> 24, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31909129", 
                    "natureza_despesa"=> 17, 
                    "nome"=> "Ação não Trans. Julg. Car. Contínuo Aposentado", 
                    "codigo_subelemento"=> "29"
                ]
            ], 
            [
                "pk"=> 25, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31909132", 
                    "natureza_despesa"=> 17, 
                    "nome"=> "Sent. Judic. Trans. Julg. Car. Único - Ativo C", 
                    "codigo_subelemento"=> "32"
                ]
            ], 
            [
                "pk"=> 26, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "31909134", 
                    "natureza_despesa"=> 17, 
                    "nome"=> "Sent. Judic. Trans. Julg. Car. Único - Inativo/Pensionista C", 
                    "codigo_subelemento"=> "34"
                ]
            ], 
            [
                "pk"=> 27, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33900420", 
                    "natureza_despesa"=> 97, 
                    "nome"=> "Outras Vantagens Cont. Temp - Verba de Custe", 
                    "codigo_subelemento"=> "20"
                ]
            ], 
            [
                "pk"=> 28, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33900855", 
                    "natureza_despesa"=> 100, 
                    "nome"=> "Auxílio Creche", 
                    "codigo_subelemento"=> "55"
                ]
            ], 
            [
                "pk"=> 29, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33903607", 
                    "natureza_despesa"=> 116, 
                    "nome"=> "Estagiários", 
                    "codigo_subelemento"=> "07"
                ]
            ], 
            [
                "pk"=> 30, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33903628", 
                    "natureza_despesa"=> 116, 
                    "nome"=> "Serviço de Seleção e Treinamento", 
                    "codigo_subelemento"=> "28"
                ]
            ], 
            [
                "pk"=> 31, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33904601", 
                    "natureza_despesa"=> 122, 
                    "nome"=> "Indenização Auxílio Alimentação", 
                    "codigo_subelemento"=> "01"
                ]
            ], 
            [
                "pk"=> 32, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33904901", 
                    "natureza_despesa"=> 125, 
                    "nome"=> "Indenização Auxílio Transporte", 
                    "codigo_subelemento"=> "01"
                ]
            ], 
            [
                "pk"=> 33, 
                "model"=> "financeiro.subelementonaturezadespesa", 
                "fields"=> [
                    "codigo"=> "33909308", 
                    "natureza_despesa"=> 129, 
                    "nome"=> "Ressarcimento Assistência Medico/Odonto", 
                    "codigo_subelemento"=> "08"
                ]
            ]
       ];

        foreach($array as $key => $value) {
            foreach($value as $attribute => $item) {
                if($attribute == 'fields') {
                    echo "SubnaturezaDespesa::create([ 'codigo' => '" . $item['codigo'] . "', 'nome' =>  '" . trim($item['nome']) . "', 'natureza_despesa_id' => '" . $item['natureza_despesa'] . "' ]);" . "<br>";
                }
            }
        }

        // foreach($array as $key => $value) {
        //   echo "Acao::create([ 'codigo' => '" . $value['codigo'] . "', 'nome' =>  '" . $value['descricao'] . "' ]);" . "<br>";
        // }
    }
}

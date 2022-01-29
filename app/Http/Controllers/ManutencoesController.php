<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManutencoesController extends Controller
{
    public function teste() {
       $array = [
        [
            "pk"=> 1, 
            "model"=> "financeiro.programa", 
            "fields"=> [
                "codigo"=> "1061", 
                "nome"=> "Brasil Escolarizado"
            ]
        ], 
        [
            "pk"=> 2, 
            "model"=> "financeiro.programa", 
            "fields"=> [
                "codigo"=> "1062", 
                "nome"=> "Desenvolvimento da Educação Profissional e Tecnológica"
            ]
        ], 
        [
            "pk"=> 3, 
            "model"=> "financeiro.programa", 
            "fields"=> [
                "codigo"=> "0089", 
                "nome"=> "Previdência de Inativos e Pensionistas da União"
            ]
        ], 
        [
            "pk"=> 4, 
            "model"=> "financeiro.programa", 
            "fields"=> [
                "codigo"=> "0750", 
                "nome"=> "Apoio Administrativo"
            ]
        ], 
        [
            "pk"=> 5, 
            "model"=> "financeiro.programa", 
            "fields"=> [
                "codigo"=> "0901", 
                "nome"=> "Cumprimento de Senteça Judicial Transitado em Julgado (Precatórios)"
            ]
        ]
       ];

        foreach($array as $key => $value) {
            foreach($value as $attribute => $item) {
                if($attribute == 'fields') {
                    // echo "ProgramaTipo::create([ 'codigo' => '" . $item['codigo'] . "', 'nome' =>  '" . trim($item['nome']) . "', 'natureza_despesa_id' => '" . $item['natureza_despesa'] . "' ]);" . "<br>";
                  echo "ProgramaTipo::create([ 'codigo' => '" . $item['codigo'] . "', 'nome' =>  '" . $item['nome'] . "' ]);" . "<br>";
                }
            }
        }

        // foreach($array as $key => $value) {
        //   echo "Acao::create([ 'codigo' => '" . $value['codigo'] . "', 'nome' =>  '" . $value['descricao'] . "' ]);" . "<br>";
        // }
    }
}

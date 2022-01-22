<?php

namespace App\Http\Transformers;

use App\Models\Pessoa;

class PessoaTransformer
{

    public static function toInstance(array $input, $pessoa = null)
    {
      if (empty($pessoa)) {
        $pessoa = new Pessoa();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'nome':
            $pessoa->nome = $value;
            break;
          case 'cpf':
            $pessoa->cpf = $value;
            break;
          case 'logradouro':
            $pessoa->logradouro = $value;
            break;
          case 'numero':
            $pessoa->numero = $value;
            break;
          case 'bairro':
            $pessoa->bairro = $value;
            break;
          case 'complemento':
            $pessoa->complemento = $value;
            break;
          case 'filiacao_1':
            $pessoa->filiacao_1 = $value;
            break;
          case 'filiacao_2':
            $pessoa->filiacao_2 = $value;
            break;
          case 'telefone':
            $pessoa->telefone = $value;
            break;
          case 'email':
            $pessoa->email = $value;
            break;
          case 'instituicao_id':
            $pessoa->instituicao_id = $value;
            break;
        }
      }

      return $pessoa;
    }
}
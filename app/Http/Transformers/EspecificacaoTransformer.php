<?php

namespace App\Http\Transformers;

use App\Models\Especificacao;

class EspecificacaoTransformer
{

    public static function toInstance(array $input, $especificacao = null)
    {
      if (empty($especificacao)) {
        $especificacao = new Especificacao();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'nome':
            $especificacao->nome = $value;
            break;
        }
      }

      return $especificacao;
    }
}
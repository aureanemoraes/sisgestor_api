<?php

namespace App\Http\Transformers;

use App\Models\Objetivo;

class ObjetivoTransformer
{

    public static function toInstance(array $input, $objetivo = null)
    {
      if (empty($objetivo)) {
        $objetivo = new Objetivo();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'nome':
            $objetivo->nome = $value;
            break;
          case 'descricao':
            $objetivo->descricao = $value;
            break;
          case 'dimensao_id':
            $objetivo->dimensao_id = $value;
            break;
        }
      }

      return $objetivo;
    }
}
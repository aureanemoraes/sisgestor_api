<?php

namespace App\Http\Transformers;

use App\Models\Acao;

class AcaoTransformer
{

    public static function toInstance(array $input, $acao = null)
    {
      if (empty($acao)) {
        $acao = new Acao();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'acao_tipo_id':
            $acao->acao_tipo_id = $value;
            break;
          case 'matriz_id':
            $acao->matriz_id = $value;
            break;
        }
      }

      return $acao;
    }
}
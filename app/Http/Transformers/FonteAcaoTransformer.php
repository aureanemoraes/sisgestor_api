<?php

namespace App\Http\Transformers;

use App\Models\FonteAcao;

class FonteAcaoTransformer
{

    public static function toInstance(array $input, $fonte_acao = null)
    {
      if (empty($fonte_acao)) {
        $fonte_acao = new FonteAcao();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'fonte_id':
            $fonte_acao->fonte_id = $value;
            break;
          case 'acao_id':
            $fonte_acao->acao_id = $value;
            break;
          case 'matriz_id':
            $fonte_acao->matriz_id = $value;
            break;
          case 'valor':
            $fonte_acao->valor = $value;
            break;
        }
      }

      return $fonte_acao;
    }
}
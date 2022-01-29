<?php

namespace App\Http\Transformers;

use App\Models\FonteAcaoGestora;

class FonteAcaoGestoraTransformer
{

    public static function toInstance(array $input, $fonte_acao_gestora = null)
    {
      if (empty($fonte_acao_gestora)) {
        $fonte_acao_gestora = new FonteAcaoGestora();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'fonte_acao_id':
            $fonte_acao_gestora->fonte_acao_id = $value;
            break;
          case 'matriz_gestora_id':
            $fonte_acao_gestora->matriz_gestora_id = $value;
            break;
          case 'valor':
            $fonte_acao_gestora->valor = $value;
            break;
        }
      }

      return $fonte_acao_gestora;
    }
}
<?php

namespace App\Http\Transformers;

use App\Models\MovimentoGestora;

class MovimentoGestoraTransformer
{

    public static function toInstance(array $input, $movimento_gestora = null)
    {
      if (empty($movimento_gestora)) {
        $movimento_gestora = new MovimentoGestora();
      } 

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'valor':
            $movimento_gestora->valor = $value;
            break;
          case 'instituicao_id':
            $movimento_gestora->instituicao_id = $value;
            break;
          case 'recurso_gestora_id':
            $movimento_gestora->recurso_gestora_id = $value;
            break;
          case 'movimento_instituicao_id':
            $movimento_gestora->movimento_instituicao_id = $value;
            break;
        }
      }

      return $movimento_gestora;
    }
}
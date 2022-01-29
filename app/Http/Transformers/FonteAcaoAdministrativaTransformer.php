<?php

namespace App\Http\Transformers;

use App\Models\FonteAcaoAdministrativa;

class FonteAcaoAdministrativaTransformer
{

    public static function toInstance(array $input, $fonte_acao_administrativa = null)
    {
      if (empty($fonte_acao_administrativa)) {
        $fonte_acao_administrativa = new FonteAcaoAdministrativa();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'fonte_acao_gestora_id':
            $fonte_acao_administrativa->fonte_acao_gestora_id = $value;
            break;
          case 'matriz_administrativa_id':
            $fonte_acao_administrativa->matriz_administrativa_id = $value;
            break;
          case 'valor':
            $fonte_acao_administrativa->valor = $value;
            break;
        }
      }

      return $fonte_acao_administrativa;
    }
}
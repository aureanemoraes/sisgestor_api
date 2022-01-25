<?php

namespace App\Http\Transformers;

use App\Models\MovimentoAdministrativa;

class MovimentoAdministrativaTransformer
{

    public static function toInstance(array $input, $movimento_administrativa = null)
    {
      if (empty($movimento_administrativa)) {
        $movimento_administrativa = new MovimentoAdministrativa();
      } 

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'valor':
            $movimento_administrativa->valor = $value;
            break;
          case 'movimento_gestora_id':
            $movimento_administrativa->movimento_gestora_id = $value;
            break;
          case 'recurso_admistrativa_id':
            $movimento_administrativa->recurso_admistrativa_id = $value;
            break;
          case 'instituicao_id':
            $movimento_administrativa->instituicao_id = $value;
            break;
        }
      }

      return $movimento_administrativa;
    }
}
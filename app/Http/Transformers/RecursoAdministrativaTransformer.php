<?php

namespace App\Http\Transformers;

use App\Models\RecursoAdministrativa;

class RecursoAdministrativaTransformer
{

    public static function toInstance(array $input, $recurso_administrativa = null)
    {
      if (empty($recurso_administrativa)) {
        $recurso_administrativa = new RecursoAdministrativa();
      } 

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'valor':
            $recurso_administrativa->valor = $value;
            break;
          case 'instituicao_id':
            $recurso_administrativa->instituicao_id = $value;
            break;
          case 'recurso_gestora_id':
            $recurso_administrativa->recurso_gestora_id = $value;
            break;
        }
      }

      return $recurso_administrativa;
    }
}
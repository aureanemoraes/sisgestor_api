<?php

namespace App\Http\Transformers;

use App\Models\RecursoGestora;

class RecursoGestoraTransformer
{

    public static function toInstance(array $input, $recurso_gestora = null)
    {
      if (empty($recurso_gestora)) {
        $recurso_gestora = new RecursoGestora();
      } 

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'valor':
            $recurso_gestora->valor = $value;
            break;
          case 'instituicao_id':
            $recurso_gestora->instituicao_id = $value;
            break;
          case 'recurso_instituicao_id':
            $recurso_gestora->recurso_instituicao_id = $value;
            break;
        }
      }

      return $recurso_gestora;
    }
}
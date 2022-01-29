<?php

namespace App\Http\Transformers;

use App\Models\MatrizAdministrativa;

class MatrizAdministrativaTransformer
{

    public static function toInstance(array $input, $matriz_administrativa = null)
    {
      if (empty($matriz_administrativa)) {
        $matriz_administrativa = new MatrizAdministrativa();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'unidade_administrativa_id':
            $matriz_administrativa->unidade_administrativa_id = $value;
            break;
          case 'exercicio_id':
            $matriz_administrativa->exercicio_id = $value;
            break;
          case 'matriz_gestora_id':
            $matriz_administrativa->matriz_gestora_id = $value;
            break;
        }
      }

      return $matriz_administrativa;
    }
}
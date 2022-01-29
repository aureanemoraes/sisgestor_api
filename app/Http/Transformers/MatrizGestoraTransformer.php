<?php

namespace App\Http\Transformers;

use App\Models\MatrizGestora;

class MatrizGestoraTransformer
{

    public static function toInstance(array $input, $matriz_gestora = null)
    {
      if (empty($matriz_gestora)) {
        $matriz_gestora = new MatrizGestora();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'unidade_gestora_id':
            $matriz_gestora->unidade_gestora_id = $value;
            break;
          case 'exercicio_id':
            $matriz_gestora->exercicio_id = $value;
            break;
          case 'matriz_id':
            $matriz_gestora->matriz_id = $value;
            break;
        }
      }

      return $matriz_gestora;
    }
}
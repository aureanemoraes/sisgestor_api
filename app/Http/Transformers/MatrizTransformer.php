<?php

namespace App\Http\Transformers;

use App\Models\Matriz;

class MatrizTransformer
{

    public static function toInstance(array $input, $matriz = null)
    {
      if (empty($matriz)) {
        $matriz = new Matriz();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'instituicao_id':
            $matriz->instituicao_id = $value;
            break;
          case 'exercicio_id':
            $matriz->exercicio_id = $value;
            break;
        }
      }

      return $matriz;
    }
}
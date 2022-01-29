<?php

namespace App\Http\Transformers;

use App\Models\Programa;

class ProgramaTransformer
{

    public static function toInstance(array $input, $programa = null)
    {
      if (empty($programa)) {
        $programa = new Programa();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'programa_tipo_id':
            $programa->programa_tipo_id = $value;
            break;
          case 'matriz_id':
            $programa->matriz_id = $value;
            break;
        }
      }

      return $programa;
    }
}
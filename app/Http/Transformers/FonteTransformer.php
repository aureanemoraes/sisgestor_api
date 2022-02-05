<?php

namespace App\Http\Transformers;

use App\Models\Fonte;

class FonteTransformer
{

    public static function toInstance(array $input, $fonte = null)
    {
      if (empty($fonte)) {
        $fonte = new Fonte();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'fonte_tipo_id':
            $fonte->fonte_tipo_id = $value;
            break;
          case 'exercicio_id':
            $fonte->exercicio_id = $value;
            break;
          case 'valor':
            $fonte->valor = $value;
            break;
        }
      }

      return $fonte;
    }
}
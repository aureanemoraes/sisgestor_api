<?php

namespace App\Http\Transformers;

use App\Models\FonteTipo;

class FonteTipoTransformer
{

    public static function toInstance(array $input, $fonte_tipo = null)
    {
      if (empty($fonte_tipo)) {
        $fonte_tipo = new FonteTipo();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'grupo_fonte_id':
            $fonte_tipo->grupo_fonte_id = $value;
            break;
          case 'especificacao_id':
            $fonte_tipo->especificacao_id = $value;
            break;
          case 'nome':
            $fonte_tipo->nome = $value;
            break;
        }
      }

      return $fonte_tipo;
    }
}
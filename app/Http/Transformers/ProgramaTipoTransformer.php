<?php

namespace App\Http\Transformers;

use App\Models\ProgramaTipo;

class ProgramaTipoTransformer
{

    public static function toInstance(array $input, $programa_tipo = null)
    {
      if (empty($programa_tipo)) {
        $programa_tipo = new ProgramaTipo();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'codigo':
            $programa_tipo->codigo = $value;
            break;
          case 'nome':
            $programa_tipo->nome = $value;
            break;
        }
      }

      return $programa_tipo;
    }
}
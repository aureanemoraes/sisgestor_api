<?php

namespace App\Http\Transformers;

use App\Models\CentroCurso;

class CentroCursoTransformer
{

    public static function toInstance(array $input, $centro_custo = null)
    {
      if (empty($centro_custo)) {
        $centro_custo = new CentroCurso();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'nome':
            $centro_custo->nome = $value;
            break;
        }
      }

      return $centro_custo;
    }
}
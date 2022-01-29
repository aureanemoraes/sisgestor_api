<?php

namespace App\Http\Transformers;

use App\Models\NaturezaDespesaTipo;

class NaturezaDespesaTipoTransformer
{

    public static function toInstance(array $input, $natureza_despesa_tipo = null)
    {
      if (empty($natureza_despesa_tipo)) {
        $natureza_despesa_tipo = new NaturezaDespesaTipo();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'nome':
            $natureza_despesa_tipo->nome = $value;
            break;
          case 'codigo':
            $natureza_despesa_tipo->codigo = $value;
            break;
          case 'tipo':
            $natureza_despesa_tipo->tipo = $value;
            break;
        }
      }

      return $natureza_despesa_tipo;
    }
}
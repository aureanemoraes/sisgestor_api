<?php

namespace App\Http\Transformers;

use App\Models\RecursoInstituicao;

class RecursoInstituicaoTransformer
{

    public static function toInstance(array $input, $recurso_instituicao = null)
    {
      if (empty($recurso_instituicao)) {
        $recurso_instituicao = new RecursoInstituicao();
      } 

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'valor':
            $recurso_instituicao->valor = $value;
            break;
          case 'instituicao_id':
            $recurso_instituicao->instituicao_id = $value;
            break;
        }
      }

      return $recurso_instituicao;
    }
}
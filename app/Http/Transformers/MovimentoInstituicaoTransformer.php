<?php

namespace App\Http\Transformers;

use App\Models\MovimentoInstituicao;

class MovimentoInstituicaoTransformer
{

    public static function toInstance(array $input, $movimento_instituicao = null)
    {
      if (empty($movimento_instituicao)) {
        $movimento_instituicao = new MovimentoInstituicao();
      } 

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'valor':
            $movimento_instituicao->valor = $value;
            break;
          case 'instituicao_id':
            $movimento_instituicao->instituicao_id = $value;
            break;
          case 'recurso_instituicao_id':
            $movimento_instituicao->recurso_instituicao_id = $value;
            break;
        }
      }

      return $movimento_instituicao;
    }
}
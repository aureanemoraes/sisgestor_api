<?php

namespace App\Http\Transformers;

use App\Models\Dimensao;

class DimensaoTransformer
{

    public static function toInstance(array $input, $dimensao= null)
    {
      if (empty($dimensao)) {
        $dimensao= new Dimensao();
      }

      foreach ($input as $key => $value) {
        switch ($key) {
          case 'nome':
            $dimensao->nome = $value;
            break;
          case 'descricao':
            $dimensao->descricao = $value;
            break;
          case 'instituicao_id':
            $dimensao->instituicao_id = $value;
            break;
          case 'exercicio_id':
            $dimensao->exercicio_id = $value;
            break;
        }
      }

      return $dimensao;
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Migration20220122 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabela de Instituições
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->string('cnpj');
            $table->string('uasg');
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('complemento')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->timestamps();
        });
        // Tabela de Usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cpf');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('perfil');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->unsignedBigInteger('unidade_gestora_id')->nullable();
            $table->foreign('unidade_gestora_id')->references('id')->on('instituicoes');
            $table->unsignedBigInteger('unidade_administrativa_id')->nullable();
            $table->foreign('unidade_administrativa_id')->references('id')->on('instituicoes');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        // Tabela de Passwords Resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        // Tabela de Exercícios
        Schema::create('exercicios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->date('data_inicio_loa');
            $table->date('data_fim_loa');
            $table->tinyInteger('aprovado')->default(0);
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->timestamps();
        });
        // Tabela de Unidades Gestoras
        Schema::create('unidades_gestoras', function (Blueprint $table) {
            // nome da pessoa
            // titulacao
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->string('cnpj');
            $table->string('uasg');
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('complemento')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->json('logs')->nullable();
            $table->string('diretor_geral');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Unidades Administrativas
        Schema::create('unidades_administrativas', function (Blueprint $table) {
            // gestor
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->string('ugr');
            $table->json('logs')->nullable();
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->unsignedBigInteger('unidade_gestora_id');
            $table->foreign('unidade_gestora_id')->references('id')->on('unidades_gestoras');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Matrizez
        Schema::create('matrizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->unsignedBigInteger('exercicio_id');
            $table->foreign('exercicio_id')->references('id')->on('exercicios');
            $table->timestamps();
        });
        // Tabela de MatrizezGestoras
        Schema::create('matrizes_gestoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unidade_gestora_id');
            $table->foreign('unidade_gestora_id')->references('id')->on('unidades_gestoras');
            $table->unsignedBigInteger('matriz_id');
            $table->foreign('matriz_id')->references('id')->on('matrizes');
            $table->timestamps();
        });
        // Tabela de MatrizezAdministrativas
        Schema::create('matrizes_administrativas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unidade_administrativa_id');
            $table->foreign('unidade_administrativa_id')->references('id')->on('unidades_administrativas');
            $table->unsignedBigInteger('matriz_gestora_id');
            $table->foreign('matriz_gestora_id')->references('id')->on('matrizes_gestoras');
            $table->timestamps();
        });
        // Tabela de GruposFontes
        Schema::create('grupos_fontes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });
        // Tabela de Espeficacoes
        Schema::create('especificacoes', function (Blueprint $table) {
            $table->id()->from(0);
            $table->string('nome');
            $table->timestamps();
        });
        // Tabela FontesTipos
        Schema::create('fontes_tipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_fonte_id');
            $table->foreign('grupo_fonte_id')->references('id')->on('grupos_fontes');
            $table->unsignedBigInteger('especificacao_id');
            $table->foreign('especificacao_id')->references('id')->on('especificacoes');
            $table->string('nome');
            $table->timestamps();
        });
        // Tabela Fontes
        Schema::create('fontes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fonte_tipo_id');
            $table->foreign('fonte_tipo_id')->references('id')->on('fontes_tipos');
            $table->unsignedBigInteger('exericio_id');
            $table->foreign('exericio_id')->references('id')->on('exercicios');
            $table->float('valor');
            $table->timestamps();
        });
        // Tabela Ações Tipos
        Schema::create('acoes_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo');
            $table->timestamps();
        });
        // Tabela Ações
        Schema::create('acoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acao_tipo_id');
            $table->foreign('acao_tipo_id')->references('id')->on('acoes_tipos');
            $table->unsignedBigInteger('exercicio_id');
            $table->foreign('exercicio_id')->references('id')->on('exercicios');
            $table->timestamps();
        });
        // Tabela Fontes Ações
        Schema::create('fontes_acoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fonte_id');
            $table->foreign('fonte_id')->references('id')->on('fontes');
            $table->unsignedBigInteger('acao_id');
            $table->foreign('acao_id')->references('id')->on('acoes');
            $table->unsignedBigInteger('exercicio_id');
            $table->foreign('exercicio_id')->references('id')->on('exercicios');
            $table->unsignedBigInteger('instituicao_id')->nullable();
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->unsignedBigInteger('unidade_gestora_id')->nullable();
            $table->foreign('unidade_gestora_id')->references('id')->on('unidades_gestoras');
            $table->unsignedBigInteger('unidade_administrativa_id')->nullable();
            $table->foreign('unidade_administrativa_id')->references('id')->on('unidades_administrativas');
            $table->float('valor');
            $table->timestamps();
        });
        // Tabela Programas Tipos
        Schema::create('programas_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nome');
            $table->timestamps();
        });
        // Tabela Programas
        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programa_tipo_id');
            $table->foreign('programa_tipo_id')->references('id')->on('programas_tipos');
            $table->unsignedBigInteger('matriz_id');
            $table->foreign('matriz_id')->references('id')->on('matrizes');
            $table->timestamps();
        });
        // Tabela Fontes Programas
        Schema::create('fontes_programas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fonte_id');
            $table->foreign('fonte_id')->references('id')->on('fontes');
            $table->unsignedBigInteger('programa_id');
            $table->foreign('programa_id')->references('id')->on('programas');
            $table->unsignedBigInteger('matriz_id');
            $table->foreign('matriz_id')->references('id')->on('matrizes');
            $table->timestamps();
        });
        // Tabela de Naturezas Despesas
        Schema::create('naturezas_despesas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo');
            $table->string('tipo');
            $table->timestamps();
        });
        // Tabela de Subnaturezas Despesas
        Schema::create('subnaturezas_despesas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo');
            $table->unsignedBigInteger('natureza_despesa_id');
            $table->foreign('natureza_despesa_id')->references('id')->on('naturezas_despesas');
            $table->timestamps();
        });
        // Tabela de Centro Custo
        Schema::create('centros_custos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('instituicoes');
        Schema::dropIfExists('exercicios');
        Schema::dropIfExists('unidades_gestoras');
        Schema::dropIfExists('unidades_administrativas');
        Schema::dropIfExists('recursos_instituicoes');
        Schema::dropIfExists('recursos_gestoras');
        Schema::dropIfExists('recursos_administrativas');
        Schema::dropIfExists('movimentos_instituicoes');
        Schema::dropIfExists('movimentos_gestoras');
        Schema::dropIfExists('movimentos_administrativas');
        Schema::dropIfExists('grupos_fontes');
        Schema::dropIfExists('especificacoes');
        Schema::dropIfExists('fontes_tipos');

    }
}

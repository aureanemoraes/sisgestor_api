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
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Pessoas
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf');
            $table->string('logradouro')->nullable();
            $table->string('cargo')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('complemento')->nullable();
            $table->string('filiacao_1')->nullable();
            $table->string('filiacao_2')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('senha');
            $table->string('perfil');
            $table->unsignedBigInteger('pessoa_id');
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
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
            $table->tinyInteger('aprovado')->default(0);
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Unidades Gestoras
        Schema::create('unidades_gestoras', function (Blueprint $table) {
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
            $table->unsignedBigInteger('diretor_geral_id');
            $table->foreign('diretor_geral_id')->references('id')->on('pessoas');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Unidades Administrativas
        Schema::create('unidades_administrativas', function (Blueprint $table) {
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
        // Tabela de GruposFontes
        Schema::create('grupos_fontes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Espeficacoes
        Schema::create('especificacoes', function (Blueprint $table) {
            $table->id()->from(0);
            $table->string('nome');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
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
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('acoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Naturezas Despesas
        Schema::create('naturezas_despesas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo');
            $table->string('tipo');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Subnaturezas Despesas
        Schema::create('subnaturezas_despesas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo');
        $table->unsignedBigInteger('natureza_despesa_id');
            $table->foreign('natureza_despesa_id')->references('id')->on('naturezas_despesas');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Recursos Instituições
        Schema::create('recursos_instituicoes', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Recursos Gestoras
        Schema::create('recursos_gestoras', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->unsignedBigInteger('recurso_instituicao_id');
            $table->foreign('recurso_instituicao_id')->references('id')->on('recursos_instituicoes');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Recursos Administrativas
        Schema::create('recursos_administrativas', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->unsignedBigInteger('recurso_gestora_id');
            $table->foreign('recurso_gestora_id')->references('id')->on('recursos_gestoras');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Movimentos Instituições
        Schema::create('movimentos_instituicoes', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->unsignedBigInteger('recurso_instituicao_id');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Recursos Gestoras
        Schema::create('movimentos_gestoras', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->unsignedBigInteger('movimento_instituicao_id');
            $table->foreign('movimento_instituicao_id')->references('id')->on('movimentos_instituicoes');
            $table->unsignedBigInteger('recurso_gestora_id');
            $table->foreign('recurso_gestora_id')->references('id')->on('recursos_gestoras');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
        // Tabela de Recursos Administrativas
        Schema::create('movimentos_administrativas', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->unsignedBigInteger('movimento_gestora_id');
            $table->foreign('movimento_gestora_id')->references('id')->on('movimentos_gestoras');
            $table->unsignedBigInteger('recurso_admistrativa_id');
            $table->foreign('recurso_admistrativa_id')->references('id')->on('recursos_administrativas');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            // $table->unsignedBigInteger('usuario_exclusao_id');
            // $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->softDeletes();
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

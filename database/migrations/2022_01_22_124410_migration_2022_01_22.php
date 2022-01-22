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
            $table->json('logs');
            $table->unsignedBigInteger('instituicao_id');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->unsignedBigInteger('unidade_gestora_id');
            $table->foreign('unidade_gestora_id')->references('id')->on('unidades_gestoras');
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
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class LivrosTable extends Migration
{
    public const ISBN_LEN = 13;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->string('ISBN', SELF::ISBN_LEN);
            $table->string('titulo');
            $table->string('categoria')->nullable();
            $table->string('autor')->nullable();
            $table->tinyInteger('qtd_exemplares', unsigned: true);

            $table->unique('ISBN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};

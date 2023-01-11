<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigos_postales', function (Blueprint $table) {
            $table->id();

            $table->string('d_codigo')->index();                    // zip_code
            $table->string('d_ciudad')->index()->nullable()->default(null);  // locality

            $table->index(['d_codigo', 'd_ciudad']);

            $table->foreignId('entidad_federativa_id')->constrained('entidades_federativas');
            $table->foreignId('municipio_id')->constrained('municipios');

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
        Schema::dropIfExists('codigos_postales');
    }
};

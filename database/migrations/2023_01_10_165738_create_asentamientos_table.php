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
        Schema::create('asentamientos', function (Blueprint $table) {
            $table->id();

            $table->string('id_asenta_cpcons')->index();     // key
            $table->string('d_asenta')->index();             // name
            $table->string('d_zona')->index();               // zone_type
            $table->string('d_tipo_asenta')->index();        // settlement_type

            $table->index(['id_asenta_cpcons', 'd_asenta', 'd_zona']);

                // $table->string('c_tipo_asenta'); // With Enum
                // $table->string('d_tipo_asenta'); // With Enum

            $table->foreignId('codigo_postal_id')->constrained('codigos_postales');

            // $table->foreignId('entidad_federativa_id')->constrained('entidades_federativas');
            // $table->foreignId('municipio_id')->constrained('municipios');

            // not reported
            // $table->string('d_CP');
            // $table->string('c_oficina');
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
        Schema::dropIfExists('asentamientos');
    }
};

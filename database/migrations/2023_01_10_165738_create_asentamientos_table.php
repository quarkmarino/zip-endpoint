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

            $table->integer('id_asenta_cpcons')->index();   // key
            $table->string('d_asenta')->index();            // name
            $table->string('d_zona', 12);                   // zone_type
            $table->string('d_tipo_asenta', 32);            // settlement_type

            $table->foreignId('codigo_postal_id')->constrained('codigos_postales');

            $table->index(['id_asenta_cpcons', 'd_asenta', 'd_zona', 'd_tipo_asenta'], 'cpcons_asenta_zona_tipo');

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

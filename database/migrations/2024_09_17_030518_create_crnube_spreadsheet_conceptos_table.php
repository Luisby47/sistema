<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crnube_spreadsheet_conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreign('id_empleado')->references('id')
                ->on('hr_employee')->onDelete('cascade');
            $table->string('tipo_concepto',3);
            $table->string('tipo_valor',4);
            $table->string('motivo');
            $table->bigInteger('valor');
            $table->text('observaciones');
            $table->check("tipo_concepto in ('ING','DED')");
            $table->check("tipo_valor in ('MONT','PORC')");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_conceptos');
    }
};

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
            $table->string('tipo_concepto',3);
            $table->string('tipo_valor',4);
            $table->string('motivo');
            $table->decimal('valor',13,2);
            $table->text('observaciones');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE crnube_spreadsheet_conceptos ADD CONSTRAINT chk_tipo_concepto CHECK (tipo_concepto IN ('ING', 'DED'))");
        DB::statement("ALTER TABLE crnube_spreadsheet_conceptos ADD CONSTRAINT chk_tipo_valor CHECK (tipo_valor IN ('MONT', 'PORC'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE crnube_spreadsheet_conceptos DROP CONSTRAINT chk_tipo_concepto");
        DB::statement("ALTER TABLE crnube_spreadsheet_conceptos DROP CONSTRAINT chk_tipo_valor");
        Schema::dropIfExists('crnube_spreadsheet_conceptos');
    }
};

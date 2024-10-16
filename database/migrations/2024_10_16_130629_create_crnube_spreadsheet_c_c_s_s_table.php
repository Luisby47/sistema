<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crnube_spreadsheet_c_c_s_s', function (Blueprint $table) {
            $table->id(); // ID automático
            $table->decimal('tax_range', 10, 2); // Columna para Rangos Impuesto
            $table->decimal('col_range', 10, 2); // Columna para Rango Col
            $table->decimal('percentage',5, 2); // Columna para el porcentaje
            $table->timestamps(); // timestamps para created_at y updated_at
        });
        DB::table('crnube_spreadsheet_c_c_s_s')->insert([
            ['tax_range' => 929000.00, 'col_range' => 929000.00, 'percentage' => 0.00],
            ['tax_range' => 1363000.00, 'col_range' => 1363000.00, 'percentage' => 10.00],
            ['tax_range' => 2392000.00, 'col_range' => 2392000.00, 'percentage' => 15.00],
            ['tax_range' => 4783000.00, 'col_range' => 4783000.00, 'percentage' => 20.00],
            ['tax_range' => 4783000.00, 'col_range' => 4783000.00, 'percentage' => 25.00],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_c_c_s_s');
    }
};

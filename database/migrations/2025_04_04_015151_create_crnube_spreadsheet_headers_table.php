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
        Schema::create('crnube_spreadsheet_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('res_company')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('number');
            $table->enum('type' , ['MENSUAL', 'QUINCENAL', 'SEMANAL']);
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('status', ['Cerrada', 'Pendiente'])->default('Cerrada');
            $table->text('notes')->nullable();
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_headers');
    }
};

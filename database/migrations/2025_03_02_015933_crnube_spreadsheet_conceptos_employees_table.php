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
        Schema::create('crnube_spreadsheet_conceptos_employees', function (Blueprint $table) {

            $table->primary(['concept_id','employee_id']);

            // Add foreign key constraints if needed
            $table->foreignId('employee_id')
                ->constrained('hr_employee')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('concept_id')
                ->constrained('crnube_spreadsheet_conceptos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('value', 13, 2);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_conceptos_employees');
    }
};

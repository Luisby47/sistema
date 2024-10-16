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
            $table->id();
            $table->string('name'); // Column for the payment type/name
            $table->decimal('value', 5, 2); // Column for the payment value in percentage
            $table->timestamps();
        });
        DB::table('crnube_spreadsheet_c_c_s_s')->insert([
            ['name' => 'Pago CCSS Empleado', 'value' => 10.67],
            ['name' => 'Pago CCSS Patrono', 'value' => 26.67],
            ['name' => 'INS', 'value' => 4.00],
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

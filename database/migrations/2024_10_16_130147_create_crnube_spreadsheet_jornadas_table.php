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
        Schema::create('crnube_spreadsheet_jornadas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days');
            $table->timestamps();
        });

        DB::table( 'crnube_spreadsheet_jornadas' )->insert([
            ['id' => 1, 'name' => 'Semanal', 'days' => 31, 'created_at' => now(), 'updated_at' => now(),],
            ['id' => 2, 'name' => 'Quincenal', 'days' => 15, 'created_at' => now(), 'updated_at' => now(),],
            ['id' => 3, 'name' => 'Mensual', 'days' => 7, 'created_at' => now(), 'updated_at' => now(),],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_jornadas');
    }
};

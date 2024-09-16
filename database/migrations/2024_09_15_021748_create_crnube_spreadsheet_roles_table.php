<?php

use App\Models\CrnubeSpreadsheetRole;
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
        // Crear la tabla 'crnube_spreadsheet_roles' en la base de datos
        Schema::create('crnube_spreadsheet_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        // Cuando se ejectute la migración, se creará un rol 'Super Admin' por defecto
        DB::table('crnube_spreadsheet_roles')->insert([
            'id' => CrnubeSpreadsheetRole::DEFAULT_ROLE_ID,
            'name' => 'Super Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_roles');
    }
};

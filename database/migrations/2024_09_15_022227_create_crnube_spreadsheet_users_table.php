<?php

use App\Models\CrnubeSpreadsheetRole;
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
        Schema::create('crnube_spreadsheet_users', function (Blueprint $table) {
            // Las siguiente dos lineas son necesarias para que el id de la tabla 'res_users' sea el mismo que el id de la tabla 'crnube_spreadsheet_users'
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('res_users')->onDelete('cascade');

            $table->string('email' , 190)->unique();
            $table->string('password');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('role_id')
                ->default(CrnubeSpreadsheetRole::DEFAULT_ROLE_ID)
                ->constrained('crnube_spreadsheet_roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crnube_spreadsheet_users');
    }
};

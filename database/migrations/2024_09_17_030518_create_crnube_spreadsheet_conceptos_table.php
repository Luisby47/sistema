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
        Schema::create('crnube_spreadsheet_conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type',3);
            $table->string('value_type',4);
            $table->decimal('value',13,2);
            $table->text('note');
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

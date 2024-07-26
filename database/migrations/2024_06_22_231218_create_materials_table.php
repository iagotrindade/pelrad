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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('images')->nulable();
            $table->string('record_number')->nullable();
            $table->string('patrimony_number')->nullable();
            $table->string('patrimony_value')->nullable();
            $table->string('inclusion_document')->nullable();
            $table->timestamp('inclusion_date')->nullable();
            $table->string('name');
            $table->string('serial_number');
            $table->text('description')->nullable();
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

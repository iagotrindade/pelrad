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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('graduation')->nullable();
            $table->string('name')->nullable();
            $table->string('idt')->nullable();
            $table->string('contact')->nullable();
            $table->longText('materials_info');
            $table->mediumText('loan_material_base_data');
            $table->timestamp('return_date');
            $table->string('status');
            $table->string('file');
            $table->string('signed_file')->nullable();
            $table->string('return_file')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

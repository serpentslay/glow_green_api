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
        Schema::create('boilers', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->foreignId('boiler_manufacturer_id')->constrained('manufacturers');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('manufacturer_part_number');
            $table->foreignId('fuel_type_id')->constrained('fuel_types');
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boilers');
    }
};

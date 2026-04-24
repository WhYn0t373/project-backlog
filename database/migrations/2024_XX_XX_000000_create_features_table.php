<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the features table.
 */
class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the 'features' table with an auto-incrementing id,
     * a unique name, an optional description, and timestamps.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the 'features' table.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
}
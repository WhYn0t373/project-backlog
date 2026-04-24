<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the items table.
 *
 * The table contains an auto-incrementing primary key,
 * a name string, an optional description text field,
 * and timestamp columns for created_at and updated_at.
 */
class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the items table.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
}
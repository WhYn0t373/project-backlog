<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for adding indexes on products and orders tables.
 *
 * @package Database\Migrations
 */
class AddIndexesToProductsAndOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Index for products.category_id
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                throw new \Exception('The products table does not have a category_id column.');
            }

            $table->index('category_id', 'idx_products_category_id');
        });

        // Indexes for orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                throw new \Exception('The orders table does not have a user_id column.');
            }

            if (!Schema::hasColumn('orders', 'created_at')) {
                throw new \Exception('The orders table does not have a created_at column.');
            }

            $table->index('user_id', 'idx_orders_user_id');
            $table->index('created_at', 'idx_orders_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_category_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_id');
            $table->dropIndex('idx_orders_created_at');
        });
    }
}
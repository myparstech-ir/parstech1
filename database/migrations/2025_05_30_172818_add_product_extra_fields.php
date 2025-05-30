<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductExtraFields extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock_alert')) {
                $table->integer('stock_alert')->default(1)->after('stock');
            }
            if (!Schema::hasColumn('products', 'min_order_qty')) {
                $table->integer('min_order_qty')->default(1)->after('stock_alert');
            }
            if (!Schema::hasColumn('products', 'expire_date')) {
                $table->string('expire_date')->nullable()->after('min_order_qty');
            }
            if (!Schema::hasColumn('products', 'added_at')) {
                $table->string('added_at')->nullable()->after('expire_date');
            }
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('added_at');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock_alert')) {
                $table->dropColumn('stock_alert');
            }
            if (Schema::hasColumn('products', 'min_order_qty')) {
                $table->dropColumn('min_order_qty');
            }
            if (Schema::hasColumn('products', 'expire_date')) {
                $table->dropColumn('expire_date');
            }
            if (Schema::hasColumn('products', 'added_at')) {
                $table->dropColumn('added_at');
            }
            if (Schema::hasColumn('products', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
}

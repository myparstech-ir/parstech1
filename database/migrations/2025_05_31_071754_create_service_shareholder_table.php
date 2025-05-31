<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // اگر جدول service_shareholder وجود نداشت، آن را بساز
        if (!Schema::hasTable('service_shareholder')) {
            Schema::create('service_shareholder', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id');
                $table->unsignedBigInteger('person_id');
                $table->float('percent')->default(0);
                $table->timestamps();

                // اگر جدول services وجود دارد، رابطه ایجاد شود
                if (Schema::hasTable('services')) {
                    $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
                }
                // اگر جدول people وجود دارد، رابطه ایجاد شود
                if (Schema::hasTable('people')) {
                    $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('service_shareholder')) {
            Schema::dropIfExists('service_shareholder');
        }
    }
};

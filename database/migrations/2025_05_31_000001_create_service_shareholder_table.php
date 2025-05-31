<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service_shareholder')) {
            Schema::create('service_shareholder', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id');
                $table->unsignedBigInteger('person_id');
                $table->float('percent')->default(0);
                $table->timestamps();

                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
                $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
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

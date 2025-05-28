<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_table_for_tenant', function (Blueprint $table) {
            $table->id();
            $table->string('test');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_table_for_tenant');
    }
};

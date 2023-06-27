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
        Schema::create('card_info', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number');
            $table->string('month');
            $table->string('year');
            $table->string('cvv');
            $table->integer('card_holder_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_info');
    }
};

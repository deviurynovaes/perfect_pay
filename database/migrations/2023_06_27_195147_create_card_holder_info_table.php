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
        Schema::create('card_holder_info', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document');
            $table->string('email');
            $table->string('zipcode');
            $table->string('address_number');
            $table->string('address_complement')->nullable();
            $table->string('phone_number');
            $table->string('mobile_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_holder_info');
    }
};

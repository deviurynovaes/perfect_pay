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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->decimal('value');
            $table->date('due_date');
            $table->string('billing_type');
            $table->string('description')->nullable();
            $table->string('remote_ip')->nullable();
            $table->integer('installmentCount')->nullable();
            $table->decimal('installmentValue')->nullable();
            $table->integer('card_holder_id')->nullable();
            $table->integer('card_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};

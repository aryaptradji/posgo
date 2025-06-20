<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code')->unique();
            $table->dateTime('time');
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('arrived_at')->nullable();
            $table->string('category');
            $table->string('payment_status')->default('belum dibayar');
            $table->string('shipping_status')->default('belum dikirim');
            $table->string('photo')->nullable();
            $table->integer('item');
            $table->bigInteger('total');
            $table->bigInteger('paid')->default(0);
            $table->bigInteger('change')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

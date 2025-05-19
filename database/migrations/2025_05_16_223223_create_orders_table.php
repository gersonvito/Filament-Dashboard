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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId("warehouse_id")
                ->nullable()
                ->nullOnDelete();

            $table->foreignId("customer_id")

                ->nullable()
                ->nullOnDelete();

            $table->foreignId("user_id")
              
                ->nullable()
                ->nullOnDelete();

            $table->decimal("total");

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

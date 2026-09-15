<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained('flash_sales')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->decimal('sale_price', 10, 2);
            $table->timestamps();

            $table->unique(['flash_sale_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};

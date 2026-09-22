<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_variant_values', function (Blueprint $table) {
            $table->foreignId('item_variant_id')
                ->constrained('item_variants')
                ->cascadeOnDelete();

            $table->foreignId('item_attribute_value_id')
                ->constrained('item_attribute_values')
                ->cascadeOnDelete();

            $table->primary(['item_variant_id', 'item_attribute_value_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_variant_values');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_attribute_type_id')
                ->constrained('item_attribute_types')
                ->cascadeOnDelete();
            $table->string('value');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['item_attribute_type_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_attribute_values');
    }
};

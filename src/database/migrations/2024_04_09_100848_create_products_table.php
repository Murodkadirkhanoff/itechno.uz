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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz');
            $table->string('name_ru');
            $table->text('description_uz');
            $table->text('description_ru');
            $table->float('price');
            $table->float('discount_price');
            $table->float('discount_percent');
            $table->integer('in_stock');
            $table->string('artikul')->nullable();
            $table->foreignId('category_id');
            $table->foreignId('brand_id')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

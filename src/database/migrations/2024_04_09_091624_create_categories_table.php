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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz');
            $table->string('name_ru');

            $table->string('icon')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Self-relation
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('parent_id')
                ->references('id')->on('categories')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

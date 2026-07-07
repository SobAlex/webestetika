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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable()->comment('Описание категории');
            $table->string('color')->nullable()->comment('Цвет категории');
            $table->string('icon')->nullable()->comment('Иконка категории');
            $table->boolean('is_active')->default(true)->comment('Активна');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};

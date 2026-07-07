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
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Заголовок');
            $table->text('description')->nullable()->comment('Описание');
            $table->string('image')->nullable()->comment('Изображение');
            $table->string('button_text')->nullable()->comment('Текст кнопки');
            $table->boolean('is_active')->default(true)->comment('Активен');
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
        Schema::dropIfExists('hero_sections');
    }
};

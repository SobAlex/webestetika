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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable()->comment('Краткое описание');
            $table->text('content')->comment('Основной контент');
            $table->string('image')->nullable()->comment('Изображение статьи');
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // SEO поля
            $table->string('meta_title')->nullable()->comment('SEO заголовок');
            $table->text('meta_description')->nullable()->comment('SEO описание');

            // Статус и сортировка
            $table->boolean('is_published')->default(false)->comment('Опубликована');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            $table->timestamp('published_at')->nullable()->comment('Дата публикации');

            $table->timestamps();

            $table->index(['is_published', 'published_at']);
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};

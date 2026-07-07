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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->comment('Краткое описание');
            $table->text('content')->nullable()->comment('Основной контент');
            $table->string('icon')->nullable()->comment('Иконка услуги');
            $table->string('color')->nullable()->comment('Цвет услуги');
            $table->string('image')->nullable()->comment('Изображение услуги');

            // Цена
            $table->decimal('price_from', 10, 2)->nullable()->comment('Цена от');
            $table->string('price_type')->nullable()->comment('Тип цены (руб/мес, руб/проект)');

            // Особенности
            $table->text('features')->nullable()->comment('Особенности услуги');

            // SEO поля
            $table->string('meta_title')->nullable()->comment('SEO заголовок');
            $table->text('meta_description')->nullable()->comment('SEO описание');
            $table->string('meta_keywords')->nullable()->comment('SEO ключевые слова');

            // Статус и сортировка
            $table->boolean('is_published')->default(false)->comment('Опубликована');
            $table->boolean('is_featured')->default(false)->comment('Рекомендуемая');
            $table->boolean('show_on_homepage')->default(false)->comment('Показывать на главной');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');

            // Связанные услуги
            $table->foreignId('related_service_1_id')->nullable()->constrained('services')->onDelete('set null');
            $table->foreignId('related_service_2_id')->nullable()->constrained('services')->onDelete('set null');
            $table->foreignId('related_service_3_id')->nullable()->constrained('services')->onDelete('set null');

            // Связанные статьи
            $table->foreignId('related_article_1_id')->nullable()->constrained('blogs')->onDelete('set null');
            $table->foreignId('related_article_2_id')->nullable()->constrained('blogs')->onDelete('set null');
            $table->foreignId('related_article_3_id')->nullable()->constrained('blogs')->onDelete('set null');

            // Связанные кейсы
            $table->foreignId('related_case_1_id')->nullable()->constrained('cases')->onDelete('set null');
            $table->foreignId('related_case_2_id')->nullable()->constrained('cases')->onDelete('set null');
            $table->foreignId('related_case_3_id')->nullable()->constrained('cases')->onDelete('set null');

            $table->timestamps();

            $table->index(['is_published', 'sort_order']);
            $table->index('show_on_homepage');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

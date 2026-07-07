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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question')->comment('Вопрос');
            $table->text('answer')->comment('Ответ');
            $table->boolean('is_active')->default(true)->comment('Активен');
            $table->boolean('show_on_homepage')->default(true)->comment('Показывать на главной');
            $table->boolean('show_on_services')->default(true)->comment('Показывать на страницах услуг');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index('show_on_homepage');
            $table->index('show_on_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};

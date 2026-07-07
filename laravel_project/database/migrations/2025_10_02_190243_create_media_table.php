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
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('filename'); // Имя файла на диске
                $table->string('original_name'); // Оригинальное имя файла
                $table->string('mime_type'); // MIME тип файла
                $table->bigInteger('size'); // Размер файла в байтах
                $table->string('path'); // Путь к файлу в storage
                $table->string('url'); // URL для доступа к файлу
                $table->string('alt_text')->nullable(); // Альтернативный текст для изображений
                $table->text('description')->nullable(); // Описание файла
                $table->timestamps();
            });
        } else {
            // Добавляем недостающие колонки если таблица уже существует
            Schema::table('media', function (Blueprint $table) {
                if (!Schema::hasColumn('media', 'alt_text')) {
                    $table->string('alt_text')->nullable()->after('url');
                }
                if (!Schema::hasColumn('media', 'description')) {
                    $table->text('description')->nullable()->after('alt_text');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

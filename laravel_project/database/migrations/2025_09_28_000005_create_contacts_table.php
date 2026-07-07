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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->comment('Email');
            $table->string('phone')->nullable()->comment('Телефон');
            $table->text('address')->nullable()->comment('Адрес');
            $table->string('working_hours')->nullable()->comment('Часы работы');

            // Социальные сети
            $table->string('social_telegram')->nullable()->comment('Telegram');
            $table->string('social_whatsapp')->nullable()->comment('WhatsApp');
            $table->string('social_vk')->nullable()->comment('VKontakte');
            $table->string('social_instagram')->nullable()->comment('Instagram');
            $table->string('social_youtube')->nullable()->comment('YouTube');

            $table->boolean('is_active')->default(true)->comment('Активна');
            $table->timestamps();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

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
        Schema::table('cases', function (Blueprint $table) {
            $table->string('service_link_text')->nullable()->after('sort_order')->comment('Текст ссылки на услугу');
            $table->string('service_link_url')->nullable()->after('service_link_text')->comment('URL ссылки на услугу');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn(['service_link_text', 'service_link_url']);
        });
    }
};

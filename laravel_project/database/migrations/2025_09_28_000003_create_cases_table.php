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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_id')->unique()->comment('Уникальный идентификатор кейса');
            $table->string('title');
            $table->string('client')->comment('Клиент');
            $table->string('period')->comment('Период работы');
            $table->string('image')->nullable()->comment('Изображение кейса');
            $table->text('description')->comment('Описание кейса');
            $table->text('before_after')->nullable()->comment('До и после');
            $table->string('service_key')->nullable()->comment('Ключ услуги');
            $table->boolean('is_published')->default(false)->comment('Опубликован');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('industry_category_id')->nullable()->constrained()->onDelete('set null');

            // SEO поля
            $table->string('meta_title')->nullable()->comment('SEO заголовок');
            $table->text('meta_description')->nullable()->comment('SEO описание');
            $table->text('content')->nullable()->comment('Основной контент');

            // Метрики "до"
            $table->string('traffic_before')->nullable();
            $table->string('keywords_before')->nullable();
            $table->string('conversion_before')->nullable();
            $table->string('revenue_before')->nullable();
            $table->string('appointments_before')->nullable();
            $table->string('calls_before')->nullable();
            $table->string('leads_before')->nullable();
            $table->string('cost_per_lead_before')->nullable();
            $table->string('mobile_traffic_before')->nullable();
            $table->string('repeat_clients_before')->nullable();
            $table->string('enrollments_before')->nullable();
            $table->string('time_on_site_before')->nullable();
            $table->string('local_traffic_before')->nullable();
            $table->string('map_views_before')->nullable();
            $table->string('reservations_before')->nullable();
            $table->string('avg_check_before')->nullable();
            $table->string('b2b_traffic_before')->nullable();
            $table->string('large_orders_before')->nullable();
            $table->string('avg_project_before')->nullable();
            $table->string('orders_before')->nullable();
            $table->string('inquiries_before')->nullable();
            $table->string('sales_before')->nullable();
            $table->string('cost_per_sale_before')->nullable();
            $table->string('avg_order_before')->nullable();
            $table->string('catalog_conversion_before')->nullable();
            $table->string('brand_traffic_before')->nullable();
            $table->string('product_views_before')->nullable();

            // Метрики "после"
            $table->string('traffic_after')->nullable();
            $table->string('keywords_after')->nullable();
            $table->string('conversion_after')->nullable();
            $table->string('revenue_after')->nullable();
            $table->string('appointments_after')->nullable();
            $table->string('calls_after')->nullable();
            $table->string('leads_after')->nullable();
            $table->string('cost_per_lead_after')->nullable();
            $table->string('mobile_traffic_after')->nullable();
            $table->string('repeat_clients_after')->nullable();
            $table->string('enrollments_after')->nullable();
            $table->string('time_on_site_after')->nullable();
            $table->string('local_traffic_after')->nullable();
            $table->string('map_views_after')->nullable();
            $table->string('reservations_after')->nullable();
            $table->string('avg_check_after')->nullable();
            $table->string('b2b_traffic_after')->nullable();
            $table->string('large_orders_after')->nullable();
            $table->string('avg_project_after')->nullable();
            $table->string('orders_after')->nullable();
            $table->string('inquiries_after')->nullable();
            $table->string('sales_after')->nullable();
            $table->string('cost_per_sale_after')->nullable();
            $table->string('avg_order_after')->nullable();
            $table->string('catalog_conversion_after')->nullable();
            $table->string('brand_traffic_after')->nullable();
            $table->string('product_views_after')->nullable();

            // Дополнительные поля
            $table->text('metrics')->nullable()->comment('Дополнительные метрики');
            $table->text('results')->nullable()->comment('Результаты');

            $table->timestamps();

            $table->index(['is_published', 'sort_order']);
            $table->index('industry_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};

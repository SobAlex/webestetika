@extends('layouts.email')

@section('title', $subject ?? 'Заказ услуги с сайта')

@section('content')
    <div class="bg-white shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-cyan-600 text-white px-6 py-4">
            <h1 class="text-2xl font-bold">🎯 Заказ услуги</h1>
            <p class="text-cyan-100 mt-1">{{ $serviceName ?? 'Не указано' }}</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Имя клиента</span>
                        <p class="text-lg font-semibold text-gray-900">{{ $name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email</span>
                        <p class="text-lg font-semibold text-gray-900">{{ $email ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Телефон</span>
                        <p class="text-lg font-semibold text-gray-900">{{ $phone ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Дата заказа</span>
                        <p class="text-lg font-semibold text-gray-900">{{ now()->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

                @if (!empty($messageBody))
                    <div class="border-t border-gray-200 pt-4">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Сообщение от клиента</span>
                        <div class="mt-2 p-4 bg-gray-50 ">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $messageBody }}</p>
                        </div>
                    </div>
                @endif

                @if (!empty($attachmentName))
                    <div class="border-t border-gray-200 pt-4">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Прикрепленный файл</span>
                        <div class="mt-2 flex items-center p-3 bg-blue-50 border border-blue-200 ">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            <span class="text-blue-700 font-medium">{{ $attachmentName }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <p class="text-sm text-gray-500 text-center">
                Это автоматическое уведомление с сайта {{ config('app.name') }}
            </p>
        </div>
    </div>
@endsection

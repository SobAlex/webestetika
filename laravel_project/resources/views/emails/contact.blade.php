@extends('layouts.email')

@section('title', $subject ?? 'Сообщение с сайта')

@section('content')
    <div class="bg-white shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 text-white px-6 py-4">
            <h1>📧 Сообщение с сайта</h1>
            <p class="mt-1">{{ $subject ?? 'Новое сообщение' }}</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="uppercase tracking-wide">Имя</span>
                        <p>{{ $name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="uppercase tracking-wide">Email</span>
                        <p>{{ $email ?? '-' }}</p>
                    </div>
                    @if (!empty($phone))
                        <div>
                            <span class="uppercase tracking-wide">Телефон</span>
                            <p>{{ $phone }}</p>
                        </div>
                    @endif
                    <div>
                        <span class="uppercase tracking-wide">Дата отправки</span>
                        <p>{{ now()->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

                @if (!empty($messageBody))
                    <div class="border-t border-gray-200 pt-4">
                        <span class="uppercase tracking-wide">Сообщение</span>
                        <div class="mt-2 p-4 bg-gray-50">
                            <p class="whitespace-pre-wrap">{{ $messageBody }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <p class="text-center">
                Это автоматическое уведомление с сайта {{ config('app.name') }}
            </p>
        </div>
    </div>
@endsection

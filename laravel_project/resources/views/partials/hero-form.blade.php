<form action="{{ route('contact.hero') }}" method="POST"
      class="flex flex-col gap-4 w-full lg:flex-row lg:space-x-4 lg:space-y-0 lg:items-end lg:justify-start mt-8">
    @csrf

    @php
        $idSuffix = $idSuffix ?? '';
        $buttonText = $buttonText ?? 'Отправить заявку';
        $buttonAriaLabel = $buttonAriaLabel ?? $buttonText;
    @endphp

    <div class="flex-1">
        <label for="name_hero{{ $idSuffix }}" class="block mb-1">Имя:</label>
        <input type="text" id="name_hero{{ $idSuffix }}" name="name" required placeholder="Ваше имя"
               class="w-full px-3 py-2 rounded-lg" aria-required="true" aria-label="Ваше имя"
               aria-invalid="@if (isset($errors) && $errors->has('name')) true @else false @endif"
               aria-describedby="name_hero{{ $idSuffix }}_error" />
        @if (isset($errors) && $errors->has('name'))
            <p id="name_hero{{ $idSuffix }}_error" class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
        @endif
    </div>

    <div class="flex-1">
        <label for="phone_hero{{ $idSuffix }}" class="block mb-1">Телефон:</label>
        <input type="tel" id="phone_hero{{ $idSuffix }}" name="phone" required placeholder="+7 (999) 999-99-99"
               class="w-full px-3 py-2 rounded-lg" aria-required="true" aria-label="Телефон"
               aria-invalid="@if (isset($errors) && $errors->has('phone')) true @else false @endif"
               aria-describedby="phone_hero{{ $idSuffix }}_error" />
        @if (isset($errors) && $errors->has('phone'))
            <p id="phone_hero{{ $idSuffix }}_error" class="mt-1 text-sm text-red-600">{{ $errors->first('phone') }}</p>
        @endif
    </div>

    <button type="submit" class="btn whitespace-nowrap px-6 py-2 self-start lg:self-auto"
            aria-label="{{ $buttonAriaLabel }}">
        {{ $buttonText }}
    </button>
</form>

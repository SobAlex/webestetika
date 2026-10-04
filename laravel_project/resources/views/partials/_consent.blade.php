{{-- Блок согласия на обработку персональных данных --}}
@php
    $consentId = $consentId ?? uniqid('consent-');
@endphp
<p class="my-4 text-sm leading-snug">
    <span class="inline-flex items-start gap-2.5">
        <input type="checkbox" id="{{ $consentId }}" class="consent-checkbox mt-0.5 size-[18px] shrink-0"
            name="consent" value="1" required @checked(old('consent'))>
        <span>
            <label for="{{ $consentId }}">Я даю согласие на обработку моих персональных данных в соответствии с</label>
            <a href="{{ route('soglasie') }}" target="_blank" rel="noopener" class="text-blue-600 underline">Согласием на обработку персональных данных</a>
        </span>
    </span>
</p>

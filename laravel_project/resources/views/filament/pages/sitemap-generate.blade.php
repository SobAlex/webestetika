<form method="POST" action="{{ route('sitemap.generate') }}">
    @csrf
    <x-filament::button type="submit">
        Сгенерировать sitemap.xml
    </x-filament::button>
</form>

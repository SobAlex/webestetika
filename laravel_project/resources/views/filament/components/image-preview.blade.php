@if($record && $record->image)
    @php
        $url = \Storage::disk('public')->url($record->image);
    @endphp
    <div class="mt-4">
        <img src="{{ $url }}" alt="Current Image" class="h-auto max-h-[200px] max-w-xs rounded-lg">
        <p class="text-sm text-gray-500 mt-2">Current image: {{ basename($record->image) }}</p>
    </div>
@else
    <p class="text-gray-500 mt-4">❌ No image uploaded</p>
@endif

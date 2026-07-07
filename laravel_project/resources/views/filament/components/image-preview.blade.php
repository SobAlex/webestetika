@if($record && $record->image)
    @php
        $url = \Storage::disk('public')->url($record->image);
    @endphp
    <div class="mt-4">
        <img src="{{ $url }}" alt="Current Image" class="max-w-xs h-auto rounded-lg" style="max-height: 200px;">
        <p class="text-sm text-gray-500 mt-2">Current image: {{ basename($record->image) }}</p>
    </div>
@else
    <p class="text-gray-500 mt-4">❌ No image uploaded</p>
@endif

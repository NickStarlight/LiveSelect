@props(['description'])

@if ($description)
    <label id="listbox-label" class="block text-sm font-medium text-gray-700">
        {{ $description }}
    </label>
@endif
@props(['model', 'selected', 'label'])

<button 
    x-on:click="
        open = !open
        if ($wire.searchMode) {
            $nextTick(() => { $refs.searchInput.focus() })
        }
    "
    type="button" 
    aria-haspopup="listbox" 
    aria-expanded="true" 
    aria-labelledby="listbox-label"
    class="@error($model) border-red-300 @enderror relative w-full py-2 pl-5 pr-10 text-left bg-white border border-gray-300 rounded-md shadow-sm cursor-default focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-opacity-40 sm:text-sm"
    style="z-index: 900;"
    >
    <span class="flex justify-between">
        @if (sizeof($selected) === 0)
            <span class="text-gray-200 truncate">
                {{ __('Nothing selected') }}
            </span>
        @else
            <span class="max-w-xs truncate">
                {{ $selected[0][$label] }}
            </span>
        @endif

        @if (sizeof($selected) > 1)
            <span class="px-1 text-blue-600 truncate rounded-sm ring-1 ring-blue-600 ring-opacity-25">
                +{{ sizeof($selected) - 1 }}
            </span>
        @endif
    </span>

    <span class="absolute inset-y-0 right-0 flex items-center pr-2 ml-3 pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
            fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </span>
</button>
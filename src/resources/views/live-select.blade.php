<div>
    {{-- Start: Input Label --}}
    <x-live-select::input-label :description="$description" />
    {{-- End: Input Label --}}

    <div 
        x-data="{ open: false }"
        x-on:click.away="open = false"
        x-on:close-event="open = false"
        class="relative mt-1">

        {{-- Start: Input Button Trigger --}}
        <x-live-select::input-button :model="$model" :selected="$selected" :label="$label" />
        {{-- End: Input Button Trigger --}}

        <div 
            x-show="open"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute w-full mt-1 bg-white"
            style="display: none; z-index: 999;">
            
            {{-- Start: Search Input --}}
            @if ($searchMode)
                <input
                    x-ref="searchInput"
                    wire:model='searchText'
                    class="w-full py-2 pl-5 mt-1 ring-1 ring-black ring-opacity-10 rounded-t-md sm:text-sm focus:outline-none" 
                    type="text" 
                    placeholder="{{ __('Type to search') }}">
            @endif
            {{-- End: Search Input --}}
            
            {{-- Start: Options List --}}
            <ul 
                tabindex="-1" 
                role="listbox" 
                aria-labelledby="listbox-label"
                class="py-1 overflow-auto text-base shadow-md {{ $searchMode ? 'rounded-b-md' : 'rounded-md' }} max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                @if (sizeof($sortedOptions) === 0)
                    <li class="relative px-2 py-2 text-gray-900 cursor-default select-none pr-9">
                        <div class="flex items-center">
                            <span class="block ml-3 font-normal text-gray-200 truncate">
                                {{ __('No options available.') }}
                            </span>
                        </div>
                    </li>
                @else
                    @foreach($sortedOptions as $key => $option)
                        <li 
                            id="{{ $option[$value] }}"
                            wire:key="{{ $loop->index }}"
                            x-data="{ value: {{ var_export($option[$value]) }} }"
                            x-on:click="
                                if ($wire.multiMode !== true) {
                                    $dispatch('close-event')
                                }

                                $wire.updateSelectedOptions(value)
                            "
                            role="option"
                            class="relative z-50 px-2 py-2 text-gray-900 cursor-default select-none pr-9 hover:bg-gray-100">
                            @if (in_array($option[$value], array_column($selected, $value)))
                                <div class="flex items-center">
                                    <span class="block ml-3 font-normal text-blue-900 truncate">
                                        {{ $option[$label] }}
                                    </span>
                                </div>

                                <span class="absolute inset-y-0 right-0 flex items-center pr-4">
                                    <!-- Heroicon name: check -->
                                    <svg class="w-5 h-5 text-blue-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <div class="flex items-center">
                                    <span class="block ml-3 font-normal truncate">
                                        {{ $option[$label] }}
                                    </span>
                                </div>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>
            {{-- End: Options List --}}
        </div>
    </div>

    {{-- Start: Validation Errors --}}
    @error($model) 
        <p class='mt-2 text-sm text-red-600'>{{ $message }}</p>
    @enderror
    {{-- End: Validation Errors --}}
</div>

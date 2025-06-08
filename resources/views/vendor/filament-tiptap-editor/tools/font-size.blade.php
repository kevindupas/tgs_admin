{{-- resources/views/vendor/filament-tiptap-editor/tools/font-size.blade.php --}}

<div class="relative" x-data="{ open: false }">
    <x-filament-tiptap-editor::button label="Taille de police" action="" @click="open = !open">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
    </x-filament-tiptap-editor::button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 w-32 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700"
        style="display: none;">
        <div class="py-1 max-h-48 overflow-y-auto">
            @php
                $sizes = [
                    '8px',
                    '9px',
                    '10px',
                    '11px',
                    '12px',
                    '14px',
                    '16px',
                    '18px',
                    '24px',
                    '30px',
                    '36px',
                    '48px',
                    '60px',
                    '72px',
                ];
            @endphp

            @foreach ($sizes as $size)
                <button type="button"
                    @click="
                        editor().commands.setFontSize('{{ $size }}');
                        open = false;
                    "
                    class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white"
                    style="font-size: {{ $size }}">
                    {{ $size }}
                </button>
            @endforeach

            <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>

            <button type="button"
                @click="
                    editor().commands.unsetFontSize();
                    open = false;
                "
                class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                Défaut
            </button>
        </div>
    </div>
</div>

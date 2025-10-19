<div class="flex items-center ms-6">
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
            <img src="{{ asset('images/flags/' . (app()->getLocale() === 'ar' ? 'sa' : 'gb') . '.svg') }}"
                alt="{{ app()->getLocale() === 'ar' ? 'Arabic' : 'English' }}" class="w-6 h-4 me-2">
            <span class="text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</span>
            <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
            <a href="{{ route('language.switch', 'en') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                {{-- <img src="{{ asset('images/flags/gb.svg') }}" alt="English" class="w-6 h-4 me-2"> --}}
                <span>English</span>
            </a>
            <a href="{{ route('language.switch', 'ar') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                {{-- <img src="{{ asset('images/flags/sa.svg') }}" alt="العربية" class="w-6 h-4 me-2"> --}}
                <span>العربية</span>
            </a>
        </div>
    </div>
</div>

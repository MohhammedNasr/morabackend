<div class="language-switcher flex items-center space-x-2">
    <a href="{{ route('language.switch', 'en') }}"
        class="px-3 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
        English
    </a>
    <a href="{{ route('language.switch', 'ar') }}"
        class="px-3 py-1 rounded {{ app()->getLocale() == 'ar' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
        العربية
    </a>
</div>

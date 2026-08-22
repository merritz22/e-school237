@php
    $helpWhatsapp = '657384102';
@endphp

<a href="https://wa.me/{{ config('subscriptions.country_code') }}{{ $helpWhatsapp }}"
   target="_blank"
   rel="noopener noreferrer"
   title="{{ __('app.nav.help') }}"
   class="fixed bottom-24 right-5 z-40 flex items-center justify-center
       w-14 h-14 rounded-full bg-{{ config('theme.primary') }}-600 shadow-lg
       hover:scale-105 active:scale-95 transition-transform duration-200">
    <flux:icon name="question-mark-circle" class="w-7 h-7 text-white" />
</a>

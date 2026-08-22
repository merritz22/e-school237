@php
    $adminWhatsapp = \App\Models\User::where('role', 'admin')
        ->where('email', config('mail.contact_address'))
        ->value('whatsapp');
@endphp

@if($adminWhatsapp)
    <a href="https://wa.me/{{ config('subscriptions.country_code') }}{{ $adminWhatsapp }}"
       target="_blank"
       rel="noopener noreferrer"
       title="{{ __('app.profile.contact.whatsapp_admin') }}"
       class="fixed bottom-5 right-5 z-40 flex items-center justify-center
           w-14 h-14 rounded-full bg-[#25d366] shadow-lg
           hover:scale-105 active:scale-95 transition-transform duration-200">
        <span class="absolute inline-flex h-full w-full rounded-full bg-[#25d366] opacity-75 animate-ping"></span>
        <svg class="relative w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="#fff" d="M123 393l14-65a138 138 0 1150 47z"/>
            <path fill="#25d366" d="M308 273c-3-2-6-3-9 1l-12 16c-3 2-5 3-9 1-15-8-36-17-54-47-1-4 1-6 3-8l9-14c2-2 1-4 0-6l-12-29c-3-8-6-7-9-7h-8c-2 0-6 1-10 5-22 22-13 53 3 73 3 4 23 40 66 59 32 14 39 12 48 10 11-1 22-10 27-19 1-3 6-16 2-18"/>
        </svg>
    </a>
@endif

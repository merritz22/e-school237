
@props(['title', 'description'])

<div class="bg-gradient-to-br from-[#03386a] to-[#196fc0] text-white p-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                {{ $title }}
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                {{ $description }}
            </p>
        </div>
    </div>
</div>
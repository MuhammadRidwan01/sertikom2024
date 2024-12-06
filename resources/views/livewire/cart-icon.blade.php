<div class="relative w-full">
    <button wire:click="$dispatch('openCart')" class="flex items-center justify-center w-full px-2 py-1 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out">

        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
    </button>
    @if($cartCount > 0)
        <span wire:poll.keep-alive class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center animate-pulse">
            {{ $cartCount }}
        </span>
    @endif
</div>

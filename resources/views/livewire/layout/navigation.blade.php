<?php
use Livewire\Volt\Component;
use App\Livewire\Actions\Logout;

new class extends Component {
    public string $userName;
    public string $userEmail;

    public function mount(): void {
        $this->userName = auth()->user()->name;
        $this->userEmail = auth()->user()->email;
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(): void {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        // Redirect after logout
        $this->redirect('/');
    }
};
?>

<nav class="fixed left-0 h-[88dvh] top-0 bottom-0 w-64 bg-white dark:bg-gray-800 shadow-lg overflow-y-auto py-6 px-4 space-y-2">
    <div class="text-2xl font-bold text-gray-800 dark:text-white ">
        Library Xpro
    </div>

    <!-- Dashboard Link -->
    @if(auth()->user()->role === 'admin')
        <x-nav-link :href="route('admin.dashboard')"
            :active="request()->routeIs('admin.dashboard')"
            class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-700 font-semibold' : '' }}"
            wire:navigate>
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </x-nav-link>

        <!-- Admin Menu Links -->
        <div class="space-y-1">
            <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2">
                Admin Menu
            </h3>

            <x-nav-link :href="route('admin.admin-book-management')"
                :active="request()->routeIs('admin.admin-book-management')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200"
                wire:navigate>
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Menu Lemari
            </x-nav-link>

            <x-nav-link :href="route('admin.users')"
                :active="request()->routeIs('admin.users')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200"
                wire:navigate>
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Menu Anggota
            </x-nav-link>

            <x-nav-link :href="route('admin.admin-book-loan')"
                :active="request()->routeIs('admin.admin-book-loan')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200"
                wire:navigate>
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                List Buku Dipinjam
            </x-nav-link>

            <x-nav-link :href="route('admin.admin-categories')"
                :active="request()->routeIs('admin.admin-categories')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200"
                wire:navigate>
                Management Kategori
            </x-nav-link>
        </div>
    @endif
        <div class="space-y-1">
            <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2">
                Member Menu
            </h3>

            <x-nav-link :href="route('member.dashboard')"
                :active="request()->routeIs('member.dashboard')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200 {{ request()->routeIs('member.dashboard') ? 'bg-gray-100 dark:bg-gray-700 font-semibold' : '' }}"
                wire:navigate>
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-lienjoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </x-nav-link>

            <x-nav-link :href="route('member.book-management')"
                :active="request()->routeIs('member.book-management')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200 {{ request()->routeIs('member.book-management') ? 'bg-gray-100 dark:bg-gray-700 font-semibold' : '' }}"
                wire:navigate>
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                  </svg>
                Daftar Buku
            </x-nav-link>

            <x-nav-link :href="route('member.books')"
                :active="request()->routeIs('member.books')"
                class="flex w-full items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200"
                wire:navigate>
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                  </svg>

                Peminjaman
            </x-nav-link>
        </div>

    <!-- Utility Section -->
    <div class="space-y-1 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
            Utilities
        </h3>

        <div class=" hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200">
            <livewire:cart-icon />
        </div>

        <button wire:click="logout"
                class="w-full flex items-center py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
        </button>
    </div>
</nav>

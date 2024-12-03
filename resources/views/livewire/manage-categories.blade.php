<div x-data="{ isModalOpen: false, isConfirmModalOpen: false }" class="p-6 bg-gray-100 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Kategori</h2>

    <button @click="isModalOpen = true" class="mb-6 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
        <i class="fas fa-plus mr-2"></i>Tambah Kategori
    </button>

    <!-- Add Category Modal -->
    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center" style="display: none;">
        <div @click.away="isModalOpen = false" class="bg-white p-8 rounded-lg shadow-2xl w-11/12 md:w-1/3 transform transition-all duration-300 ease-in-out" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Kategori Buku</h2>

            <form wire:submit.prevent="storeCategory">
                <div class="mb-4">
                    <label for="nama_kategori" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                    <input
                        type="text"
                        id="nama_kategori"
                        wire:model="nama_kategori"
                        placeholder="Masukkan nama kategori"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                    >
                    @error('nama_kategori')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button
                        type="button"
                        @click="isModalOpen = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                    >
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    @if($isConfirmModalOpen)
    <div x-show="isConfirmModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div @click.away="isConfirmModalOpen = false" class="bg-white p-8 rounded-lg shadow-2xl w-11/12 md:w-1/3 transform transition-all duration-300 ease-in-out" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Konfirmasi Hapus Kategori</h2>

            <p class="mb-4 text-gray-600">Apakah Anda yakin ingin menghapus kategori "<span class="font-semibold">{{ $categoryToDelete->nama_kategori }}</span>"?</p>
            <p class="text-red-500 text-sm mb-6">Catatan: Buku dengan kategori ini akan dikosongkan kategorinya.</p>

            <div class="flex justify-end space-x-3">
                <button
                    @click="isConfirmModalOpen = false"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                >
                    Batal
                </button>
                <button
                    wire:click="deleteCategory"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Daftar Kategori -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Daftar Kategori</h3>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @forelse($categories->where('status', 'active') as $category)
                <li class="flex justify-between items-center p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <span class="text-gray-800 font-medium">{{ $category->nama_kategori }}</span>
                    <button
                        @click="isConfirmModalOpen = true; $wire.openDeleteConfirmModal({{ $category->id }})"
                        class="text-red-500 hover:text-red-700 transition duration-300 ease-in-out focus:outline-none"
                    >
                    Hapus
                    </button>
                </li>
                @empty
                <li class="p-4 text-gray-500 text-center">Tidak ada kategori aktif.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://kit.fontawesome.com/dfb31a0ba6.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modal', () => ({
            isModalOpen: false,
            isConfirmModalOpen: false,
        }));
    });
</script>
@endpush

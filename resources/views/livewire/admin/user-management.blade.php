<div x-data="{
    showModal: @entangle('showModal'),
    selectedUsers: @entangle('selectedUsers'),
    selectAll: @entangle('selectAll'),
}"
    class="container mx-auto p-6 bg-white border-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">


        <div class="flex flex-col md:flex-row justify-between items-center p-6 border-white  ">
            <h1 class="text-2xl font-bold text-black dark:text-white  mb-4 md:mb-0">Manajemen Anggota</h1>
            <div class="flex items-center space-x-4">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search users..."
                    class="form-input w-full md:w-64 px-4 py-2 rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                >
                <button
                    wire:click="openCreateModal"
                    class="bg-blue-500 rounded p-2 text-white"
                >Tambah User
                </button>
                <div x-show="selectedUsers.length > 0" >
                    <button
                        wire:click="deleteSelectedUsers"
                        class=" bg-red-500 rounded p-2 text-white"
                    >
                     Hapus user ({{ count($selectedUsers) }})
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="p-3 text-left">
                            <label class="inline-flex items-center">
                                <input
                                    type="checkbox"
                                    x-model="selectAll"
                                    wire:model.live="selectAll"
                                    class="form-checkbox h-5 w-5 text-blue-600 rounded"
                                >
                                <span class="ml-2 text-gray-700 dark:text-gray-300">All</span>
                            </label>
                        </th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="p-3">
                                <input
                                    type="checkbox"
                                    value="{{ $user->id }}"
                                    x-model="selectedUsers"
                                    class="form-checkbox h-5 w-5 text-blue-600 rounded"
                                >
                            </td>
                            <td class="p-3 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                            <td class="p-3 text-gray-800 dark:text-gray-200">{{ $user->email }}</td>
                            <td class="p-3">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="p-3 space-x-2">
                                <button
                                    wire:click="openEditModal({{ $user->id }})"
                                    class=" bg-blue-400 p-1 rounded text-white"
                                >Ubah
                                </button>
                                <button
                                    wire:click="deleteUser({{ $user->id }})"
                                    class=" bg-red-500 p-1 rounded text-white"
                                >Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>


    <!-- User Modal -->
    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md" @click.away="showModal = false">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">
                {{ $editMode ? 'Edit User' : 'Create User' }}
            </h2>
            <form wire:submit.prevent="saveUser">
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="form-input w-full px-4 py-2 rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Email</label>
                    <input
                        type="email"
                        wire:model="email"
                        class="form-input w-full px-4 py-2 rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Password</label>
                    <input
                        type="password"
                        wire:model="password"
                        class="form-input w-full px-4 py-2 rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                        {{ $editMode ? '' : 'required' }}
                    >
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Role</label>
                    <select
                        wire:model="role"
                        class="form-select w-full px-4 py-2 rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="member">Member</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="btn-secondary"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('user-saved', () => {
                Alpine.store('showModal', false);
            });
        });
    </script>

    <style>
        .btn-primary {
            @apply px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200;
        }
    </style>

</div>


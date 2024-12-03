<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UserManagement extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $search = '';
    public $selectedUsers = [];
    public $selectAll = false;
    public $showModal = false;
    public $editMode = false;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'member';
    public $userId = null;

    protected $queryString = ['search'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'nullable|min:8',
        'role' => 'required|in:admin,member'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->getFilteredUsers()->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEditModal(User $user)
    {
        $this->resetForm();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->showModal = true;
        $this->editMode = true;
    }

    public function saveUser()
    {
        try {
            if ($this->editMode) {
                $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
            }

            $validatedData = $this->validate();

            if ($this->editMode) {
                $user = User::findOrFail($this->userId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'role' => $this->role,
                ]);

                if ($this->password) {
                    $user->password = Hash::make($this->password);
                    $user->save();
                }
            } else {
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role' => $this->role,
                ]);
            }

            $this->resetForm();
            $this->alert('success', 'User di rubah.');
            $this->dispatch('user-saved');
        } catch (\Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
        $this->alert('success', 'User dihapus.');
    }

    public function deleteSelectedUsers()
    {
        User::whereIn('id', $this->selectedUsers)->delete();
        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->alert('success', 'Banyak user di hapus.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId', 'showModal', 'editMode']);
        $this->resetValidation();
    }

    private function getFilteredUsers()
    {
        return User::where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%");
    }

    public function render()
    {
        $users = $this->getFilteredUsers()->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }
}

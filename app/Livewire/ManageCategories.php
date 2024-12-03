<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Book;
use Livewire\Component;
use App\Models\Category;

class ManageCategories extends Component
{
    use LivewireAlert;

    public $nama_kategori = '';
    public $categories;
    public $isModalOpen = false;
    public $isConfirmModalOpen = false;
    public $categoryToDelete = null;

    public function mount()
    {
        $this->refreshCategories();
    }

    public function refreshCategories()
    {
        $this->categories = Category::where('status', 'active')->get();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset('nama_kategori');
    }

    public function storeCategory()
    {
        $this->validate([
            'nama_kategori' => 'required|min:3|unique:categories,nama_kategori'
        ]);

        Category::create([
            'nama_kategori' => $this->nama_kategori,
            'status' => 'active'
        ]);

        $this->closeModal();
        $this->refreshCategories();
        $this->alert('success', "Category berhasil ditambahkan!");
    }

    public function openDeleteConfirmModal($id)
    {
        $this->categoryToDelete = Category::findOrFail($id);
        $this->isConfirmModalOpen = true;
    }

    public function closeConfirmModal()
    {
        $this->isConfirmModalOpen = false;
        $this->categoryToDelete = null;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            $this->categoryToDelete->update([
                'status' => 'deleted'
            ]);

            // Book::where('kategori', $this->categoryToDelete->id)
            //     ->update(['kategori' => null]);

            $this->refreshCategories();
            $this->closeConfirmModal();
            $this->alert('success', "Category berhasil dihapus!");
        }
    }

    public function render()
    {
        return view('livewire.manage-categories');
    }
}

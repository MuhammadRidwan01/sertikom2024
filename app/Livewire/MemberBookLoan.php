<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Loan;
use Livewire\Component;
use App\Livewire\CartIcon;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class MemberBookLoan extends Component
{
    use LivewireAlert;
    use WithPagination;

    public $tanggal_kembali;
    public $selectedBookId;
    public $searchTerm = '';
    public $userLoans;
    public $todayDate;

    public function mount()
    {
        $this->loadUserLoans();
        $this->todayDate = now()->format('Y-m-d');
    }

    #[On('books-updated')]
    public function refreshBooksAndLoans()
    {
        $this->loadUserLoans();
        $this->render();
    }

    public function loadUserLoans()
    {
        $this->userLoans = auth()->user()->loans()
            ->with('book')
            ->latest()
            ->get();
    }

    public function selectBook($bookId)
    {
        $this->selectedBookId = $bookId;
    }

    public function pinjamBuku()
    {
        try {
            $this->validate([
                'selectedBookId' => 'required|exists:books,id',
                'tanggal_kembali' => 'required|date|after:today',
            ]);

            $book = Book::findOrFail($this->selectedBookId);
            if ($book->category->status !== 'active' || $book->status !== 'tersedia') {
                throw new \Exception('Buku ini tidak tersedia.');
            }

            Loan::validateLoan(auth()->id(), $this->selectedBookId);

            $loan = Loan::create([
                'user_id' => auth()->id(),
                'book_id' => $this->selectedBookId,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => $this->tanggal_kembali,
                'status_peminjaman' => 'aktif'
            ]);

            $book->update(['status' => 'dipinjam']);
            $this->dispatch('books-updated');
            $this->loadUserLoans();
            $this->dispatch('notify', ['message' => 'Buku berhasil dipinjam!']);
            $this->reset(['tanggal_kembali', 'selectedBookId']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => $e->getMessage()]);
        }
    }

    public function tambahKeKeranjang()
    {
        try {
            $this->validate([
                'selectedBookId' => 'required|exists:books,id',
                'tanggal_kembali' => 'required|date|after:today',
            ]);

            $book = Book::findOrFail($this->selectedBookId);
            if ($book->category->status !== 'active' || $book->status !== 'tersedia') {
                throw new \Exception('Buku ini tidak tersedia.');
            }

            $existingCartItem = Cart::where('user_id', auth()->user()->id)
                ->where('book_id', $this->selectedBookId)
                ->first();

            if ($existingCartItem) {
                throw new \Exception('Buku ini sudah ada di keranjang.');
            }

            $duration = now()->diffInDays($this->tanggal_kembali);
            $totalPrice = $book->harga * $duration;

            Cart::create([
                'user_id' => auth()->user()->id,
                'book_id' => $this->selectedBookId,
                'tanggal_kembali' => $this->tanggal_kembali,
                'duration' => $duration,
                'total_price' => $totalPrice,
            ]);

            $this->reset(['tanggal_kembali', 'selectedBookId']);
            $this->dispatch('cart-updated');
            $this->alert('success', 'Buku berhasil ditambahkan ke keranjang!');
        } catch (\Throwable $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function kembalikanBuku($loanId)
    {
        try {
            $loan = Loan::findOrFail($loanId);
            $denda = $loan->returnBook();

            if ($denda > 0) {
                $this->alert('warning', "Buku dikembalikan dengan denda Rp. " . number_format($denda, 0, ',', '.'));
            } else {
                $this->alert('success', 'Buku dikembalikan tanpa denda.');
            }

            $this->loadUserLoans();
            $this->dispatch('books-updated');
        } catch (\Exception $e) {
            $this->alert('error', 'Gagal mengembalikan buku: '. $e->getMessage());
        }
    }

    public function render()
    {
        $books = Book::with('category')
            ->whereHas('category', function ($q) {
                $q->where('status', 'active');
            })
            ->where('status', 'tersedia')
            ->when($this->searchTerm, function ($query) {
                return $query->where(function ($q) {
                    $q->where('judul_buku', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('penulis', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->paginate(6);

        return view('livewire.member-book-loan', [
            'books' => $books,
            'userLoans' => $this->userLoans
        ]);
    }
}

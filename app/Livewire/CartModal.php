<?php

namespace App\Livewire;

use Midtrans\Snap;
use App\Models\Cart;
use App\Models\Loan;
use Midtrans\Config;
use Livewire\Component;
use App\Models\transaction;
use Livewire\Attributes\On;
use App\Livewire\MemberBookLoan;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class CartModal extends Component
{
    use LivewireAlert;
    public $isOpen = false;
    public $cartItems;
    public $totalPrice = 0;
    public $user;

    #[On('openCart')]
    public function open()
    {
        $this->isOpen = true;
        $this->loadCartItems();
    }

public function mount()
{
    $this->user = auth()->user();
}

    public function loadCartItems()
    {
        $this->cartItems = auth()->user()->cartItems()->with('book')->get();
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        $this->totalPrice = $this->cartItems->sum('total_price');
    }

    public function removeFromCart($cartItemId)
    {
        Cart::destroy($cartItemId);
        $this->loadCartItems();
        $this->alert('success', 'Item berhasil dihapus.');
        $this->dispatch('cart-updated');
    }

    public function checkout()
    {
        try {
            DB::beginTransaction();

            foreach ($this->cartItems as $cartItem) {
                $tanggalKembali = now()->addDays($cartItem->duration);

                Loan::create([
                    'user_id' => auth()->id(),
                    'book_id' => $cartItem->book_id,
                    'tanggal_pinjam' => now(),
                    'tanggal_kembali' => $tanggalKembali,
                    'status_peminjaman' => 'pending'
                ]);

                $cartItem->book->update(['status' => 'pending']);
                $cartItem->delete();
            }

            DB::commit();
            $this->loadCartItems();
            $this->isOpen = false;
            $this->dispatch('cart-updated');
            $this->dispatch('books-updated');
            $this->alert('success', 'Checkout berhasil! Buku telah dipinjam.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            $this->alert('error', 'Buku tidak ditemukan.');
        }
    }

//     public function checkout()
// {
//     try {
//         DB::beginTransaction();

//         // Buat transaksi terlebih dahulu
//         $transaction = transaction::create([
//             'user_id' => auth()->id(),
//             'total_amount' => $this->totalPrice,
//             'status' => 'pending'
//         ]);

//         // Konfigurasi Midtrans
//         Config::$serverKey = config('midtrans.server_key');
//         Config::$isProduction = config('midtrans.is_production');
//         Config::$isSanitized = config('midtrans.is_sanitized');
//         Config::$is3ds = config('midtrans.is_3ds');

//         $items = [];
//         foreach ($this->cartItems as $cartItem) {
//             $price = $cartItem->book->harga_sewa;
//             $items[] = [
//                 'id' => $cartItem->book_id,
//                 'price' => $price,
//                 'quantity' => 1,
//                 'name' => $cartItem->book->judul_buku
//             ];
//         }

//         $params = [
//             'transaction_details' => [
//                 'order_id' => $transaction->id,
//                 'gross_amount' => $this->totalPrice,
//             ],
//             'item_details' => $items,
//             'customer_details' => [
//                 'first_name' => auth()->user()->name,
//                 'email' => auth()->user()->email,
//                 'phone' => auth()->user()->phone ?? '081234567890',
//             ]
//         ];

//         // Dapatkan Snap Token dari Midtrans
//         $snapToken = Snap::getSnapToken($params);
//         $transaction->update(['snap_token' => $snapToken]);

//         // Proses peminjaman buku
//         foreach ($this->cartItems as $cartItem) {
//             Loan::create([
//                 'user_id' => auth()->id(),
//                 'book_id' => $cartItem->book_id,
//                 'tanggal_pinjam' => now(),
//                 'tanggal_kembali' => now()->addDays($cartItem->duration),
//                 'status_peminjaman' => 'aktif'
//             ]);

//             $cartItem->book->update(['status' => 'dipinjam']);
//             $cartItem->delete();
//         }

//         DB::commit();
//         $this->loadCartItems();
//         $this->isOpen = false;
//         $this->dispatch('cart-updated');
//         $this->dispatch('books-updated');

//         return redirect()->to('/payment/' . $transaction->id);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         dd($e->getMessage());
//         return null;
//     }
// }

    public function render()
    {
        return view('livewire.cart-modal');
    }
}


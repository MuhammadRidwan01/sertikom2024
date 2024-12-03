<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartIcon extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    #[On('cart-updated')]
    public function updateCartCount()
    {
        $this->cartCount = auth()->user()->cartItems()->count();
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}

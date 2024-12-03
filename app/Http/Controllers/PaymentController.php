<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show($id)
    {
        $transaction = transaction::findOrFail($id);
        return view('payment', compact('transaction'));
    }
}

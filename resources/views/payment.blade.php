<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
                        <div class="container">
                            <h1>Pembayaran</h1>
                            <p>Total: Rp {{ number_format($transaction->total_amount, 2) }}</p>
                            <button id="pay-button">Bayar Sekarang</button>
                        </div>

                        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
                        </script>
                        <script>
                            document.getElementById('pay-button').onclick = function() {
                                snap.pay('{{ $transaction->snap_token }}', {
                                    onSuccess: function(result) {
                                        alert("Pembayaran berhasil!");
                                        console.log(result);
                                    },
                                    onPending: function(result) {
                                        alert("Pembayaran tertunda!");
                                        console.log(result);
                                    },
                                    onError: function(result) {
                                        alert("Pembayaran gagal!");
                                        console.log(result);
                                    }
                                });
                            };
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

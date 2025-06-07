<x-layout-main>
    <x-slot:title>Detail Pesanan</x-slot:title>

    <div class="mx-14 mt-32">
        <div class="text-3xl font-bold mb-6">Detail Pesanan</div>
        <div class="shadow-outer p-6 rounded-xl">
            <div class="mb-10 font-semibold">Order ID : {{ $order->code }}</div>
            <div class="flex gap-6 mb-6">
                @foreach ($order->items as $item)
                    <div class="flex gap-4">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="max-h-20">
                        <div>
                            <span class="block">{{ $item->product->name }}</span>
                            <span class="block">{{ $item->product->pcs }} pcs</span>
                            <span class="block">{{ $item->qty }}x</span>
                            <span class="block">Rp {{ $item->qty * $item->price }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-button-sm id="btn-pay" type="button"
                class="bg-secondary-blue text-white w-fit py-2 px-6">Bayar</x-button-sm>
        </div>
    </div>

    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('btn-pay');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    // window.location.href = '/success';
                    window.location.href = "{{ route('customer.order.index', ['status' => 'dikemas']) }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('customer.order.index', ['status' => 'belum-dibayar']) }}";
                },
                onError: function(result) {
                    alert('Terjadi kesalahan saat pembayaran.');
                    console.error(result);
                    window.location.href = "{{ route('customer.order.index', ['status' => 'belum-dibayar']) }}";
                },
                onClose: function() {
                    window.location.href = "{{ route('customer.order.index', ['status' => 'belum-dibayar']) }}";
                }
            });
        });
    </script>
</x-layout-main>

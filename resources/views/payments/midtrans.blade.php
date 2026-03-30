<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Kost</title>
    <script src="{{ config('midtrans.snap_url') }}"
            data-client-key="{{ config('SB-Mid-client-0svOnWVYf5XUY8e9') }}">
    </script>
</head>
<body>
    <h2>Pembayaran Kost</h2>
    <p>Order ID: {{ $payment->order_id }}</p>
    <p>Total: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
    <p>Keterangan: {{ $payment->notes }}</p>

    <button id="pay-button">Bayar Sekarang</button>

    <script>
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $payment->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = '{{ route("payment.success") }}';
                },
                onPending: function (result) {
                    window.location.href = '{{ route("payment.pending") }}';
                },
                onError: function (result) {
                    window.location.href = '{{ route("payment.failed") }}';
                },
                onClose: function () {
                    alert('Pembayaran dibatalkan.');
                }
            });
        };
    </script>
</body>
</html>

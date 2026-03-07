@extends('layout')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Pesanan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="status"><strong>Status</strong></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="alamat_pemesanan"><strong>Alamat Pemesanan</strong></label>
                                <input type="text" name="alamat_pemesanan" id="alamat_pemesanan" class="form-control" value="{{ $order->alamat_pemesanan }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="metode_pengiriman"><strong>Metode Pengiriman</strong></label>
                                <input type="text" name="metode_pengiriman" id="metode_pengiriman" class="form-control" value="{{ $order->metode_pengiriman }}">
                            </div>

                            <div class="form-group mb-4">
                                <label for="notes"><strong>Catatan</strong></label>
                                <textarea name="notes" id="notes" class="form-control" rows="4">{{ $order->notes }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

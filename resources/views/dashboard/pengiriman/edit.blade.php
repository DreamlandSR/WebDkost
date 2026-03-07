@extends('layouts.app')
@section('content')
    <div class="container">
        <h3>Edit Pengiriman</h3>

        <form action="{{ route('pengiriman.update', $pengiriman->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Order ID</label>
                <select name="order_id" class="form-control" required>
                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}" {{ $order->id == $pengiriman->order_id ? 'selected' : '' }}>
                            {{ $order->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Status Pengiriman</label>
                <select name="status_pengiriman" class="form-control" required>
                    @foreach (['diproses', 'dikirim', 'dalam perjalanan', 'sampai'] as $status)
                        <option value="{{ $status }}"
                            {{ $pengiriman->status_pengiriman == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Nomor Resi</label>
                <input type="text" name="nomor_resi" class="form-control" value="{{ $pengiriman->nomor_resi }}">
            </div>

            <div class="mb-3">
                <label>Jasa Kurir</label>
                <input type="text" name="jasa_kurir" class="form-control" value="{{ $pengiriman->jasa_kurir }}">
            </div>

            <div class="mb-3">
                <label>Tanggal Dikirim</label>
                <input type="datetime-local" name="tanggal_dikirim" class="form-control"
                    value="{{ \Carbon\Carbon::parse($pengiriman->tanggal_dikirim)->format('Y-m-d\TH:i') }}">
            </div>

            <div class="mb-3">
                <label>Tanggal Sampai</label>
                <input type="datetime-local" name="tanggal_sampai" class="form-control"
                    value="{{ \Carbon\Carbon::parse($pengiriman->tanggal_sampai)->format('Y-m-d\TH:i') }}">
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="2">{{ $pengiriman->catatan }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pengiriman.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

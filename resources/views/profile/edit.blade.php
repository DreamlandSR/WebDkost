@extends('layout')

@section('content')
<div class="container mt-5">
    <h3>Edit Profil</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <!-- Foto Profil -->
        <div class="mb-3 text-center">
            <img src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : asset('img/default-avatar.png') }}"
                 class="rounded-circle"
                 style="width: 100px; height: 100px; object-fit: cover;"
                 alt="Avatar">

            <div class="mt-2">
                <input type="file" name="avatar" accept="image/*" class="form-control-file">
            </div>
        </div>

        <!-- Nama -->
        <div class="mb-3">
            <label for="name">Nama</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- Tombol -->
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection

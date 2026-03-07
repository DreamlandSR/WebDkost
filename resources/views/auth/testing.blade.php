<form method="POST" action="{{ route('password.reset', ['email' => $email]) }}">
    @csrf
    <label>Password Baru:</label>
    <input type="password" name="password" required>

    <label>Konfirmasi Password Baru:</label>
    <input type="password" name="password_confirmation" required>

    @error('password')
        <span>{{ $message }}</span>
    @enderror

    <button type="submit">Reset Password</button>
</form>

@extends('vendor.twofactorauth.auth.layout.app')

@section('title', 'Login')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Bem-vindo de volta!</h2>

    @if (session('error'))
        <div class="mb-4">
            <div class="bg-red-500 text-white p-4 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="login-form">
        @csrf
        <input type="email" name="email" placeholder="E-mail" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror

        <input type="password" name="password" placeholder="Senha" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror

        <button type="submit">Entrar</button>
    </form>

    <p class="message">
        <a href="#" class="text-blue-500 hover:underline">Esqueceu a senha?</a>
    </p>
@endsection

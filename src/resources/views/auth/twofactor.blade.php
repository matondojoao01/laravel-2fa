@extends('vendor.twofactorauth.auth.layout.app')

@section('title', 'Verificação de Código de Segurança')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Verificação do Código de Segurança</h2>
    <p class="text-gray-600 mb-4">Para acessar o sistema, por favor, insira o código de segurança que foi enviado para o seu e-mail.</p>
    
    @if($msg == 'invalid_code')
        <div class="mb-4">
            <div class="bg-red-500 text-white p-4 rounded">
                Código inválido. Tente novamente.
            </div>
        </div>
    @endif

    @if($msg == 'user_not_found')
        <div class="mb-4">
            <div class="bg-red-500 text-white p-4 rounded">
                Usuário não encontrado.
            </div>
        </div>
    @endif

    <form action="{{ route('2fa.verify') }}" method="POST" class="login-form">
        @csrf
        <input type="text" name="2fa" placeholder="Código de Autenticação" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">

        <input type="hidden" name="tk" value="{{ $tk }}">
        <input type="hidden" name="id" value="{{ $id }}">
        <input type="hidden" name="username" value="{{ $username }}">

        <button type="submit">Verificar</button>
    </form>
@endsection

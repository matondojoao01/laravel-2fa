<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticação de Dois Fatores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Autenticação de Dois Fatores</h2>
                
                @if($msg == 'invalid_code')
                    <div class="alert alert-danger">Código inválido. Tente novamente.</div>
                @endif

                @if($msg == 'user_not_found')
                    <div class="alert alert-danger">Usuário não encontrado.</div>
                @endif

                <form action="{{ route('2fa.verify') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="2fa" class="form-label">Código de Autenticação</label>
                        <input type="text" class="form-control" name="2fa" required>
                    </div>
                    
                    <input type="hidden" name="tk" value="{{ $tk }}">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="username" value="{{ $username }}">

                    <button type="submit" class="btn btn-primary">Verificar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

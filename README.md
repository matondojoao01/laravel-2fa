# Pacote de Autenticação 2FA

Matondo TwoFactorAuth é um pacote desenvolvido para adicionar autenticação de dois fatores (2FA) a aplicações Laravel. O objetivo inicial do pacote é fornecer uma forma simples de login com autenticação de dois fatores, sem incluir outras etapas de autenticação, como redefinição de senha ou funcionalidades mais avançadas.

# Instalação

Para instalar o pacote, utilize o Composer:

```php
composer require matondo/twofactorauth
```

#  Registro do Service Provider

Após a instalação, registre o TwoFactorAuthServiceProvider no arquivo config/app.php, na seção providers:

```php
'providers' => [
    // Outros providers
   Matondo\TwoFactorServiceProvider::class,
],
```

# Publicação dos Arquivos

Após registrar o TwoFactorAuthServiceProvider, publique os arquivos do pacote:

```php
php artisan vendor:publish --provider="Matondo\TwoFactorServiceProvider"
```

Esse comando irá publicar as seguintes partes do pacote:

- **Models:** As models necessárias para a autenticação de dois fatores.
- **Controllers:** Controladores para gerenciar a lógica da autenticação.
- **Middleware:** Middleware para proteger as rotas que requerem autenticação de dois fatores.
- **Views:** As views necessárias para o processo de autenticação.
- **Rotas:** Um arquivo de rotas que contém as novas rotas para a autenticação de dois fatores.

# Inclusão das Rotas

Depois de registrar o provider e publicar os arquivos, adicione o seguinte comando no arquivo routes/web.php para incluir as novas rotas:

```php
require base_path('routes/twofactorauth.php');
```

# Execução das Migrations

Antes de executar as migrations, certifique-se de configurar as variáveis de ambiente no arquivo .env para que a base de dados não apresente erros ao migrar as tabelas. Isso inclui definir as configurações corretas de conexão com o banco de dados.

Além disso, configure as variáveis de ambiente para garantir que os e-mails sejam enviados corretamente. Verifique as configurações do servidor de e-mail, como MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, e MAIL_ENCRYPTION.

Depois de configurar as variáveis de ambiente, execute as migrations:


```php
php artisan migrate
```

# Registro do Middleware

Registre o middleware TwoFactorVerify no arquivo app/Http/Kernel.php na seção $routeMiddleware:

```php
protected $routeMiddleware = [
    // Outros middlewares
    '2fa' => \App\Http\Middleware\TwoFactorVerify::class,
];
```

# Uso do Middleware nas Rotas

Use o middleware nas rotas que necessitam de autenticação de dois fatores. Por exemplo:

```php
Route::group(['middleware' => '2fa'], function () {
    Route::get('/dashboard', function () {
        // Conteúdo do dashboard
    });
});
```

# Acesso ao Login

Depois de completar todas as etapas acima, você pode acessar a rota /login para iniciar o processo de autenticação.

O pacote gera um código de autenticação que é enviado ao usuário após a entrada inicial com credenciais. O usuário deve inserir esse código para concluir o processo de login, proporcionando uma segurança adicional.

Sinta-se à vontade para ajustar quaisquer partes do texto para atender às suas preferências ou necessidades!
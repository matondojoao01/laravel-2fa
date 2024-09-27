<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Código de Autenticação 2FA</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(90deg, rgba(141,194,111,1) 0%, rgba(118,184,82,1) 50%);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .code {
            font-size: 20px;
            font-weight: bold;
            color: #4CAF50;
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Olá,</h1>
        <p>Seu código de autenticação é:</p>
        <p class="code">{{ $token }}</p>
        <p>Esse código é válido por 10 minutos.</p>
        <div class="footer">
            <p>Se você não solicitou esse código, por favor, ignore este e-mail.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Sistema de Chamados</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg, #8b0000 0%, #c41e3a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .login-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #8b0000;
            margin: 0;
            font-size: 28px;
        }

        .login-header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #8b0000;
            box-shadow: 0 0 5px rgba(139, 0, 0, 0.2);
        }

        .error-message {
            color: #c41e3a;
            padding: 10px;
            background: #fde9e9;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #8b0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #c41e3a;
        }

        .submit-btn:active {
            transform: translateY(1px);
        }

        @media (max-width: 600px) {
            .login-container {
                padding: 30px 20px;
                margin: 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>ServiceDesk</h1>
            <p>Sistema de Chamados</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <strong>Erro:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=entrar">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="submit-btn">Entrar</button>
        </form>
    </div>
</body>
</html>

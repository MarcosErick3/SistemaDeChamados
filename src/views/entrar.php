<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Sistema de Chamados</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/entrar.css?v=1">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-brand">
            <div class="login-brand-content">
                <div class="login-brand-icon">🛠️</div>
                <h2>ServiceDesk</h2>
                <p>Gerenciamento de Chamados</p>
                
                <div class="login-brand-features">
                    <div class="feature">
                        <div class="feature-icon">✓</div>
                        <div>Sistema moderno e intuitivo</div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">✓</div>
                        <div>Rastreamento em tempo real</div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">✓</div>
                        <div>Relatórios e análises</div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">✓</div>
                        <div>Suporte para colaboração</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-form-container">
            <div class="login-form-content">
                <div class="login-header">
                    <h1>Bem-vindo!</h1>
                    <p>Entre com suas credenciais para acessar o sistema</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <strong>❌ Erro:</strong> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=entrar">
                    <div class="form-group">
                        <label for="email">📧 Email</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="senha">🔒 Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
                    </div>

                    <button type="submit" class="submit-btn">Entrar no Sistema</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

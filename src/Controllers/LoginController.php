<?php

class LoginController
{
    private $tecnicoDao;

    public function __construct(TecnicoDAO $tecnicoDao)
    {
        $this->tecnicoDao = $tecnicoDao;
    }

    public function entrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $user = $this->tecnicoDao->authenticate($email, $senha);
            if ($user) {
                session_regenerate_id(true);
                unset($user['senha']);
                $_SESSION['user'] = $user;
                header('Location: index.php?action=perfil');
                exit;
            } else {
                $error = 'Credenciais inválidas';
            }
        }

        require_once __DIR__ . '/../views/entrar.php';
    }

    public function login()
    {
        $this->entrar();
    }

    public function sair()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?action=entrar');
        exit;
    }

    public function logout()
    {
        $this->sair();
    }

    public function perfil()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=entrar');
            exit;
        }

        $user = $_SESSION['user'];
        require_once __DIR__ . '/../views/perfil.php';
    }
}

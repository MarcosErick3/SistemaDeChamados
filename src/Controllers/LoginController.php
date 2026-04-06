<?php

class LoginController
{
    private $tecnicoDao;

    public function __construct(TecnicoDAO $tecnicoDao)
    {
        $this->tecnicoDao = $tecnicoDao;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $user = $this->tecnicoDao->authenticate($email, $senha);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php?action=perfil');
                exit;
            } else {
                $error = 'Credenciais inválidas';
            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public function perfil()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $user = $_SESSION['user'];
        require_once __DIR__ . '/../views/perfil.php';
    }
}
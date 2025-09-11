<?php
session_start();
require_once __DIR__ . '/../Model/User.php';

class AuthController
{
    public function login()
    {
        require_once __DIR__ . '/../View/login.php';
    }

    public function authenticate()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            $isPasswordValid = false;

            if (strpos($user['password'], '$') === 0) {
                $isPasswordValid = password_verify($password, $user['password']);
            } else {
                $isPasswordValid = ($password === $user['password']);
            }

            if ($isPasswordValid) {
                $_SESSION['user'] = $user;
                $userId = $user['id'];

                if ($user['statut'] === 'actif') {
                    $dashboards = [
                        'officier' => 'dashboard',
                        'agent' => 'dashboardAgent',
                        'admin' => 'dashboardAdministrator'
                    ];

                    if (isset($dashboards[$user['role']])) {
                        $page = $dashboards[$user['role']];
                        header("Location: index.php?page={$page}&id=$userId");
                        exit;
                    }
                } else {
                    $error = "Identifiants invalides : vous n'êtes plus autorisé.";
                }
            } else {
                $error = "Identifiants invalides.";
            }
        } else {
            $error = "Identifiants invalides.";
        }

        require_once __DIR__ . '/../View/login.php';
    }
}


    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        require_once __DIR__ . '/../View/officier_page.php';
    }

    public function dashboardAgent()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        require_once __DIR__ . '/../View/list_demand.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    public function dashboardAdministrator()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        require_once __DIR__ . '/../View/administration_page.php';
    }
}

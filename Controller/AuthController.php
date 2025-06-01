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

            if ($user && $password === $user['password']) {
                $_SESSION['user'] = $user;
                $userId = $user['id'];

                if ($user['role'] === 'officier') {
                    header("Location: index.php?page=dashboard&id=$userId");
                } else {
                    header("Location: index.php?page=dashboardAgent&id=$userId");
                }
                exit;
            } else {
                $error = "Identifiants invalides";
                require_once __DIR__ . '/../View/login.php';
            }
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
}

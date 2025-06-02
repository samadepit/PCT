<?php
require_once  __DIR__ . '/../Controller/AuthController.php';

$controller = new AuthController();

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        $controller->login();
        break;
    case 'authenticate':
        $controller->authenticate();
        break;
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'dashboardAgent':
        $controller->dashboardAgent();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        echo "Page non trouvée.";
        break;
}

    <?php
    // Activer l'affichage des erreurs pour le débogage
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    

    // Charger la connexion à la base de données
    require_once 'config/dbconnect.php';

    // Inclure le header (qui contient Bootstrap)
    require_once 'View/partials/header.php';

    // Charger les contrôleurs
    require_once 'Controller/index.php';
    require_once 'Controller/demandController.php';




    // Système de routage simple
    $controller = isset($_GET['controller']) ? $_GET['controller'] : 'index';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    // Instancier le contrôleur
    switch ($controller) {
        case 'demande':
            $controllerInstance = new demandeController();
            break;
        
        default:
            $controllerInstance = new index();
            break;
    }

    // Appeler l'action
    if (method_exists($controllerInstance, $action)) {
        if ($id) {
            $controllerInstance->$action($id);
        } else {
            $controllerInstance->$action();
        }
    } else {
        // Afficher une erreur stylisée avec Bootstrap
        echo '<div class="container mt-5"><div class="alert alert-danger" role="alert">Action non trouvée !</div></div>';
    }

    // Inclure le footer (optionnel)
    require_once 'View/partials/footer.php'; // Crée un footer.php si besoin
    ?>
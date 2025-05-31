<?php
require_once __DIR__ . '/../Controller/decescontroller.php';
require_once __DIR__ . '/../Controller/naissancecontroller.php';

$title = "Demande d'Acte de Décès";
$error = '';

// Récupérer l'id_naissance (par exemple, à partir des données de naissance précédentes)
$naissanceController = new NaissanceController();
$naissance_data = $_SESSION['donnees_actes']['naissance'] ?? null;

if (!$naissance_data) {
    $error = "Erreur : Aucune donnée de naissance trouvée. Veuillez d'abord remplir l'acte de naissance.";
} else {
    // Récupérer l'id_naissance en utilisant NaissanceController
    $id_naissance = $naissanceController->get_existing_birth_id($naissance_data);
    if (!$id_naissance) {
        $error = "Erreur : Impossible de récupérer l'ID de naissance. Assurez-vous que l'acte de naissance est correctement enregistré.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $decesController = new DecesController();

    $data = [
        'date_deces' => $_POST['date_deces'] ?? '',
        'lieu_deces' => $_POST['lieu_deces'] ?? '',
        'cause' => $_POST['cause'] ?? null,
        'genre' => $_POST['genre'] ?? '',
        'profession' => $_POST['profession'] ?? ''
    ];

    $success = $decesController->creerActeDeces($data, $id_naissance);
    if ($success) {
        // Sauvegarder les données dans la session
        $_SESSION['donnees_actes']['deces'] = $data;

        // Rediriger vers l'acte suivant ou finaliser
        if (!empty($_SESSION['actes_restants'])) {
            $acte_suivant = array_shift($_SESSION['actes_restants']);
            switch ($acte_suivant) {
                case 'mariage':
                    header('Location: index.php?controller=demande&action=marriage');
                    exit;
                case 'deces':
                    header('Location: index.php?controller=demande&action=death_certificate');
                    exit;
                default:
                header('Location: index.php?controller=demande&action=final_process');    
            }
        } else {
            header('Location: index.php?controller=demande&action=final_process');
            exit;
        }
    } else {
        $error = "Erreur lors de l'enregistrement de l'acte de décès.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container mt-4">
    <ul class="list-group list-group-horizontal mb-4 justify-content-center">
        <li class="list-group-item">Étape 1 : Choix des Actes</li>
        <li class="list-group-item">Étape 2 : Demandeur</li>
        <li class="list-group-item active bg-primary text-white">
            Étape 3 : <?php echo htmlspecialchars(implode(', ', $_SESSION['actes_restants'] ?? ['Actes restants'])); ?>
        </li>
    </ul>

    <h1 class="text-center mb-4">Demande d'Acte de Décès</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="" class="needs-validation" novalidate>
        <h3>Informations sur le décès</h3>
        <div class="mb-3">
            <label for="date_deces" class="form-label">Date de décès</label>
            <input type="date" class="form-control" id="date_deces" name="date_deces" required>
            <div class="invalid-feedback">Veuillez entrer la date de décès.</div>
        </div>
        <div class="mb-3">
            <label for="lieu_deces" class="form-label">Lieu de décès</label>
            <input type="text" class="form-control" id="lieu_deces" name="lieu_deces" required>
            <div class="invalid-feedback">Veuillez entrer le lieu de décès.</div>
        </div>
        <div class="mb-3">
            <label for="cause" class="form-label">Cause du décès (optionnel)</label>
            <input type="text" class="form-control" id="cause" name="cause">
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select class="form-select" id="genre" name="genre" required>
                <option value="">-- Sélectionner --</option>
                <option value="Masculin">Masculin</option>
                <option value="Féminin">Féminin</option>
                <option value="Autre">Autre</option>
            </select>
            <div class="invalid-feedback">Veuillez sélectionner un genre.</div>
        </div>
        <div class="mb-3">
            <label for="profession" class="form-label">Profession</label>
            <input type="text" class="form-control" id="profession" name="profession" required>
            <div class="invalid-feedback">Veuillez entrer la profession.</div>
        </div>

        <button type="submit" class="btn btn-primary">Passer à l'acte suivant</button>
        <a href="index.php?controller=demande&action=create_step3" class="btn btn-secondary ms-2">Précédent</a>
        <a href="index.php?controller=demande&action=final_process" class="btn btn-success ms-2">Continuer</a>
    </form>

    <script>
        (function () {
            'use strict';
            var form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>
</div>
</body>
</html>



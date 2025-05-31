<?php
require_once __DIR__ . '/../Controller/mariagecontroller.php';
require_once __DIR__ . '/../Controller/naissancecontroller.php';

$title = "Demande d'Acte de Mariage";
$error = '';

// Récupérer les données de naissance pour le mari et la femme
$naissanceController = new NaissanceController();
$naissance_data_mari = $_SESSION['donnees_actes']['naissance_mari'] ?? null;
$naissance_data_femme = $_SESSION['donnees_actes']['naissance_femme'] ?? null;

if (!$naissance_data_mari || !$naissance_data_femme) {
    $error = "Erreur : Les données de naissance pour le mari et la femme sont requises. Veuillez d'abord remplir les actes de naissance correspondants.";
} else {
    // Récupérer les id_naissance pour le mari et la femme
    $id_naissance_mari = $naissanceController->get_existing_birth_id($naissance_data_mari);
    $id_naissance_femme = $naissanceController->get_existing_birth_id($naissance_data_femme);

    if (!$id_naissance_mari || !$id_naissance_femme) {
        $error = "Erreur : Impossible de récupérer les ID de naissance pour le mari ou la femme. Assurez-vous que les actes de naissance sont correctement enregistrés.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $mariageController = new MariageController();

    $data = [
        'id_naissance_mari' => $id_naissance_mari,
        'id_naissance_femme' => $id_naissance_femme,
        'date_mariage' => $_POST['date_mariage'] ?? '',
        'lieu_mariage' => $_POST['lieu_mariage'] ?? '',
        'nombre_enfant' => $_POST['nombre_enfant'] ?? 0,
        'numero_registre' => $_POST['numero_registre'] ?? ''
    ];

    $success = $mariageController->creerActeMariage($data);
    if ($success) {
        // Sauvegarder les données dans la session
        $_SESSION['donnees_actes']['mariage'] = $data;

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
        $error = "Erreur lors de l'enregistrement de l'acte de mariage.";
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

    <h1 class="text-center mb-4">Demande d'Acte de Mariage</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="" class="needs-validation" novalidate>
        <h3>Informations sur le mariage</h3>
        <div class="mb-3">
            <label for="date_mariage" class="form-label">Date de mariage</label>
            <input type="date" class="form-control" id="date_mariage" name="date_mariage" required>
            <div class="invalid-feedback">Veuillez entrer la date de mariage.</div>
        </div>
        <div class="mb-3">
            <label for="lieu_mariage" class="form-label">Lieu de mariage</label>
            <input type="text" class="form-control" id="lieu_mariage" name="lieu_mariage" required>
            <div class="invalid-feedback">Veuillez entrer le lieu de mariage.</div>
        </div>
        <div class="mb-3">
            <label for="nombre_enfant" class="form-label">Nombre d'enfants</label>
            <input type="number" class="form-control" id="nombre_enfant" name="nombre_enfant" min="0" value="0">
        </div>
        <div class="mb-3">
            <label for="numero_registre" class="form-label">Numéro de registre</label>
            <input type="text" class="form-control" id="numero_registre" name="numero_registre" required>
            <div class="invalid-feedback">Veuillez entrer le numéro de registre.</div>
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



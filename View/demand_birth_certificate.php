<?php

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['naissance'] = [
        'nom' => $_POST['nom_beneficiaire'],
        'prenom' => $_POST['prenom_beneficiaire'],
        'date_naissance' => $_POST['date_naissance'],
        'heure_naissance' => $_POST['heure_naissance'],
        'genre' => $_POST['genre'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'nom_pere' => $_POST['nom_pere'],
        'prenom_pere' => $_POST['prenom_pere'],
        'profession_pere' => $_POST['profession_pere'],
        'nom_mere' => $_POST['nom_mere'],
        'prenom_mere' => $_POST['prenom_mere'],
        'profession_mere' => $_POST['profession_mere'],
        'date_mariage' => $_POST['date_mariage'] ?: null,
        'lieu_mariage' => $_POST['lieu_mariage'] ?: null,
        'statut_mariage' => $_POST['statut_mariage'] ?: null,
        'date_deces' => $_POST['date_deces'] ?: null,
        'lieu_deces' => $_POST['lieu_deces'] ?: null,
    ];

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);

        switch ($acte_suivant) {
            case 'mariage':
                header('Location: demand_marriage.php');
                exit;
            case 'deces':
                header('Location: demand_death_certificate.php');
                exit;
        }
    }

    header('Location: traitement_final_demande.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="./assets/css/etape.css">
</head>

<body>
    <div class="container stepper-container mt-4">
        <ul class="list-group list-group-horizontal mb-4 justify-content-center">
            <li class="list-group-item">Étape 1 : Choix des Actes</li>
            <li class="list-group-item">Étape 2 : Demandeur</li>
            <li class="list-group-item active bg-primary text-white">
                Étape 3 :
                <?php echo htmlspecialchars(implode(', ', $_SESSION['actes_restants'] ?? ['Actes restants']));?>
            </li>
        </ul>

        <h1 class="text-center mb-4">Demande d'Acte de Naissance</h1>
        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="" class="needs-validation" novalidate>
            <h3>Bénéficiaire</h3>
            <div class="mb-3">
                <label for="nom_beneficiaire" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom_beneficiaire" name="nom" required>
                <div class="invalid-feedback">Veuillez entrer le nom.</div>
            </div>
            <div class="mb-3">
                <label for="prenom_beneficiaire" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom_beneficiaire" name="prenom" required>
                <div class="invalid-feedback">Veuillez entrer le prénom.</div>
            </div>
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                <div class="invalid-feedback">Veuillez entrer la date de naissance.</div>
            </div>
            <div class="mb-3">
                <label for="heure_naissance" class="form-label">Heure de naissance</label>
                <input type="time" class="form-control" id="heure_naissance" name="heure_naissance" required>
                <div class="invalid-feedback">Veuillez entrer l'heure de naissance.</div>
            </div>
            <div class="mb-3">
                <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                <input type="text" class="form-control" id="lieu_naissance" name="lieu_naissance" required>
                <div class="invalid-feedback">Veuillez entrer le lieu de naissance.</div>
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

            <h3>Informations du père</h3>
            <div class="mb-3">
                <label for="nom_pere" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom_pere" name="nom_pere" required>
                <div class="invalid-feedback">Veuillez entrer le nom du père.</div>
            </div>
            <div class="mb-3">
                <label for="prenom_pere" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom_pere" name="prenom_pere" required>
                <div class="invalid-feedback">Veuillez entrer le prénom du père.</div>
            </div>
            <div class="mb-3">
                <label for="profession_pere" class="form-label">Profession</label>
                <input type="text" class="form-control" id="profession_pere" name="profession_pere" required>
                <div class="invalid-feedback">Veuillez entrer la profession du père.</div>
            </div>

            <h3>Informations de la mère</h3>
            <div class="mb-3">
                <label for="nom_mere" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom_mere" name="nom_mere" required>
                <div class="invalid-feedback">Veuillez entrer le nom de la mère.</div>
            </div>
            <div class="mb-3">
                <label for="prenom_mere" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom_mere" name="prenom_mere" required>
                <div class="invalid-feedback">Veuillez entrer le prénom de la mère.</div>
            </div>
            <div class="mb-3">
                <label for="profession_mere" class="form-label">Profession</label>
                <input type="text" class="form-control" id="profession_mere" name="profession_mere" required>
                <div class="invalid-feedback">Veuillez entrer la profession de la mère.</div>
            </div>

            <h3>Informations optionnelles</h3>
            <div class="mb-3">
                <label for="date_mariage" class="form-label">Date de mariage</label>
                <input type="date" class="form-control" id="date_mariage" name="date_mariage">
            </div>
            <div class="mb-3">
                <label for="lieu_mariage" class="form-label">Lieu de mariage</label>
                <input type="text" class="form-control" id="lieu_mariage" name="lieu_mariage">
            </div>
            <div class="mb-3">
                <label for="statut_mariage" class="form-label">Statut du mariage</label>
                <input type="text" class="form-control" id="statut_mariage" name="statut_mariage">
            </div>
            <div class="mb-3">
                <label for="date_deces" class="form-label">Date de décès</label>
                <input type="date" class="form-control" id="date_deces" name="date_deces">
            </div>
            <div class="mb-3">
                <label for="lieu_deces" class="form-label">Lieu de décès</label>
                <input type="text" class="form-control" id="lieu_deces" name="lieu_deces">
            </div>
            <button type="submit" class="btn btn-primary">Passer à l'acte suivant</button>
            <a href="index.php?controller=demande&action=create_step3" class="btn btn-secondary ms-2">Précédent</a>
            <a href="index.php?controller=demande&action=final_process" class="btn btn-success ms-2">Continuer</a>
        </form>

        <script>
        (function() {
            'use strict';
            var form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function(event) {
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
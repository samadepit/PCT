<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['localiter'] = $_POST['localiter'];
    $_SESSION['actes'] = $_POST['actes'];
    $actes_selectionnes = $_POST['actes'];
    $donnees_existantes = $_SESSION['donnees_actes'] ?? [];
    foreach ($donnees_existantes as $type => $data) {
        if (!in_array($type, $actes_selectionnes)) {
            unset($_SESSION['donnees_actes'][$type]);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Demande - Étape 2</title>
    <link rel="stylesheet" href="./assets/css/etape.css">
</head>
<body>
 <div class="container stepper-container mt-4">
    <!-- Stepper -->
    <ul class="list-group list-group-horizontal mb-4 justify-content-center">
        <li class="list-group-item">Étape 1 : Choix des Actes</li>
        <li class="list-group-item active bg-primary text-white">Étape 2 : Demandeur</li>
        <li class="list-group-item">Étape 3 : Confirmation</li>
    </ul>

    <h1 class="text-center mb-4">Étape 2 : Informations sur le Demandeur</h1>
    <form method="POST" action="index.php?controller=demande&action=create_step3" class="needs-validation" novalidate>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
                <div class="invalid-feedback">Veuillez entrer votre nom.</div>
            </div>
            <div class="col-12 col-md-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
                <div class="invalid-feedback">Veuillez entrer votre prénom.</div>
            </div>
            <div class="col-12">
                <label for="relation_avec_beneficiaire" class="form-label">Relation avec le bénéficiaire</label>
                <select class="form-select" id="relation_avec_beneficiaire" name="relation_avec_beneficiaire" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="parent">Parent</option>
                    <option value="conjoint">Conjoint</option>
                    <option value="tuteur">Tuteur</option>
                    <option value="demandeur">Moi même</option>
                    <option value="autre">Autre</option>
                </select>
                <div class="invalid-feedback">Veuillez sélectionner une relation.</div>
            </div>
            <div class="col-12 col-md-6">
                <label for="numero_telephone" class="form-label">Téléphone</label>
                <input type="tel" class="form-control" id="numero_telephone" name="numero_telephone">
            </div>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Suivant</button>
                <a href="index.php?controller=demande&action=create" class="btn btn-secondary ms-2">Précédent</a>
                <a href="index.php?controller=demande&action=demander" class="btn btn-secondary ms-2">Annuler</a>
            </div>
        </div>
    </form>

    <script>
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</div>
</body>
</html>
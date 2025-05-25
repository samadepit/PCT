


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/etape.css">
</head>
<body>
  <div class="container stepper-container  mt-4">
    <!-- Stepper -->
    <ul class="list-group list-group-horizontal mb-4 justify-content-center">
        <li class="list-group-item">Étape 1 : Choix des Actes</li>
        <li class="list-group-item">Étape 2 : Demandeur</li>
        <li class="list-group-item active bg-primary text-white">Étape 3 : Confirmation</li>
    </ul>

    <h1 class="text-center  mb-4">Étape 3 : Confirmation</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Récapitulatif</h5>
            <p><strong>Localité :</strong> <?php echo htmlspecialchars($_SESSION['localiter'] ?? 'Non spécifié'); ?></p>
            <p><strong>Actes demandés :</strong> <?php echo htmlspecialchars(implode(', ', $_SESSION['actes'] ?? [])); ?></p>
            <h6>Demandeur</h6>
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($_SESSION['demandeur']['nom'] ?? 'Non spécifié'); ?></p>
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($_SESSION['demandeur']['prenom'] ?? 'Non spécifié'); ?></p>
            <p><strong>Relation :</strong> <?php echo htmlspecialchars($_SESSION['demandeur']['relation_avec_beneficiaire'] ?? 'Non spécifiée'); ?></p>
            <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($_SESSION['demandeur']['numero_telephone'] ?? 'Non spécifié'); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['demandeur']['email'] ?? 'Non spécifié'); ?></p>
        </div>
    </div>
    <form method="POST" action="index.php?controller=demande&action=create_submit">
        <button type="submit" class="btn btn-success">Confirmer et Continuer</button>
        <a href="index.php?controller=demande&action=create_step2" class="btn btn-secondary ms-2">Précédent</a>
        <a href="index.php?controller=demande&action=demander" class="btn btn-secondary ms-2">Annuler</a>
    </form>
</div>

</body>
</html>
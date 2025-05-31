<?php   
require_once __DIR__ . '/../Controller/certificate_duplicateController.php';

$controller = new ActeDuplicataController();
$actes = [];
$erreur = '';
$type='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type_acte']);
    $numero = trim($_POST['numero_registre']);
    $date = trim($_POST['evenement_date']);
    // var_dump([
    //     'type' => $type,
    //     'numero' => $numero,
    //     'date' => $date
    // ]);
    // die();
    if (!empty($type) && !empty($numero) && !empty($date)) {
        try {
            $actes = $controller->getActeByRegistreAndDate($type, $numero, $date);
            if (empty($actes)) {
                $_SESSION['erreur'] = "Aucun acte trouvé avec ces informations.";
            } else {
                $_SESSION['actes'] = $actes;
                $_SESSION['type_acte'] = $type;
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = "Erreur : " . $e->getMessage();
        }
    } else {
        $_SESSION['erreur'] = "Veuillez remplir tous les champs.";
    }
    // header("Location: " . $_SERVER['PHP_SELF']);
    // exit();
}

if (isset($_SESSION['actes'])) {
    $actes = $_SESSION['actes'];
    // unset($_SESSION['actes']);
}

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];  
    unset($_SESSION['erreur']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/duplicata/style.css">
</head>
<body>
  <div class="acte-search-container">
        <h1>🔍 Recherche d'Actes d'État Civil</h1>

        <?php if (!empty($erreur)): ?>
            <div class="error-message">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="acte-form">
            <label for="type_acte">Type d'acte :</label>
            <select id="type_acte" name="type_acte" required>
                <option value="">-- Sélectionner --</option>
                <option value="naissance" <?= ($type === 'naissance') ? 'selected' : '' ?>>Naissance</option>
                <option value="mariage" <?= ($type === 'mariage') ? 'selected' : '' ?>>Mariage</option>
                <option value="deces" <?= ($type === 'deces') ? 'selected' : '' ?>>Décès</option>
            </select>

            <label for="numero_registre">Numéro de registre :</label>
            <input type="text" id="numero_registre" name="numero_registre" required>

            <label for="evenement_date">Date de l'événement :</label>
            <input type="date" id="evenement_date" name="evenement_date" required>

            <button type="submit">🔍 Rechercher</button>
        </form>

        <?php if (!empty($actes) && is_array($actes)): ?>
            <div class="acte-detail">
                <?php if ($type === 'naissance'): ?>
                    <h3>📋 Acte de Naissance</h3>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($actes['nom_beneficiaire']) ?></p>
                    <p><strong>Prénom :</strong> <?= htmlspecialchars($actes['prenom_beneficiaire']) ?></p>
                    <p><strong>Lieu de naissance :</strong> <?= htmlspecialchars($actes['lieu_naissance']) ?></p>
                    <p><strong>Date de naissance :</strong> <?= htmlspecialchars($actes['date_naissance']) ?></p>
                    <a href="paiement.php" class="payment-button">💳 Passer au paiement</a>
                    
                <?php elseif ($type === 'deces'): ?>
                    <h3>⚱️ Acte de Décès</h3>
                    <p><strong>Nom du défunt :</strong> <?= htmlspecialchars($actes['nom_beneficiaire']) ?></p>
                    <p><strong>Prénom du défunt :</strong> <?= htmlspecialchars($actes['prenom_beneficiaire']) ?></p>
                    <p><strong>Lieu de décès :</strong> <?= htmlspecialchars($actes['lieu_deces']) ?></p>
                    <p><strong>Date de décès :</strong> <?= htmlspecialchars($actes['date_deces']) ?></p>
                    <a href="paiement.php" class="payment-button">💳 Passer au paiement</a>
                    
                <?php elseif ($type === 'mariage'): ?>
                    <h3>💒 Acte de Mariage</h3>
                    <p><strong>Époux :</strong> <?= htmlspecialchars($actes['nom_mari']) ?> <?= htmlspecialchars($actes['prenom_mari']) ?></p>
                    <p><strong>Épouse :</strong> <?= htmlspecialchars($actes['nom_femme']) ?> <?= htmlspecialchars($actes['prenom_femme']) ?></p>
                    <p><strong>Lieu de mariage :</strong> <?= htmlspecialchars($actes['lieu_mariage']) ?></p>
                    <p><strong>Date de mariage :</strong> <?= htmlspecialchars($actes['date_mariage']) ?></p>
                    <a href="paiement.php" class="payment-button">💳 Passer au paiement</a>
                    
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>  
</body>
</html>







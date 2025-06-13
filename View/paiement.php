<?php 
session_start();

$type = $_SESSION['actes'] ?? null;
$montant = 0;

if (count($type) == 3) {
    $montant = 15000;
} elseif (count($type) == 2) {
    $montant = 10000;
} else {
    $montant = 5000;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['numero'])) {
        $_SESSION['numero_telephone'] = $_POST['numero'];
        $_SESSION['code_paiement'] = 'TRC' . random_int(100, 999);
        header("Location: verify_code.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link rel="stylesheet" href="../assets/css/styleEtape.css">
    
</head>
<body>
      <?php
       require_once './partials/header.php'
     ?>

      <div class="stepper-container container">
        <div class="header-etape">
            <a href="confirmation.php" class="btn btn-retour">← Retour</a>
            <h2>Choisissez votre moyen de paiement</h2>
        </div>

        <div class="form-grid">
            <p class="price-display">Montant à payer : <?= htmlspecialchars($montant) ?> F CFA</p>
            <form method="POST" class="was-validated">
                <div class="form-row">
                    <div class="form-item">
                        <label class="form-label">Mode de paiement :</label>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="agregateur" value="wave" required>
                                <img src="data:image/jpeg;base64,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" alt="Wave">
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="agregateur" value="orange" required>
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAABF1BMVEUEBAQDAwP///8AAADxfgH8/PxVVVUICAj5+fmioqIABQO0tLTyfQLvfgMAAAbohxnwhBtnOhKOjo7p6eng4OAQEBDW1taBgYGqqqrJycnv7+8XFxe4uLjFxcUwMDCamprb29t5eXmSkpJJSUkhISFhYWFubm5AQEAYAAALAAA5OTmGhoZZWVn4fAAcHBwyMjIdAACeXx1lOxLqggCLVSbwhCJpNxcKAAw6FwOQWjKtbjqkZjOeWSjniDjwexPniizHgz9TLBSpYCfEeiurXh2ATy5kNyASBBXYjjV1TBW3bSvglCCfbSsmAADlkjV/TCBCFwCRUyUABhfrhyifSxLojx+aVRjZfxAwDAJYLQbGeDO6eT1WIw5ZQvNgAAAQA0lEQVR4nO2bC2Pbxg3HSUIUqQdpRaLe1st62JIsO63iOGliu3GTNsvWpom3bG33/T/HANxRoihSdrqmyTz8PTeWSJH3I3AADqcZhkgkEolEIpFIJBKJRCKRSCQSiUQikUgkEolEIpFIJBKJRCKRSCQSiUQikUgkEolEIpFIJBKJRCKRSCQSiUQikejz6z86EXro9ElKIwAAAABJRU5ErkJggg==" alt="Orange Money">
                            </label>
                        </div>
                        <div class="invalid-feedback">Veuillez sélectionner un mode de paiement.</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-item">
                        <label for="numero" class="form-label">Numéro de téléphone :</label>
                        <input type="tel" name="numero" id="numero" class="form-control" placeholder="Numéro de téléphone" pattern="[0-9]{10}" required>
                        <div class="invalid-feedback">Veuillez entrer un numéro de téléphone valide (10 chiffres).</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-25">Payez</button>
            </form>
        </div>
    </div>
    <?php
       require_once './partials/footer.php'
      ?>
</body>
</html>

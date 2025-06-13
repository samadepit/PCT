<?php
session_start();
require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/marriageController.php';
require_once __DIR__ . '/../Controller/deathController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/requestroController.php';

$birthController = new NaissanceController();
$marriageController = new MarriageController();
$deathController = new DecesController();
$demandController = new DemandeController();
$certificate_demandController = new ActeDemandeController();
$requestroController = new DemandeurController();

$data_certificate = $_SESSION['donnees_actes'] ?? [];
$requestor_data = $_SESSION['demandeur'] ?? [];

foreach ($data_certificate as $type => $certificate) {
    if (!is_array($certificate) || array_keys($certificate) === range(0, count($certificate) - 1)) {
        continue;
    }
    $data_certificate[$type] = [$certificate];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
    try {
        header('Location: paiement.php');
        exit;
    } catch (Exception $e) {
        $erreurs[] = $e->getMessage();
        error_log("Erreur traitement: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_type'])) {
    $type = $_POST['modifier_type'];
    $redirectPage = 'demande_etape1.php';
    
    if ($redirectPage) {
        header("Location: $redirectPage");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styleEtape.css">
</head>

<body>
      
    <?php
       require_once './partials/header.php'
     ?>

  <?php if (!empty($data_certificate)): ?>

    <div class="stepper-container container">
        <div class="header-etape">
            <a href="javascript:history.back()" class="btn btn-retour">← Retour</a>
            <h2>🧾 Vérification des informations</h2>
        </div>

        <div class="form-grid">
            <?php if (!empty($_SESSION['localiter'])): ?>
            <div class="section">
                <h3>📍 Localité</h3>
                <p class="section-content"><?= htmlspecialchars($_SESSION['localiter']) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($requestor_data)): ?>
            <div class="section">
                <h3>🙋‍♂️ Informations sur le demandeur</h3>
                <ul class="section-list">
                    <?php foreach ($requestor_data as $cle => $val): ?>
                    <li><strong><?= htmlspecialchars($cle) ?>:</strong> <?= htmlspecialchars($val) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php foreach ($data_certificate as $type => $certificates): ?>
            <div class="section">
                <h3>📄 <?= ucfirst($type) ?></h3>
                <div class="certificate-container">
                    <?php foreach ($certificates as $i => $certificate): ?>
                    <details class="certificate-details">
                        <summary><?= ucfirst($type) ?> #<?= (int)$i + 1 ?> - Afficher / Masquer</summary>
                        <fieldset class="certificate-fieldset">
                            <ul class="section-list">
                                <?php foreach ($certificate as $cle => $val): ?>
                                <li><strong><?= htmlspecialchars($cle) ?>:</strong> <?= htmlspecialchars($val) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <form method="post" class="form-grid">
                                <input type="hidden" name="modifier_type" value="<?= htmlspecialchars($type) ?>">
                                <input type="hidden" name="certificate_index" value="<?= $i ?>">
                                <button type="submit" class="btn btn-secondary">✏️ Modifier cet acte</button>
                            </form>
                        </fieldset>
                    </details>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <form method="post" class="form-grid">
                <button type="submit" name="confirmer" class="btn btn-primary w-25 ">✅ Confirmer et payer</button>
            </form>
        </div>
    </div>
 <?php endif; ?>
   
      <?php
       require_once './partials/footer.php'
      ?>
</body>

</html>
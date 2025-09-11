<?php
session_start();

require_once __DIR__ . '/../Controller/paymentController.php';
require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/marriageController.php';
require_once __DIR__ . '/../Controller/deathController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/requestroController.php';

$acteDemandeController=new ActeDemandeController;

$paymentcontroller = new PaymentController();
$code_demand = $_GET['code_demande'] ;

$message = "";
$success = false;
$code_paiement_generate = $_SESSION['code_paiement'] ?? null;
$numero = $_SESSION['numero_telephone'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['code_paiement'])) {
    $code_paiement_saisi = trim($_POST['code_paiement']);

    if (!$numero) {
        $message = "Numéro de téléphone manquant.";
    } elseif ($code_paiement_generate !== $code_paiement_saisi) {
        $message = "❌ Code incorrect. Veuillez réessayer.";
    } else {
        $success = true;
        $message = "✅ Paiement confirmé. Merci !";
        $paymentcontroller->createPayment($code_demand, $numero, $code_paiement_generate,$is_duplicate=0);
        $acteDemandeController->addPaymentForOneCertificate($code_demand);
        header("Location: impression.php?code_demande=" . urlencode($code_demand));
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Vérification du paiement</title>
    <link rel="stylesheet" href="../assets/css/verify_code.css">
</head>

<body>
    <?php
       require_once './partials/header.php';
    ?>
    <div class="container">
        <div class="container-kpi">
            <h2>Entrez le code de paiement de paiement ci-dessous</h2>
            <i><?=$code_paiement_generate?></i>
            <form method="POST" action="">
                <input type="text" name="code_paiement" id="code_paiement" placeholder="Code reçu par SMS" required>
                <button type="submit">Valider</button>
            </form>
            <?php if (!empty($message)): ?>
            <div class="message <?= $success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
      require_once './partials/footer.php';
       ?>
</body>

</html>
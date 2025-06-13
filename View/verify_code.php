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
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification du paiement</title>
    <style>
        body {
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        input[type="text"] {
            padding: 10px;
            width: 100%;
            margin-top: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
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
</body>
</html>

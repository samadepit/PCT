<?php
session_start();
require_once __DIR__ . '/../Controller/paymentController.php';
require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/marriageController.php';
require_once __DIR__ . '/../Controller/deathController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/requestroController.php';

$paymentcontroller = new PaymentController();
$birthController = new NaissanceController();
$marriageController = new MarriageController();
$deathController = new DecesController();
$demandController = new DemandeController();
$certificate_demandController = new ActeDemandeController();
$requestroController = new DemandeurController();

$message = "";
$success = false;
$code_paiement_generate = $_SESSION['code_paiement'] ?? null;
$data_certificate = $_SESSION['donnees_actes'] ?? [];
$requestor_data = $_SESSION['demandeur'] ?? [];
$numero = $_SESSION['numero_telephone'] ?? null;
$code_demande_duplicate=$_SESSION['code_demande_duplicate'] ?? null;

foreach ($data_certificate as $type => $certificate) {
    if (!is_array($certificate) || array_keys($certificate) === range(0, count($certificate) - 1)) {
        continue;
    }
    $data_certificate[$type] = [$certificate];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['code_paiement'])) {
    $code_paiement_saisi = trim($_POST['code_paiement']);

    if (!$numero) {
        $message = "Numéro de téléphone manquant.";
    } elseif ($code_paiement_generate !== $code_paiement_saisi) {
        $message = "❌ Code incorrect. Veuillez réessayer.";
    } else {
        $success = true;
        $message = "✅ Paiement confirmé. Merci !";

        try {
            if (!empty($data_certificate)) {
                $certificat_ids = [];

                foreach ($data_certificate as $type => $certificates) {
                    if (!is_array($certificates)) $certificates = [$certificates];

                    foreach ($certificates as $certificate) {
                        switch ($type) {
                            case 'naissance':
                                $birth_id = $birthController->get_existing_birth_id($certificate);
                                if (!$birth_id) {
                                    $certificate_id = $birthController->create_birth_certificate($certificate);
                                    if (!$certificate_id) throw new Exception("Erreur dans l'acte de naissance.");
                                } else {
                                    $certificate_id = $birth_id;
                                }
                                break;

                            case 'mariage':
                                $certificate_id = $marriageController->create_marriage_certificate($certificate);
                                if (!$certificate_id) throw new Exception("Erreur dans l'acte de mariage.");
                                $birthController->addMarriageInbirthcertificate($certificate);
                                break;

                            case 'deces':
                                $birth_id = $birthController->get_existing_birth_id($certificate);
                                if (!$birth_id) throw new Exception("ID naissance introuvable pour décès.");
                                $certificate_id = $deathController->create_death_certificate($certificate, $birth_id);
                                if (!$certificate_id) throw new Exception("Erreur dans l'acte de décès.");
                                $birthController->addDeathInbirthcertificate($certificate, $birth_id);
                                break;

                            default:
                                throw new Exception("Type d'acte inconnu : $type");
                        }

                        $certificat_ids[] = ['type' => $type, 'id' => $certificate_id];
                    }
                }
                $code_demand = $demandController->create_demand($_SESSION['localiter'] ?? null);
                $requestroController->create_requestor($code_demand, $requestor_data);
                $paymentcontroller->createPayment($code_demand, $numero, $code_paiement_generate,$is_duplicate=0);

                foreach ($certificat_ids as $certif) {
                    $certificate_demandController->certificate_demand($code_demand, $certif['type'], $certif['id']);
                }
                
                $certificate_demandController->addPaymentForOneCertificate($code_demand);

                $_SESSION['code_demande'] = $code_demand;
                unset($_SESSION['demandeur'], $_SESSION['localiter'], $_SESSION['donnees_actes'], $_SESSION['code_paiement']);
                header('Location: code_suivie.php');
                exit;
            }
            else {
                // $code_demand = $_SESSION['code_demande'] ?? null;
                // if (!$code_demand) throw new Exception("Code de demande manquant pour duplicata.");
                $paymentcontroller->createPayment($code_demande_duplicate, $numero, $code_paiement_generate,$is_duplicate=1);
                unset($_SESSION['code_paiement']);
                header('Location: impression.php');
                exit;
            }

        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
            error_log("Erreur traitement: " . $e->getMessage());
        }
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

<?php
require_once __DIR__ . '/signingController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeDemande = $_POST['code_demande'] ?? null;
    $signatureData = $_POST['signature'] ?? null;

    if (!$codeDemande || !$signatureData) {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
        exit;
    }

    // Appel de la méthode du contrôleur pour enregistrer la signature
    $controller = new SigningController();
    $result = $controller->enregistrerSignature($codeDemande, $signatureData);

    echo json_encode($result);
}

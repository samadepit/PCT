<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['demandeur'] = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'relation_avec_beneficiaire' => $_POST['relation_avec_beneficiaire'],
        'numero_telephone' => $_POST['numero_telephone'],
        'email' => $_POST['email']
    ];

    $_SESSION['actes_restants'] = $_SESSION['actes'];
    
    $premier_acte = array_shift($_SESSION['actes_restants']);

    switch ($premier_acte) {
        case 'naissance':
            header('Location: demande_naissance.php');
            break;
        case 'mariage':
            header('Location: demande_acte_mariage.php');
            break;
        case 'deces':
            header('Location: demande_acte_deces.php');
            break;
    }
    exit;
}

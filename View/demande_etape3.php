<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    function saveTempFile($file, $folder = 'uploads/tmp') {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('temp_') . '.' . $ext;
            if (!is_dir($folder)) mkdir($folder, 0755, true);
            $destination = $folder . '/' . $filename;
            move_uploaded_file($file['tmp_name'], $destination);
            return $destination;
        }
        return null;
    }
    $_SESSION['demandeur'] = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'relation_avec_beneficiaire' => $_POST['relation_avec_beneficiaire'],
        'numero_telephone' => $_POST['numero_telephone'],
        'email' => $_POST['email'],
        'lieu_residence'=>  $_POST['lieu_residence'],
        'piece_identite_demandeur'=> saveTempFile($_FILES['piece_identite_demandeur']) 
    ];

    


    $_SESSION['actes_restants'] = $_SESSION['actes'];
    
    $premier_acte = array_shift($_SESSION['actes_restants']);

    switch ($premier_acte) {
        case 'naissance':
            header('Location: demand_birth_certificate.php');
            break;
        case 'mariage':
            header('Location: demand_marriage.php');
            break;
        case 'deces':
            header('Location: demand_death_certificate.php');
            break;
    }
    exit;
}

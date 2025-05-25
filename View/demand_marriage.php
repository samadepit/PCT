<?php
session_start();

require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';

$naissanceController = new NaissanceController();
$demandeController = new DemandeController();
$traitementController = new ActeDemandeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $husband_info = [
        'nom' => $_POST['husband_lastname'],
        'prenom' => $_POST['husband_firstname'],
        'lieu_naissance' => $_POST['husband_birth_place'],
        'date_naissance' => $_POST['husband_birth_date'],
        'genre' => 'Masculin'
    ];

    $wife_info = [
        'nom' => $_POST['wife_lastname'],
        'prenom' => $_POST['wife_firstname'],
        'lieu_naissance' => $_POST['wife_birth_place'],
        'date_naissance' => $_POST['wife_birth_date'],
        'genre' => 'Féminin'
    ];


    $husband_birth_id = $naissanceController->get_existing_birth_id($husband_info);
    $wife_birth_id = $naissanceController->get_existing_birth_id($wife_info);

    if($husband_birth_id && $wife_birth_id){
        $_SESSION['donnees_actes']['mariage'] = [
            'husband_birth_id'=> $husband_birth_id,
            'wife_birth_id'=> $wife_birth_id,
            'marriage_date'=> $_POST['marriage_date'],
            'marriage_place'=> $_POST['marriage_place'],
            'number_children'=> $_POST['number_children'],
            'statut_marriage' => $_POST['statut_marriage']
        ];
    }else {
        if (!$husband_birth_id) {
            throw new Exception("Acte de naissance du mari introuvable");
        }
        if (!$wife_birth_id) {
            throw new Exception("Acte de naissance de la femme introuvable");
        }
    }

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);
        switch ($acte_suivant) {
                case 'naissance':
                    header('Location: demand_birth_certificate.php');
                    exit;
                case 'deces':
                    header('Location: demand_death_certificate.php');
                    exit;
            }
        }
    header('Location: traitement_final_demande.php');
    exit;

    }    
?>

<h2>Formulaire de demande d’acte de mariage</h2>

<form method="post" class="form-container">
    <h3>Informations sur le mari</h3>
    <label>Nom :
        <input type="text" name="husband_lastname" required>
    </label>
    <label>Prenom :
        <input type="text" name="husband_firstname" required>
    </label>
    <label>Date de naissance :
        <input type="date" name="husband_birth_date" required>
    </label>
    <label>Lieu de naissance:
        <input type="text" name="husband_birth_place" required>
    </label>
    <h3>Informations sur la femme</h3>
    <label>Nom :
        <input type="text" name="wife_lastname" required>
    </label>
    <label>Prenom :
        <input type="text" name="wife_firstname" required>
    </label>
    <label>Date de naissance :
        <input type="date" name="wife_birth_date" required>
    </label>
    <label>Lieu de naissance :
        <input type="text" name="wife_birth_place" required>
    </label>
    <h3>Détails du mariage</h3>
    <label>Date du mariage :
        <input type="date" name="marriage_date" required>
    </label>
    <label>Lieu du mariage :
        <input type="text" name="marriage_place" required>
    </label>
    <label>Etat du mariage :
        <input type="text" name="statut_marriage" required>
    </label>
    <label>Nombre d'enfant :
        <input type="number" name="number_children" required>
    </label>

    <button type="submit">Soumettre la demande</button>
</form>

<style>
    .form-container {
        background-color: #ffffff;
        max-width: 600px;
        margin: 20px auto;
        padding: 35px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    h3 {
        margin-top: 30px;
        margin-bottom: 10px;
        border-left: 4px solid #3498db;
        padding-left: 10px;
        color: #3498db;
    }

    label {
        display: block;
        margin-bottom: 20px;
        font-weight: 500;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 15px;
        box-sizing: border-box;
        transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="date"]:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        outline: none;
    }

    button[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #3498db;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 25px;
    }

    button[type="submit"]:hover {
        background-color: #2980b9;
    }
</style>

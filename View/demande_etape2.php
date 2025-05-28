<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['localiter'] = $_POST['localiter'];
    $_SESSION['actes'] = $_POST['actes'];
    $actes_selectionnes = $_POST['actes'];
    $donnees_existantes = $_SESSION['donnees_actes'] ?? [];
    foreach ($donnees_existantes as $type => $data) {
        if (!in_array($type, $actes_selectionnes)) {
            unset($_SESSION['donnees_actes'][$type]);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Demande - Étape 2</title>
    <style>
        body { font-family: Arial; max-width: 700px; margin: auto; padding: 20px; background: #f4f4f4; }
        form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin: 10px 0; }
        button { margin-top: 15px; padding: 10px 15px; background: #2ecc71; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>
<h2>Étape 2 : Informations sur le demandeur</h2>
<form method="post" action="demande_etape3.php">
    <label>Nom : <input type="text" name="nom" required></label>
    <label>Prénom : <input type="text" name="prenom" required></label>
    <label>Relation avec le bénéficiaire : 
    <select name="relation_avec_beneficiaire" required>
        <option value="">-- Sélectionner --</option>
        <option value="parent">Parent</option>
        <option value="conjoint">Conjoint</option>
        <option value="tuteur">Tuteur</option>
        <option value="demandeur">Moi même</option>
        <option value="autre">Autre</option>
    </select>
    </label>
    <label>lieu de residence: <input type="text" name="lieu_residence"required></label>
    <label>Téléphone : <input type="tel" name="numero_telephone"></label>
    <label>Email : <input type="email" name="email"></label>
    <button type="submit">Suivant</button>
</form>
</body>
</html>
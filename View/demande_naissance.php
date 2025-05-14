<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['naissance'] = [
        'nom_beneficiaire' => $_POST['nom_beneficiaire'],
        'prenom_beneficiaire' => $_POST['prenom_beneficiaire'],
        'date_naissance' => $_POST['date_naissance'],
        'heure_naissance' => $_POST['heure_naissance'],
        'genre' => $_POST['genre'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'nom_pere' => $_POST['nom_pere'],
        'prenom_pere' => $_POST['prenom_pere'],
        'profession_pere' => $_POST['profession_pere'],
        'nom_mere' => $_POST['nom_mere'],
        'prenom_mere' => $_POST['prenom_mere'],
        'profession_mere' => $_POST['profession_mere'],
        'date_mariage' => $_POST['date_mariage'] ?: null,
        'lieu_mariage' => $_POST['lieu_mariage'] ?: null,
        'statut_mariage' => $_POST['statut_mariage'] ?: null,
        'date_deces' => $_POST['date_deces'] ?: null,
        'lieu_deces' => $_POST['lieu_deces'] ?: null,
    ];

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);

        switch ($acte_suivant) {
            case 'mariage':
                header('Location: demande_mariage.php');
                exit;
            case 'deces':
                header('Location: demande_deces.php');
                exit;
        }
    }
    header('Location: traitement_final_demande.php');
    exit;
}
?>
<form method="post">
    <h3>Bénéficiaire</h3>
    <label>Nom :
        <input type="text" name="nom_beneficiaire" required>
    </label><br>

    <label>Prénom :
        <input type="text" name="prenom_beneficiaire" required>
    </label><br>

    <label>Date de naissance :
        <input type="date" name="date_naissance" required>
    </label><br>

    <label>Heure de naissance :
        <input type="time" name="heure_naissance" required>
    </label><br>

    <label>Lieu de naissance :
        <input type="text" name="lieu_naissance" required>
    </label><br>

    <label>genre :
        <select name="genre" required>
            <option value="">-- Sélectionner --</option>
            <option value="Masculin">Masculin</option>
            <option value="Féminin">Féminin</option>
            <option value="Autre">Autre</option>
        </select>
    </label><br>

    <h3>Informations du père</h3>
    <label>Nom :
        <input type="text" name="nom_pere" required>
    </label><br>

    <label>Prénom :
        <input type="text" name="prenom_pere" required>
    </label><br>

    <label>Profession :
        <input type="text" name="profession_pere" required>
    </label><br>

    <h3>Informations de la mère</h3>
    <label>Nom :
        <input type="text" name="nom_mere" required>
    </label><br>

    <label>Prénom :
        <input type="text" name="prenom_mere" required>
    </label><br>

    <label>Profession :
        <input type="text" name="profession_mere" required>
    </label><br>

    <h3>Informations optionnelles</h3>
    <label>Date de mariage :
        <input type="date" name="date_mariage">
    </label><br>

    <label>Lieu de mariage :
        <input type="text" name="lieu_mariage">
    </label><br>

    <label>Statut du mariage :
        <input type="text" name="statut_mariage">
    </label><br>

    <label>Date de décès :
        <input type="date" name="date_deces">
    </label><br>

    <label>Lieu de décès :
        <input type="text" name="lieu_deces">
    </label><br>

    <button type="submit">Passer à l'acte suivant</button>
</form>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
    }

    form {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h3 {
        color: #2c3e50;
        margin-top: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    label {
        display: block;
        margin-bottom: 15px;
        font-weight: 500;
    }

    input[type="text"],
    input[type="date"],
    input[type="time"],
    select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    select:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    button[type="submit"] {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
        transition: background-color 0.3s;
    }

    button[type="submit"]:hover {
        background-color: #2980b9;
    }

    @media (min-width: 600px) {
        label {
            display: grid;
            grid-template-columns: 200px 1fr;
            align-items: center;
            gap: 15px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            margin-top: 0;
        }
    }
</style>

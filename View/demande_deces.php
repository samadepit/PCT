<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['deces'] = [
        'nom_defunt' => $_POST['nom_defunt'],
        'prenom_defunt' => $_POST['prenom_defunt'],
        'date_deces' => $_POST['date_deces'],
        'lieu_deces' => $_POST['lieu_deces'],
        'cause'=> $_POST['cause'],
        'profession'=>$_POST['profession']
    ];

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);

        switch ($acte_suivant) {
            case 'naissance':
                header('Location: demande_naissance.php');
                exit;
            case 'mariage':
                header('Location: demande_mariage.php');
                exit;
        }
    }
    header('Location: traitement_final_demande.php');
    exit;
}
?>

<form method="post">
    <h3>Informations sur le défunt</h3>
    <label>Nom : <input type="text" name="nom_defunt" required></label><br>
    <label>Prénom : <input type="text" name="prenom_defunt" required></label><br>
    <label>Date de naissance : <input type="date" name="date_naissance_defunt" required></label><br>
    <label>Lieu de naissance : <input type="text" name="lieu_naissance_defunt" required></label><br>
    <label>Date de décès : <input type="date" name="date_deces" required></label><br>
    <label>Lieu de décès : <input type="text" name="lieu_deces" required></label><br>
    <label>Lieu de décès : <input type="text" name="profession" required></label><br>
    <label>Cause du décès : <input type="text" name="cause" required></label><br>
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
    input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    input[type="text"]:focus,
    input[type="date"]:focus {
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
        input[type="date"] {
            margin-top: 0;
        }
    }
</style>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['naissance'] = [
        'nom' => $_POST['nom_beneficiaire'],
        'prenom' => $_POST['prenom_beneficiaire'],
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

    $_SESSION['donnees_actes']['naissance']['piece_identite_pere'] = saveTempFile($_FILES['piece_identite_pere']);
    $_SESSION['donnees_actes']['naissance']['piece_identite_mere'] = saveTempFile($_FILES['piece_identite_mere']);
    $_SESSION['donnees_actes']['naissance']['certificat_de_naissance'] = saveTempFile($_FILES['certificat_de_naissance']);

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);
        switch ($acte_suivant) {
            case 'mariage':
                header('Location: demand_marriage.php');
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI" />
        <h1>Bienvenue sur le Portail des Demande d'actes d'état civil</h1>
        <nav>
            <a href="dashboard.php" class="nav-btn">Accueil</a>
            <a href="demande_etape1.php" class="nav-btn"><span>Faire une demande</span></a>
            <a href="consulter_demande.php" class="nav-btn">Suivre une demande</a>
        </nav>
    </div>
    <title>Demande - Acte de Naissance</title>
    <style>
            html, body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: linear-gradient(to right, #ff8008, #ffc837);
    }

    body {
        font-family: Arial, sans-serif;
        background-color: linear-gradient(to right, #ff8008, #ffc837);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 80px; /* espace pour le header fixe */
    }

    .top-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 80px;
        width: 100%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 40px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #1f2937;
        z-index: 1000;
        box-sizing: border-box;
    }

    .top-header img {
        height: 50px;
    }

    .top-header h1 {
        font-size: 20px;
        font-weight: bold;
        flex: 1;
        text-align: center;
        margin: 0;
        color: #1f2937;
    }

    .top-header nav {
        display: flex;
        gap: 20px;
        font-weight: 600;
        font-size: 16px;
    }

    .top-header nav span {
        color: #f97316; /* orange pour la page active */
    }

    .top-header nav a {
        text-decoration: none;
        color: #1f2937;
    }

        .container {
            width: 100%;
            max-width: 960px;
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #ff8008;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 30px;
            margin-bottom: 10px;
            color: #444;
            font-size: 1.3rem;
            border-left: 5px solid #ff8008;
            padding-left: 10px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        input[type="file"],
        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 70%;
            padding: 10px 15px;
            border: 2px solid #ff8008;
            border-radius: 8px;
            background-color: #fff;
            font-size: 0.95rem;
            color: #333;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #ff9500;
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.2);
        }

        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .col {
            flex: 1;
            min-width: 260px;
        }

        button {
            background: #ff8008;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 25px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e67600;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 1.4rem;
            }

            button {
                padding: 12px;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Acte de Naissance</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col form-group">
                <label>Nom</label>
                <input type="text" name="nom_beneficiaire" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
            <div class="col form-group">
                <label>Prénom</label>
                <input type="text" name="prenom_beneficiaire" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" required>
            </div>
            <div class="col form-group">
                <label>Heure de naissance</label>
                <input type="time" name="heure_naissance" required>
            </div>
            <div class="col form-group">
                <label>Genre</label>
                <select name="genre" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="Masculin">Masculin</option>
                    <option value="Féminin">Féminin</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
        </div>
        <div class="form-group">
            <label>Certificat de naissance</label>
            <input type="file"  name="certificat_de_naissance" accept="application/pdf,image/jpeg,image/png" required>
        </div>

        <h3>Informations du père</h3>
        <div class="row">
            <div class="col form-group">
                <label>Nom</label>
                <input type="text" name="nom_pere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
            <div class="col form-group">
                <label>Prénom</label>
                <input type="text" name="prenom_pere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
            <div class="col form-group">
                <label>Profession</label>
                <input type="text" name="profession_pere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
        </div>
        <div class="row">
        <div class="col form-group">
                <label>Pièce d'identité du pere (PDF/JPEG/PNG max 5MB) :</label>
                <input type="file" name="piece_identite_pere" accept="application/pdf,image/jpeg,image/png" required>
            </div>
        </div>
        <h3>Informations de la mère</h3>
        <div class="row">
            <div class="col form-group">
                <label>Nom</label>
                <input type="text" name="nom_mere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
            <div class="col form-group">
                <label>Prénom</label>
                <input type="text" name="prenom_mere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
            <div class="col form-group">
                <label>Profession</label>
                <input type="text" name="profession_mere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
            </div>
        </div>
        <div class="row"> 
            <div class="col form-group"> 
                <label>Pièce d'identité de la mere (PDF/JPEG/PNG max 5MB) :</label>
                <input type="file" name="piece_identite_mere" accept="application/pdf,image/jpeg,image/png" required>
            </div>
        </div>

        <h3>Informations supplémentaires (facultatives)</h3>
        <div class="row">
            <div class="col form-group">
                <label>Date de mariage</label>
                <input type="date" name="date_mariage">
            </div>
            <div class="col form-group">
                <label>Lieu de mariage</label>
                <input type="text" name="lieu_mariage" pattern="^[A-Za-zÀ-ÿ\s\-]*$">
            </div>
            <div class="col form-group">
                <label>Statut du mariage</label>
                <input type="text" name="statut_mariage" pattern="^[A-Za-zÀ-ÿ\s\-]*$">
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Date de décès</label>
                <input type="date" name="date_deces">
            </div>
            <div class="col form-group">
                <label>Lieu de décès</label>
                <input type="text" name="lieu_deces" pattern="^[A-Za-zÀ-ÿ\s\-]*$">
            </div>
        </div>

        <button type="submit">Passer à l'acte suivant</button>
    </form>
</div>
</body>
</html>

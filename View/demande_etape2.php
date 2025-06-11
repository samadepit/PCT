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
    <title>Demande - Étape 2</title>
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


        form {
            background: #fff;
            padding: 20px; 
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 460px; 
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ff8008;
            font-size: 2rem;
        }

        label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1rem;
            color: #333;
        }

        input[type="file"],
        input[type="text"],
        input[type="tel"],
        input[type="email"]{
            width: 85%;
            padding: 10px 34px;  
            border: 2px solid #ff8008;
            border-radius: 8px;
            background-color: #fff;
            font-size: 0.95rem;
            margin-top: 5px;
            color: #333;
        }


        select {
            width: 53%; 
            padding: 10px 34px;  
            border: 2px solid #ff8008;
            border-radius: 8px;
            background-color: #fff;
            font-size: 0.95rem; 
            margin-top: 5px;
            color: #333;
        }

        select:focus,
        input:focus {
            outline: none;
            border-color: #ff9500;
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.2);
        }

        button {
            background: #ff8008;
            color: white;
            border: none;
            padding: 12px 18px; 
            border-radius: 8px;
            font-size: 1rem; 
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e67600;
        }

        .header-etape {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 25px;
            gap: 20px;
        }

        .btn-retour {
            background-color:rgb(31, 122, 241);
            color: white;
            padding: 11px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 20%;
        }

        .btn-retour:hover {
            background-color:rgb(15, 0, 230);
            color: #000;
            transform: translateY(-1px);
        }



        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

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
        }
    </style>
</head>
<body>
    <form method="post" action="demande_etape3.php" enctype="multipart/form-data">
        <div class="header-etape">
            <a href="demande_etape1.php" class="btn-retour">&#8592; Retour</a>
            <h2>Étape 2 : Informations sur le demandeur</h2>
        </div>

        <label for="nom">Nom :
            <input type="text" name="nom" id="nom"  pattern="[A-Za-zÀ-ÿ\s\-']{2,50}" required>
        </label>

        <label for="prenom">Prénom :
            <input type="text" name="prenom" id="prenom"  pattern="[A-Za-zÀ-ÿ\s\-']{2,50}" required>
        </label>

        <label for="relation">Relation avec le bénéficiaire :
            <select name="relation_avec_beneficiaire" id="relation" required>
                <option value="">-- Sélectionner --</option>
                <option value="parent">Parent</option>
                <option value="conjoint">Conjoint</option>
                <option value="tuteur">Tuteur</option>
                <option value="demandeur">Moi-même</option>
                <option value="autre">Autre</option>
            </select>
        </label>

        <label for="lieu_residence">Lieu de résidence :
            <input type="text" name="lieu_residence" id="lieu_residence"  pattern="[A-Za-zÀ-ÿ0-9\s\-']{2,100}" required>
        </label>

        <label for="numero_telephone">Téléphone :
            <input type="tel" name="numero_telephone" id="numero_telephone" pattern="^\d{10,15}$" title="Entrez un numéro de téléphone valide (10 à 15 chiffres)"required>
        </label>

        <label for="email">Email (optionnel):
            <input type="email" name="email" id="email">
        </label>

        <label for="file">Pièce d'identité  (PDF/JPEG/PNG max 5MB) :
            <input type="file" name="piece_identite_demandeur" accept="application/pdf,image/jpeg,image/png" required>
        </label>
        <button type="submit">Suivant</button>
    </form>
</body>
</html>

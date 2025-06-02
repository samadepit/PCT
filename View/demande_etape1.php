<?php
// fichier : demande_etape1.php
session_start();
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
    <title>Demande - Étape 1</title>
    <style>
         html, body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f5f7fa;
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
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ff8008;
            font-size: 2rem;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 1rem;
            color: #333;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #ff8008;
            border-radius: 8px;
            background-color: #fff;
            font-size: 1rem;
            margin-bottom: 20px;
            color: #333;
        }

        select option {
            padding: 10px;
        }

        select:focus, input[type="text"]:focus {
            outline: none;
            border-color: #ff9500;
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.2);
        }

        button {
            background: #ff8008;
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e67600;
        }

        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            h2 {
                font-size: 1.6rem;
            }

            select, input[type="text"], button {
                font-size: 1rem;
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
    <form method="post" action="demande_etape2.php">
        <h2>Étape 1 : Choix des actes et localité</h2>

        <label for="localiter">Localité :</label>
        <input type="text" name="localiter" id="localiter" value="Ouangolodougou" readonly>

        <label for="actes">Choisissez les actes à demander :</label>
        <select name="actes[]" id="actes" multiple required>
            <option value="naissance">Acte de naissance</option>
            <option value="mariage">Acte de mariage</option>
            <option value="deces">Acte de décès</option>
        </select>

        <button type="submit">Suivant</button>
    </form>

    <script>
        // Vérifie qu'au moins un acte est sélectionné
        document.querySelector("form").addEventListener("submit", function(e) {
            const select = document.getElementById("actes");
            const selected = Array.from(select.options).filter(option => option.selected).length;
            if (selected === 0) {
                e.preventDefault();
                alert("Veuillez sélectionner au moins un acte.");
            }
        });
    </script>
</body>
</html>

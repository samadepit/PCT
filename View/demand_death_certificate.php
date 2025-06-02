<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation serveur simple (exemple : nom ou prénom vide malgré le required HTML)
    if (empty($_POST['nom_defunt']) || empty($_POST['prenom_defunt'])) {
        $_SESSION['error'] = "Le nom et le prénom du défunt sont requis.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $_SESSION['donnees_actes']['deces'] = [
        'nom' => $_POST['nom_defunt'],
        'prenom' => $_POST['prenom_defunt'],
        'date_naissance' => $_POST['date_naissance'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'genre' => $_POST['genre'],
        'date_deces' => $_POST['date_deces'],
        'lieu_deces' => $_POST['lieu_deces'],
        'cause' => $_POST['cause'],
        'profession' => $_POST['profession']
    ];

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);
        switch ($acte_suivant) {
            case 'naissance':
                header('Location: demand_birth_certificate.php');
                exit;
            case 'mariage':
                header('Location: demand_marriage.php');
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
    <title>Demande - Acte de Décès</title>
    <style>
        /* ... styles existants ... */
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

        input[type="text"],
        input[type="date"],
        select {
            width: 80%;
            padding: 10px 15px;
            border: 2px solid #ff8008;
            border-radius: 8px;
            background-color: #fff;
            font-size: 0.95rem;
            color: #333;
            max-width: 100%;
        }

        input:focus, select:focus {
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
            min-width: 240px;
            max-width: 48%;
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

        .back-button {
            background: #999;
            margin-top: 10px;
        }

        .back-button:hover {
            background: #777;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 1.6rem;
            }

            .col {
                max-width: 100%;
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
    <h2>Acte de Décès</h2>
    <form method="post">
        <h3>Informations sur le défunt</h3>

        <div class="row">
            <div class="col form-group">
                <label>Nom</label>
                <input type="text" name="nom_defunt" required>
            </div>
            <div class="col form-group">
                <label>Prénom</label>
                <input type="text" name="prenom_defunt" required>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" required>
            </div>
            <div class="col form-group">
                <label>Lieu de naissance</label>
                <input type="text" name="lieu_naissance" required>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Genre</label>
                <select name="genre" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="Masculin">Masculin</option>
                    <option value="Féminin">Féminin</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="col form-group">
                <label>Profession</label>
                <input type="text" name="profession" required>
            </div>
        </div>

        <h3>Détails du décès</h3>
        <div class="row">
            <div class="col form-group">
                <label>Date de décès</label>
                <input type="date" name="date_deces" required>
            </div>
            <div class="col form-group">
                <label>Lieu de décès</label>
                <input type="text" name="lieu_deces" required>
            </div>
            <div class="col form-group">
                <label>Cause du décès</label>
                <input type="text" name="cause" required>
            </div>
        </div>

        <button type="submit">Soumettre la demande</button>
        <a href="demande_etape2.php"><button type="button" class="back-button">← Retour</button></a>
    </form>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: <?= json_encode($_SESSION['error']) ?>,
        confirmButtonColor: '#ff8008'
    });
</script>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

</body>
</html>

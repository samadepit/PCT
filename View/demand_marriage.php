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

    if ($husband_birth_id && $wife_birth_id) {
        $_SESSION['donnees_actes']['mariage'] = [
            'husband_birth_id' => $husband_birth_id,
            'wife_birth_id' => $wife_birth_id,
            'marriage_date' => $_POST['marriage_date'],
            'marriage_place' => $_POST['marriage_place'],
            'number_children' => $_POST['number_children'],
            'statut_marriage' => $_POST['statut_marriage']
        ];
    } else {
        if (!$husband_birth_id || !$wife_birth_id) {
            $_SESSION['error'] = !$husband_birth_id 
                ? "Acte de naissance du mari introuvable" 
                : "Acte de naissance de la femme introuvable";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Demande - Acte de Mariage</title>
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

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 70%;
            padding: 10px 15px;
            border: 2px solid #ff8008;
            border-radius: 8px;
            background-color: #fff;
            font-size: 0.95rem;
            color: #333;
        }

        input:focus {
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
    <h2>Acte de Mariage</h2>
    <form method="post">
        <h3>Informations sur le mari</h3>
        <div class="row">
            <div class="col form-group">
                <label>Nom</label>
                <input type="text" name="husband_lastname" required>
            </div>
            <div class="col form-group">
                <label>Prénom</label>
                <input type="text" name="husband_firstname" required>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Date de naissance</label>
                <input type="date" name="husband_birth_date" required>
            </div>
            <div class="col form-group">
                <label>Lieu de naissance</label>
                <input type="text" name="husband_birth_place" required>
            </div>
        </div>

        <h3>Informations sur la femme</h3>
        <div class="row">
            <div class="col form-group">
                <label>Nom</label>
                <input type="text" name="wife_lastname" required>
            </div>
            <div class="col form-group">
                <label>Prénom</label>
                <input type="text" name="wife_firstname" required>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Date de naissance</label>
                <input type="date" name="wife_birth_date" required>
            </div>
            <div class="col form-group">
                <label>Lieu de naissance</label>
                <input type="text" name="wife_birth_place" required>
            </div>
        </div>

        <h3>Détails du mariage</h3>
        <div class="row">
            <div class="col form-group">
                <label>Date du mariage</label>
                <input type="date" name="marriage_date" required>
            </div>
            <div class="col form-group">
                <label>Lieu du mariage</label>
                <input type="text" name="marriage_place" required>
            </div>
            <div class="col form-group">
                <label>Statut du mariage</label>
                <input type="text" name="statut_marriage" required>
            </div>
            <div class="col form-group">
                <label>Nombre d'enfants</label>
                <input type="number" name="number_children" min="1" required>
                <script>
                    document.querySelector('form').addEventListener('submit', function (e) {
                        const nb = document.querySelector('input[name="number_children"]').value;
                        if (nb <= 0 || !Number.isInteger(Number(nb))) {
                            e.preventDefault();
                            alert("Le nombre d'enfants doit être un entier strictement supérieur à 0.");
                        }
                    });
                </script>
            </div>
        </div>

        <button type="submit">Soumettre la demande</button>
        <a href="demande_etape2.php"><button type="button" class="back-button">← Retour</button></a>
    </form>
</div>
<?php if (!empty($_SESSION['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '<?= $_SESSION["error"] ?>',
        confirmButtonColor: '#ff8008'
    });
</script>
<?php unset($_SESSION['error']); endif; ?>
</body>
</html>

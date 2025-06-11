<?php
session_start();

require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';

$naissanceController = new NaissanceController();
$demandeController = new DemandeController();
$traitementController = new ActeDemandeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['mariage'] = [
        'nom_epoux' => $_POST['nom_epoux'] ?? '',
        'prenom_epoux' => $_POST['prenom_epoux'] ?? '',
        'date_naissance_epoux' => $_POST['date_naissance_epoux'] ?? '',
        'lieu_naissance_epoux' => $_POST['lieu_naissance_epoux'] ?? '',
        'nationalite_epoux' => $_POST['nationalite_epoux'] ?? '',
        'situation_matrimoniale_epoux' => $_POST['situation_matrimoniale_epoux'] ?? '',
        'temoin_epoux' => $_POST['temoin_epoux'] ?? '',
        'profession_epoux' => $_POST['profession_epoux'] ?? '',
        'piece_identite_epoux' => $_POST['piece_identite_epoux'] ?? '',
        'certificat_residence_epoux' => $_POST['certificat_residence_epoux'] ?? '',

        'nom_epouse' => $_POST['nom_epouse'] ?? '',
        'prenom_epouse' => $_POST['prenom_epouse'] ?? '',
        'date_naissance_epouse' => $_POST['date_naissance_epouse'] ?? '',
        'lieu_naissance_epouse' => $_POST['lieu_naissance_epouse'] ?? '',
        'nationalite_epouse' => $_POST['nationalite_epouse'] ?? '',
        'situation_matrimoniale_epouse' => $_POST['situation_matrimoniale_epouse'] ?? '',
        'temoin_epouse' => $_POST['temoin_epouse'] ?? '',
        'profession_epouse' => $_POST['profession_epouse'] ?? '',
        'piece_identite_epouse'=> $_POST['piece_identite_epouse'] ?? '',
        'certificat_residence_epouse'=> $_POST['certificat_residence_epouse'] ?? '',

        'date_mariage' => $_POST['date_mariage'] ?? '',
        'lieu_mariage' => $_POST['lieu_mariage'] ?? ''
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

    $_SESSION['donnees_actes']['mariage']['piece_identite_epoux'] = saveTempFile($_FILES['piece_identite_epoux']);
    $_SESSION['donnees_actes']['mariage']['certificat_residence_epoux'] = saveTempFile($_FILES['certificat_residence_epoux']);
    $_SESSION['donnees_actes']['mariage']['piece_identite_epouse'] = saveTempFile($_FILES['piece_identite_epouse']);
    $_SESSION['donnees_actes']['mariage']['certificat_residence_epouse'] = saveTempFile($_FILES['certificat_residence_epouse']);


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

        input[type="file"],
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

        select:focus,
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
    <form method="post" enctype="multipart/form-data">
    <h3>Informations sur le conjoint</h3>
    <div class="row">
        <div class="col form-group">
            <label>Nom</label>
            <input type="text" name="nom_epoux" required>
        </div>
        <div class="col form-group">
            <label>Prénom</label>
            <input type="text" name="prenom_epoux" required>
        </div>
    </div>
    <div class="row">
        <div class="col form-group">
            <label>Date de naissance</label>
            <input type="date" name="date_naissance_epoux" required>
        </div>
        <div class="col form-group">
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance_epoux" required>
        </div>
    </div>
    <div class="row">
        <div class="col form-group">
            <label>Nationalité</label>
            <input type="text" name="nationalite_epoux" required>
        </div>
        <div class="col form-group">
            <label>Situation matrimoniale</label>
            <select name="situation_matrimoniale_epoux" id="relation" required>
                <option value="">-- Sélectionner --</option>
                <option value="celibataire">Celibataire</option>
                <option value="veuf">Veuf</option>
                <option value="divorcé">Divorcé</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col form-group">
            <label>Profession</label>
            <input type="text" name="profession_epoux" required>
        </div>
        <div class="col form-group">
            <label>Témoin</label>
            <input type="text" name="temoin_epoux" required>
        </div>
    </div>
    <div class="row"> 
            <div class="col form-group">
                <label>Pièce d'identité de l'epoux (PDF/JPEG/PNG max 5MB) :</label>
                <input type="file" name="piece_identite_epoux" accept="application/pdf,image/jpeg,image/png" required>
            </div>
            <div class="col form-group"> 
                <label>certificat de residence de l'epoux  (PDF/JPEG/PNG max 5MB) :</label>
                <input type="file" name="certificat_residence_epoux" accept="application/pdf,image/jpeg,image/png" required>
            </div>
     </div>

    <h3>Informations sur la conjointe</h3>
    <div class="row">
        <div class="col form-group">
            <label>Nom</label>
            <input type="text" name="nom_epouse" required>
        </div>
        <div class="col form-group">
            <label>Prénom</label>
            <input type="text" name="prenom_epouse" required>
        </div>
    </div>
    <div class="row">
        <div class="col form-group">
            <label>Date de naissance</label>
            <input type="date" name="date_naissance_epouse" required>
        </div>
        <div class="col form-group">
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance_epouse" required>
        </div>
    </div>
    <div class="row">
        <div class="col form-group">
            <label>Nationalité</label>
            <input type="text" name="nationalite_epouse" required>
        </div>
        <div class="col form-group">
            <label>Situation matrimoniale</label>
            <select name="situation_matrimoniale_epouse" id="relation" required>
                <option value="">-- Sélectionner --</option>
                <option value="celibataire">Celibataire</option>
                <option value="veuf">Veuf</option>
                <option value="divorcé">Divorcé</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col form-group">
            <label>Profession</label>
            <input type="text" name="profession_epouse" required>
        </div>
        <div class="col form-group">
            <label>Témoin</label>
            <input type="text" name="temoin_epouse" required>
        </div>
    </div>
    <div class="row"> 
            <div class="col form-group">
                <label>Pièce d'identité de l'epouse (PDF/JPEG/PNG max 5MB) :</label>
                <input type="file" name="piece_identite_epouse" accept="application/pdf,image/jpeg,image/png" required>
            </div>
            <div class="col form-group"> 
                <label>certificat de residence de l'epoux (PDF/JPEG/PNG max 5MB) :</label>
                <input type="file" name="certificat_residence_epouse" accept="application/pdf,image/jpeg,image/png" required>
            </div>
     </div>

    <h3>Détails du mariage</h3>
    <div class="row">
        <div class="col form-group">
            <label>Date du mariage</label>
            <input type="date" name="date_mariage" required>
        </div>
        <div class="col form-group">
            <label>Lieu du mariage</label>
            <input type="text" name="lieu_mariage" value="Ouangolodougou" readonly required>
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

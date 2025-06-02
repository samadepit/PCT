<?php
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/demandController.php';

$actedemandeController = new ActeDemandeController();
$demandeController = new DemandeController();

if (!isset($_GET['code_demande'])) {
    echo "ID de la demande manquant.";
    exit;
}

$id = $_GET['id'] ?? null;
$code_demande = $_GET['code_demande'];
$demande = $actedemandeController->getCertificateById($code_demande);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $motif = $_POST['motif'] ?? null;

    if ($action === 'valider') {
        $demandeController->updateStatut($code_demande, 'valider');
        $actedemandeController->ValidateByAgent($id, $code_demande);
    } elseif ($action === 'rejeter') {
        $demandeController->updateStatut($code_demande, 'rejeter', $motif);
    }

    header('Location: list_demand.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la demande</title>
    <link rel="stylesheet" href="../Assets/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
        }

        header {
            background-color: #1e293b;
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: start;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        header img {
            height: 45px;
            margin-right: 20px;
        }

        header h1 {
            font-size: 20px;
            margin: 0;
            font-weight: 500;
        }


        .top-header {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .top-header img {
            height: 60px;
        }

        .top-header h1 {
            font-size: 22px;
            color: #1f2937;
            font-weight: bold;
            flex: 1;
            text-align: center;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #f97316;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 25px;
            color: #1e293b;
            font-weight: 600;
        }

        p {
            margin: 12px 0;
            font-size: 15px;
            color: #334155;
        }

        label {
            font-weight: 600;
            color: #0f172a;
        }

        textarea {
            width: 100%;
            height: 120px;
            margin-top: 12px;
            margin-bottom: 20px;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            resize: vertical;
        }

        .btns {
            display: flex;
            gap: 20px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        button {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .valider {
            background-color: #10b981;
            color: white;
        }

        .valider:hover {
            background-color: #059669;
            transform: scale(1.03);
        }

        .rejeter {
            background-color: #ef4444;
            color: white;
        }

        .rejeter:hover {
            background-color: #dc2626;
            transform: scale(1.03);
        }

        .info-block {
            background-color: #f9fafb;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-block p {
            margin: 6px 0;
        }

        .btns-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 25px;
    flex-wrap: wrap;
        }

        .retour {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
        }

        .retour:hover {
            background-color: #2563eb;
            transform: scale(1.03);
        }

        .btns {
            display: flex;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .top-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                gap: 10px;
                text-align: left;
            }

            .top-header h1 {
                text-align: left;
                font-size: 18px;
            }

            .logout-btn {
                align-self: flex-end;
                padding: 8px 16px;
                font-size: 14px;
            }

            .container {
                width: 90%;
                padding: 20px;
                margin: 20px auto;
            }

            h2 {
                font-size: 20px;
            }

            .info-block {
                padding: 15px;
            }

            label, p {
                font-size: 14px;
            }

            textarea {
                height: 100px;
                font-size: 13px;
            }

            .btns-wrapper {
                flex-direction: column-reverse;
                gap: 20px;
                align-items: stretch;
            }

            .btns {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .btns button,
            .retour {
                width: 100%;
                font-size: 14px;
                padding: 12px;
            }
        }



    </style>
</head>
<body>
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
        <h1>Portail des Agents de l'état civil</h1>
        <a href="index.php?page=logout" class="logout-btn">Déconnexion</a>
    </div>

    <div class="container">
        <h2>Informations de la demande</h2>

        <div class="info-block">
            <p><label>Demandeur :</label> <?= htmlspecialchars($demande['nom_demandeur']) ?> <?= htmlspecialchars($demande['prenom_demandeur']) ?></p>
            <p><label>Lien avec la personne concernée :</label> <?= htmlspecialchars($demande['relation_avec_beneficiaire']) ?></p>
            <p><label>Type d'acte :</label> <?= htmlspecialchars($demande['type_acte']) ?></p>

            <?php if ($demande['type_acte'] === 'naissance'): ?>
                <p><label>Personne concernée :</label> <?= htmlspecialchars($demande['nom_beneficiaire']) ?> <?= htmlspecialchars($demande['prenom_beneficiaire']) ?>, née le <?= htmlspecialchars($demande['date_naissance']) ?> à <?= htmlspecialchars($demande['lieu_naissance']) ?></p>
                <p><label>Père :</label> <?= htmlspecialchars($demande['nom_pere']) ?> <?= htmlspecialchars($demande['prenom_pere']) ?> (<?= htmlspecialchars($demande['profession_pere']) ?>)</p>
                <p><label>Mère :</label> <?= htmlspecialchars($demande['nom_mere']) ?> <?= htmlspecialchars($demande['prenom_mere']) ?> (<?= htmlspecialchars($demande['profession_mere']) ?>)</p>

            <?php elseif ($demande['type_acte'] === 'mariage'): ?>
                <?php
                    $dateNaissanceHomme = new DateTime($demande['age_homme']);
                    $dateNaissanceFemme = new DateTime($demande['age_femme']);
                    $aujourdhui = new DateTime();
                    $ageHomme = $aujourdhui->diff($dateNaissanceHomme)->y;
                    $ageFemme = $aujourdhui->diff($dateNaissanceFemme)->y;
                ?>
                <p><label>Mari :</label> <?= htmlspecialchars($demande['nom_mari']) ?> <?= htmlspecialchars($demande['prenom_mari']) ?> (<?= $ageHomme ?> ans)</p>
                <p><label>Femme :</label> <?= htmlspecialchars($demande['nom_femme']) ?> <?= htmlspecialchars($demande['prenom_femme']) ?> (<?= $ageFemme ?> ans)</p>
                <p><label>Date et lieu de mariage :</label> <?= htmlspecialchars($demande['date_mariage']) ?> à <?= htmlspecialchars($demande['lieu_mariage']) ?></p>

            <?php elseif ($demande['type_acte'] === 'deces'): ?>
                <p><label>Défunt :</label> <?= htmlspecialchars($demande['nom_defunt']) ?> <?= htmlspecialchars($demande['prenom_defunt']) ?></p>
            <?php endif; ?>
        </div>
        <!-- BOUTON RETOUR AJOUTÉ ICI -->
    
        <form method="POST">
            <label for="motif">Motif de rejet (obligatoire si rejet) :</label>
            <textarea name="motif" id="motif" placeholder="Expliquer pourquoi la demande est rejetée..."></textarea>
            
            <div class="btns-wrapper">
                <a href="list_demand.php?id=<?= urlencode($id) ?>" class="retour">← Retour à la liste</a>
                <div class="btns">
                    <button type="submit" name="action" value="valider" class="valider">✅ Valider</button>
                    <button type="submit" name="action" value="rejeter" class="rejeter">❌ Rejeter</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

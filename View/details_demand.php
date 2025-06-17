<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../service/date_convert.php';
require_once __DIR__ . '/../service/mail_functions.php';

$id = $_GET['id'] ?? null;
$code_demande =  $_GET['code_demande'] ?? null;
if (empty($id) || empty($code_demande)) {
    $_SESSION['erreur'] = "Accès invalide. Vous avez été redirigé vers la page de connexion.";
    header("Location: login.php");
    exit;
}
$actedemandeController = new ActeDemandeController();
$demandeController = new DemandeController();
$demande = $actedemandeController->getCertificateById($code_demande);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $motif = $_POST['motif'] ?? null;

    if ($action === 'valider') {
        $demandeController->updateStatut($code_demande, 'valider');
        $actedemandeController->ValidateByAgent($id, $code_demande);
        if (!empty($demande['email_demandeur'])) {
            notifierDemandeur($demande['email_demandeur'], $code_demande, 'valide');
        }
        $_SESSION['alert'] = 'valide';
    } elseif ($action === 'rejeter') {
        $demandeController->updateStatut($code_demande, 'rejeter', $motif);
        if (!empty($demande['email_demandeur'])) {
            notifierDemandeur($demande['email_demandeur'], $code_demande, 'rejete');
        }
        $_SESSION['alert'] = 'rejete';
        
    }

    header('Location: list_demand.php?id=' . urlencode($id));
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
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card h3 {
            margin-top: 0;
        }
        .flex {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .section {
            flex: 1;
        }
        .dropdown {
            margin-top: 10px;
        }
        summary {
            font-weight: bold;
            cursor: pointer;
        }
        img.preview {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            margin-top: 10px;
        }
        :root {
            --bg-light: #f1f5f9;
            --bg-white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #facc15;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
        }

        .top-header {
            background-color: var(--bg-white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .top-header img {
            height: 60px;
        }

        .top-header h1 {
            font-size: 24px;
            color: var(--text-dark);
            font-weight: 700;
            flex: 1;
            text-align: center;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: var(--warning);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #eab308;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
            background-color: var(--bg-white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: var(--text-dark);
            text-align: center;
        }

        .info-block {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 12px;
            border-left: 6px solid var(--primary);
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        }

        .info-block p {
            margin: 12px 0;
            font-size: 16px;
            color: var(--text-muted);
        }

        .info-block label {
            font-weight: 600;
            color: var(--text-dark);
            display: inline-block;
            min-width: 230px;
        }

        textarea {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            font-size: 15px;
            border: 1px solid #cbd5e1;
            resize: vertical;
            margin-top: 12px;
            margin-bottom: 25px;
        }

        .btns-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btns {
            display: flex;
            gap: 20px;
        }

        button, .retour {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            border: none;
            text-decoration: none;
        }

        .valider {
            background-color: var(--success);
            color: white;
        }

        .valider:hover {
            background-color: #059669;
        }

        .rejeter {
            background-color: var(--danger);
            color: white;
        }

        .rejeter:hover {
            background-color: #dc2626;
        }

        .retour {
            background-color: var(--primary);
            color: white;
        }

        .retour:hover {
            background-color: #2563eb;
        }

        @media (max-width: 768px) {
            .top-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                gap: 10px;
                text-align: left;
            }

            .container {
                padding: 25px;
                width: 90%;
            }

            h2 {
                font-size: 22px;
            }

            .info-block {
                padding: 20px;
            }

            textarea {
                height: 100px;
            }

            .btns-wrapper {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .btns, .btns button, .retour {
                width: 100%;
            }

            .btns {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
        <h1>Portail des Agents de l'État Civil</h1>
        <a href="index.php?page=logout" class="logout-btn">Déconnexion</a>
    </div>

    <div class="container">
        <h2>Détails de la demande</h2>

        <div class="info-block">
            <p><strong>👤  Nom :</strong> <?= htmlspecialchars($demande['nom_demandeur']) ?> <?= htmlspecialchars($demande['prenom_demandeur']) ?></p>
            <p><strong>Lieu de residence :</strong> <?= htmlspecialchars($demande['lieu_residence']) ?></p>
            <p><strong>Numero de telephone  :</strong> <?= htmlspecialchars($demande['numero_demandeur']) ?></p>
        <p><strong>Lien avec le bénéficiaire :</strong> <?= htmlspecialchars($demande['relation_avec_beneficiaire']) ?></p>
        <p><strong>Type d'acte  demandé:</strong> <?= htmlspecialchars($demande['type_acte']) ?></p>
        <details class="dropdown">
            <summary>📷 Voir la piece du demandeur</summary>
            <img src="<?= htmlspecialchars($demande['piece_identite_demandeur']) ?>" alt="Photo du demandeur" class="preview">
        </details>
    </div>

    <?php if ($demande['type_acte'] === 'naissance'): ?>
        <div class="card flex">
            <div class="section">
                <h3>Informations Naissance</h3>
                <p><strong>Nom :</strong> <?= htmlspecialchars($demande['nom_beneficiaire']) ?> <?= htmlspecialchars($demande['prenom_beneficiaire']) ?></p>
                <p><strong>Date de naissance :</strong> <?=$dateConvertie = convertirDateEnFrancais( htmlspecialchars($demande['date_naissance'])) ?></p>
                <p><strong>Lieu de naissance :</strong> <?= htmlspecialchars($demande['lieu_naissance']) ?></p>
                <p><strong>Père :</strong> <?= htmlspecialchars($demande['nom_pere']) ?> <?= htmlspecialchars($demande['prenom_pere']) ?> (<?= htmlspecialchars($demande['profession_pere']) ?>)</p>
                <p><strong>Mère :</strong> <?= htmlspecialchars($demande['nom_mere']) ?> <?= htmlspecialchars($demande['prenom_mere']) ?> (<?= htmlspecialchars($demande['profession_mere']) ?>)</p>
            </div>
            <div class="section">
                <details class="dropdown">
                    <summary>📑 Certificat de naissance</summary>
                    <img src="<?= htmlspecialchars($demande['certificat_de_naissance']) ?>" alt="Certificat de naissance" class="preview">
                </details>
                <details class="dropdown">
                    <summary>📄 Pièce identité père</summary>
                    <img src="<?= htmlspecialchars($demande['piece_identite_pere']) ?>" alt="CNI père" class="preview">
                </details>
                <details class="dropdown">
                    <summary>📄 Pièce identité mère</summary>
                    <img src="<?= htmlspecialchars($demande['piece_identite_mere']) ?>" alt="CNI mère" class="preview">
                </details>
            </div>
        </div>

    <?php elseif ($demande['type_acte'] === 'deces'): ?>
        <h3>Informations Décès</h3>
        <div class="card flex">
            <div class="section">
                <p><strong>Nom du défunt :</strong> <?= htmlspecialchars($demande['nom_defunt']) ?> <?= htmlspecialchars($demande['prenom_defunt']) ?></p>
                <p><strong>Date  de naissance :</strong> <?= $dateConvertie = convertirDateEnFrancais(htmlspecialchars($demande['date_naissance_defunt'])) ?></p>
                <p><strong>Lieu naissance :</strong> <?= htmlspecialchars($demande['lieu_naissance_defunt']) ?> </p>
                <p><strong>Date de deces :</strong> <?= $dateConvertie = convertirDateEnFrancais(htmlspecialchars($demande['date_deces'])) ?></p>
                <p><strong>Lieu de deces :</strong> <?= htmlspecialchars($demande['lieu_deces']) ?></p>
                <p><strong>Cause  du deces:</strong> <?= htmlspecialchars($demande['cause']) ?> </p>
                <p><strong>Genre :</strong> <?= htmlspecialchars($demande['defunt_genre']) ?> </p>
                <p><strong>Profession du defunt:</strong> <?= htmlspecialchars($demande['defunt_profession']) ?></p>
            </div>
            <div class="section">
                <details class="dropdown">
                    <summary>📑 Certificat de décès</summary>
                    <img src="<?= htmlspecialchars($demande['certificat_medical_deces']) ?>" alt="Certificat de décès" class="preview">
                </details>
                <details class="dropdown">
                    <summary>📄 Carte d'identité du défunt</summary>
                    <img src="<?= htmlspecialchars($demande['piece_identite_defunt']) ?>" alt="CNI défunt" class="preview">
                </details>
            </div>
        </div>

    <?php elseif ($demande['type_acte'] === 'mariage'): ?>
        <div class="card flex">
            <div class="section">
                <h3>Épouse</h3>
                <p><strong>Nom et prénom :</strong> <?= htmlspecialchars($demande['nom_epouse']) ?> <?= htmlspecialchars($demande['prenom_epouse']) ?></p>
                <p><strong>Date de naissance :</strong> <?=$dateConvertie = convertirDateEnFrancais(htmlspecialchars($demande['date_naissance_epouse'])) ?> </p>
                <p><strong>Lieu de naissance :</strong> <?= htmlspecialchars($demande['lieu_naissance_epouse']) ?></p>
                <p><strong>Nationalité :</strong> <?= htmlspecialchars($demande['nationalite_epouse']) ?> </p>
                <p><strong>Situation matrimonial :</strong> <?= htmlspecialchars($demande['situation_matrimoniale_epouse']) ?> </p>
                <details class="dropdown">
                    <summary>📄 Pièce d'identité</summary>
                    <img src="<?= htmlspecialchars($demande['piece_identite_epouse']) ?>" alt="CNI épouse" class="preview">
                </details>
                <details class="dropdown">
                    <summary>🏠 Carte de résidence</summary>
                    <img src="<?= htmlspecialchars($demande['certificat_residence_epouse']) ?>" alt="Carte de résidence épouse" class="preview">
                </details>
            </div>
            <div class="section">
                <h3>Époux</h3>
                <p><strong>Nom et prénom:</strong> <?= htmlspecialchars($demande['nom_epoux']) ?> <?= htmlspecialchars($demande['prenom_epoux']) ?></p>
                <p><strong>Date de naissance :</strong> <?=$dateConvertie = convertirDateEnFrancais(htmlspecialchars($demande['date_naissance_epoux'])) ?> </p>
                <p><strong>Lieu de naissance :</strong> <?= htmlspecialchars($demande['lieu_naissance_epoux']) ?></p>
                <p><strong>Nationalité :</strong> <?= htmlspecialchars($demande['nationalite_epoux']) ?> </p>
                <p><strong>Situation matrimonial :</strong> <?= htmlspecialchars($demande['situation_matrimoniale_epoux']) ?> </p>
                <details class="dropdown">
                    <summary>📄 Pièce d'identité</summary>
                    <img src="<?= htmlspecialchars($demande['piece_identite_epoux']) ?>" alt="CNI époux" class="preview">
                </details>
                <details class="dropdown">
                    <summary>🏠 Carte de résidence</summary>
                    <img src="<?= htmlspecialchars($demande['certificat_residence_epoux']) ?>" alt="Carte de résidence époux" class="preview">
                </details>
            </div>
        </div>
    <?php endif; ?>
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
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
        const action = e.submitter?.value;
        const motif = document.getElementById('motif').value.trim();

        if (action === 'rejeter' && !motif) {
            alert("Veuillez entrer un motif pour rejeter la demande.");
            e.preventDefault();
            return;
        }

        setTimeout(() => {
        const validerBtn = this.querySelector('.valider');
        const rejeterBtn = this.querySelector('.rejeter');

        if (validerBtn) {
            validerBtn.disabled = true;
            validerBtn.textContent = '⏳ Traitement...';
        }

        if (rejeterBtn) {
            rejeterBtn.disabled = true;
            rejeterBtn.textContent = '⏳ Traitement...';
        }
    }, 0);
    });
    </script>
</body>
</html>

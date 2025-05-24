<?php
session_start();
require_once __DIR__ . '/../Controller/certificatedemandController.php';

$certificate_demandController = new ActeDemandeController();
$certificates = [];
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code_demande']);
    if (!empty($code)) {
        try {
            $certificates = $certificate_demandController->get_certificateby_Demande($code);
            if (empty($certificates)) {
                $_SESSION['erreur'] = "Aucun acte trouvé pour ce code de demande.";
            } else {
                $_SESSION['actes'] = $certificates;
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = "Erreur lors de la récupération des actes : " . $e->getMessage();
        }
    } else {
        $_SESSION['erreur'] = "Veuillez entrer un code de demande.";
    }

    header("Location: " . $_SERVER['PHP_SELF']); //Redirige vers la meme page
    exit();
}

if (isset($_SESSION['actes'])) {
    $certificates = $_SESSION['actes'];
    unset($_SESSION['actes']);
}

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
?>


<form method="POST">
    <label for="code_demande">Code de la demande :</label>
    <input type="text" id="code_demande" name="code_demande" required>
    <button type="submit">🔍 Rechercher</button>
</form>

<?php if (!empty($erreur)): ?>
    <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<?php if (!empty($certificates)): ?>
    <h3>Actes trouvés :</h3>
    <?php foreach ($certificates as $index => $certificate): ?>
        <div class="acte">
            <strong>Type d'acte :</strong> <?= htmlspecialchars($certificate['type_acte']) ?><br>
            <strong>Statut de la demande :</strong> <?= htmlspecialchars($certificate['statut']) ?><br>

            <?php if (strtolower($certificate['statut']) === 'valider'): ?>
                <form method="POST" action="impression.php" target="_blank">
                    <input type="hidden" name="code_demande" value="<?= htmlspecialchars($code) ?>">
                    <input type="hidden" name="type_acte" value="<?= htmlspecialchars($certificate['type_acte']) ?>">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button class="imprimer-btn" type="submit">🖨️ Imprimer</button>
                </form>
            <?php endif; ?>

            <details>
                <summary>Voir les détails</summary>
                <div>
                    <?php if (!empty($certificate['nom_beneficiaire'])): ?>
                        <h4>Naissance</h4>
                        Nom : <?= htmlspecialchars($certificate['nom_beneficiaire']) ?> <?= htmlspecialchars($certificate['prenom_beneficiaire']) ?><br>
                        Né(e) le : <?= htmlspecialchars($certificate['date_naissance']) ?> à <?= htmlspecialchars($certificate['lieu_naissance']) ?><br>
                        Père : <?= htmlspecialchars($certificate['prenom_pere']) ?> <?= htmlspecialchars($certificate['nom_pere']) ?> (<?= htmlspecialchars($certificate['profession_pere']) ?>)<br>
                        Mère : <?= htmlspecialchars($certificate['prenom_mere']) ?> <?= htmlspecialchars($certificate['nom_mere']) ?> (<?= htmlspecialchars($certificate['profession_mere']) ?>)<br>
                        Enregistré le : <?= htmlspecialchars($certificate['naissance_date_creation']) ?><br>
                    <?php endif; ?>

                    <?php if (!empty($certificate['date_mariage'])): ?>
                        <h4>Mariage</h4>
                        Date : <?= htmlspecialchars($certificate['date_mariage']) ?><br>
                        Lieu : <?= htmlspecialchars($certificate['lieu_mariage']) ?><br>
                        Marié : <?= htmlspecialchars($certificate['prenom_mari']) ?> <?= htmlspecialchars($certificate['nom_mari']) ?><br>
                        Mariée : <?= htmlspecialchars($certificate['prenom_femme']) ?> <?= htmlspecialchars($certificate['nom_femme']) ?><br>
                        Enregistré le : <?= htmlspecialchars($certificate['mariage_date_creation']) ?><br>
                    <?php endif; ?>

                    <?php if (!empty($certificate['date_deces'])): ?>
                        <h4>Décès</h4>
                        Nom du défunt : <?= htmlspecialchars($certificate['prenom_defunt']) ?> <?= htmlspecialchars($certificate['nom_defunt']) ?><br>
                        Date : <?= htmlspecialchars($certificate['date_deces']) ?><br>
                        Lieu : <?= htmlspecialchars($certificate['lieu_deces']) ?><br>
                        Cause : <?= htmlspecialchars($certificate['cause']) ?><br>
                        Genre : <?= htmlspecialchars($certificate['genre']) ?><br>
                        Profession : <?= htmlspecialchars($certificate['profession']) ?><br>
                        Enregistré le : <?= htmlspecialchars($certificate['deces_date_creation']) ?><br>
                    <?php endif; ?>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    form {
        background: white;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    button {
        padding: 10px 20px;
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #2563eb;
    }

    .acte {
        border-radius: 16px;
        padding: 25px 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 35px;
        width: 100%;
        max-width: 800px;
        line-height: 1.7;
        background-color: #fff;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .acte:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .acte.en-attente {
        border-left: 8px solid orange;
        background-color: #fff7ed;
    }

    .acte.valide {
        border-left: 8px solid #10b981;
        background-color: #ecfdf5;
    }

    .acte.autre {
        border-left: 8px solid #d1d5db;
        background-color: #f9fafb;
    }

    .acte-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e5e7eb;
    }

    .acte-header-item {
        flex: 1 1 45%;
        font-weight: bold;
        color: #1f2937;
    }

    .acte h4 {
        margin-top: 25px;
        margin-bottom: 10px;
        color: #4b5563;
        font-size: 18px;
        border-bottom: 1px solid #d1d5db;
        padding-bottom: 5px;
    }

    summary {
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
        margin-bottom: 15px;
    }

    details {
        margin-top: 15px;
    }

    .imprimer-btn {
        background-color: #10b981;
        margin-top: 20px;
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 15px;
    }

    .imprimer-btn:hover {
        background-color: #059669;
    }

    .erreur {
        color: red;
        background: #fee2e2;
        padding: 14px;
        border-radius: 8px;
        max-width: 400px;
        margin-bottom: 20px;
        text-align: center;
        border: 1px solid #fca5a5;
    }

    h3 {
        margin-bottom: 25px;
        color: #111827;
        font-size: 24px;
    }

    p {
        margin: 6px 0;
    }
</style>


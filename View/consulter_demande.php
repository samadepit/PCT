<?php

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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="./assets/css/suivie/style.css">
</head>
<body>
        <div class="search-code-container">
        <h1>🔍 Recherche par Code de Demande</h1>

        <form method="POST" class="search-form">
            <label for="code_demande">Code de la demande :</label>
            <input type="text" id="code_demande" name="code_demande" placeholder="Entrez le code de votre demande" required>
            <button type="submit">🔍 Rechercher</button>
        </form>

      

        <?php if (!empty($erreur)): ?>
            <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <?php if (!empty($certificates)): ?>
            <h3 class="results-title">📋 Actes trouvés :</h3>
            <?php foreach ($certificates as $index => $certificate): ?>
                <div class="acte">
                    <div class="acte-header">
                        <div class="acte-info">
                            <p>
                                <span class="type-icon <?= strtolower($certificate['type_acte']) === 'naissance' ? 'birth-icon' : (strtolower($certificate['type_acte']) === 'mariage' ? 'marriage-icon' : 'death-icon') ?>"></span>
                                <strong>Type d'acte :</strong> <?= htmlspecialchars($certificate['type_acte']) ?>
                            </p>
                            <p>
                                <strong>Statut :</strong> 
                                <span class="statut <?= strtolower(str_replace(' ', '-', $certificate['statut'])) ?>">
                                    <?= htmlspecialchars($certificate['statut']) ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <?php if (strtolower($certificate['statut']) === 'valider'): ?>
                        <form method="POST" action="impression.php" target="_blank" style="margin-top: 15px;">
                            <input type="hidden" name="code_demande" value="<?= htmlspecialchars($code) ?>">
                            <input type="hidden" name="type_acte" value="<?= htmlspecialchars($certificate['type_acte']) ?>">
                            <input type="hidden" name="index" value="<?= $index ?>">
                            <button class="imprimer-btn" type="submit">🖨️ Imprimer le certificat</button>
                        </form>
                    <?php endif; ?>

                    <details>
                        <summary>👁️ Voir les détails complets</summary>
                        <div class="details-content">
                            <?php if (!empty($certificate['nom_beneficiaire'])): ?>
                                <h4>👶 Informations de Naissance</h4>
                                <div class="detail-item">
                                    <strong>Nom complet :</strong> 
                                    <?= htmlspecialchars($certificate['nom_beneficiaire']) ?> <?= htmlspecialchars($certificate['prenom_beneficiaire']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Date de naissance :</strong> 
                                    <?= htmlspecialchars($certificate['date_naissance']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Lieu de naissance :</strong> 
                                    <?= htmlspecialchars($certificate['lieu_naissance']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Père :</strong> 
                                    <?= htmlspecialchars($certificate['prenom_pere']) ?> <?= htmlspecialchars($certificate['nom_pere']) ?> 
                                    (<?= htmlspecialchars($certificate['profession_pere']) ?>)
                                </div>
                                <div class="detail-item">
                                    <strong>Mère :</strong> 
                                    <?= htmlspecialchars($certificate['prenom_mere']) ?> <?= htmlspecialchars($certificate['nom_mere']) ?> 
                                    (<?= htmlspecialchars($certificate['profession_mere']) ?>)
                                </div>
                                <div class="detail-item">
                                    <strong>Date d'enregistrement :</strong> 
                                    <?= htmlspecialchars($certificate['naissance_date_creation']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($certificate['date_mariage'])): ?>
                                <h4>💒 Informations de Mariage</h4>
                                <div class="detail-item">
                                    <strong>Date du mariage :</strong> 
                                    <?= htmlspecialchars($certificate['date_mariage']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Lieu du mariage :</strong> 
                                    <?= htmlspecialchars($certificate['lieu_mariage']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Époux :</strong> 
                                    <?= htmlspecialchars($certificate['prenom_mari']) ?> <?= htmlspecialchars($certificate['nom_mari']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Épouse :</strong> 
                                    <?= htmlspecialchars($certificate['prenom_femme']) ?> <?= htmlspecialchars($certificate['nom_femme']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Date d'enregistrement :</strong> 
                                    <?= htmlspecialchars($certificate['mariage_date_creation']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($certificate['date_deces'])): ?>
                                <h4>⚱️ Informations de Décès</h4>
                                <div class="detail-item">
                                    <strong>Nom du défunt :</strong> 
                                    <?= htmlspecialchars($certificate['prenom_defunt']) ?> <?= htmlspecialchars($certificate['nom_defunt']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Date du décès :</strong> 
                                    <?= htmlspecialchars($certificate['date_deces']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Lieu du décès :</strong> 
                                    <?= htmlspecialchars($certificate['lieu_deces']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Cause du décès :</strong> 
                                    <?= htmlspecialchars($certificate['cause']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Genre :</strong> 
                                    <?= htmlspecialchars($certificate['genre']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Profession :</strong> 
                                    <?= htmlspecialchars($certificate['profession']) ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Date d'enregistrement :</strong> 
                                    <?= htmlspecialchars($certificate['deces_date_creation']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </details>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>










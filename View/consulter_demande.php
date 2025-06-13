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

}

if (isset($_SESSION['actes'])) {
    $certificates = $_SESSION['actes'];
    // unset($_SESSION['actes']);
}

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
?>




<div class="top-header">
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI" />
    <h1>Bienvenue sur le Portail des Demande d'actes d'état civil</h1>
    <nav>
        <a href="dashboard.php" class="nav-btn">Accueil</a>
        <a href="demande_etape1.php" class="nav-btn">Faire une demande</a>
        <a href="consulter_demande.php" class="nav-btn"><span>Suivre une demande</span></a>
    </nav>
</div>

<form method="POST" class="search-form">
    <label for="code_demande">Code de la demande :</label>
    <input type="text" id="code_demande" name="code_demande" required />
    <button type="submit">🔍 Rechercher</button>
</form>

<?php if (!empty($certificates)): ?>
    <h3>Actes trouvés :</h3>
    <?php foreach ($certificates as $index => $certificate): ?>
        <?php
$status = strtolower($certificate['statut'] ?? '');
$canPrint = (
    isset($certificate['statut'], $certificate['est_signer']) &&
    $status === 'valider' &&
    $certificate['est_signer'] == 1 
);

$cssClass = 'autre'; 

if ($status === 'valider') {
    $cssClass = $canPrint ? 'valide' : 'en-attente';
} elseif ($status === 'rejeter') {
    $cssClass = 'rejeter';
}
?>
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
        padding-top: 80px;
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

    form.search-form {
    background: white;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin: 30px auto; /* centré horizontalement + un peu de marge */
    max-width: 400px;
    width: 100%;
    text-align: center;
}

    form.search-form label {
        font-weight: 600;
        font-size: 16px;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        box-sizing: border-box;
    }

    button {
        padding: 10px 20px;
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #2563eb;
    }

    #btn-retour {
        background-color: #ff8008;
        margin-top: 10px;
    }

    #btn-retour:hover {
        background-color: #cc6600;
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
        box-sizing: border-box;
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

    .rejeter {
    border-left: 8px solid red;
    background-color: #ffe5e5;
    
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
        transition: background-color 0.3s ease;
    }

    .imprimer-btn:hover {
        background-color: #059669;
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

<div class="acte <?= $cssClass ?>">
    <div class="acte-header">
        <div class="acte-header-item"><strong>Type d'acte :</strong> <?= htmlspecialchars($certificate['type_acte']) ?></div>
        <div class="acte-header-item">
            <strong>Statut de la demande :</strong>
            <span><?= htmlspecialchars($certificate['statut']) ?></span>
            <?php if ($status === 'rejeter' && !empty($certificate['motif_rejet'])): ?>
                <p style="color: red; margin: 5px 0 0;"><strong>Motif :</strong> <?= htmlspecialchars($certificate['motif_rejet']) ?></p>
            <?php endif; ?>
        </div>
    </div>
            <?php
                $canPrint = (
                    isset($certificate['statut'], $certificate['est_signer']) &&
                    strtolower($certificate['statut']) === 'valider' &&
                    $certificate['est_signer'] == 1 &&
                    $certificate['payer'] == 0
                );
            ?>
            <?php if ($canPrint): ?>
                <form method="POST" action="paiement.php?code_demande=<?= urlencode($code) ?>" target="_blank" class="print-form">
                    <input type="hidden" name="code_demande" value="<?= htmlspecialchars($code) ?>" />
                    <input type="hidden" name="type_acte" value="<?= htmlspecialchars($certificate['type_acte']) ?>" />
                    <input type="hidden" name="index" value="<?= $index ?>" />
                    <button class="imprimer-btn" type="submit"> 💸​ Payez le timbre</button>
                </form>
            <?php endif; ?>

            <details>
                <summary>Voir les détails</summary>
                <div class="details-content">
                    <?php if (!empty($certificate['nom_beneficiaire'])): ?>
                        <h4>Naissance</h4>
                        <p>Nom : <?= htmlspecialchars($certificate['nom_beneficiaire']) ?> <?= htmlspecialchars($certificate['prenom_beneficiaire']) ?></p>
                        <p>Né(e) le : <?= htmlspecialchars($certificate['date_naissance']) ?> à <?= htmlspecialchars($certificate['lieu_naissance']) ?></p>
                        <p>Père : <?= htmlspecialchars($certificate['prenom_pere']) ?> <?= htmlspecialchars($certificate['nom_pere']) ?> (<?= htmlspecialchars($certificate['profession_pere']) ?>)</p>
                        <p>Mère : <?= htmlspecialchars($certificate['prenom_mere']) ?> <?= htmlspecialchars($certificate['nom_mere']) ?> (<?= htmlspecialchars($certificate['profession_mere']) ?>)</p>
                        <p>Enregistré le : <?= htmlspecialchars($certificate['naissance_date_creation']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($certificate['date_mariage'])): ?>
                        <h4>Mariage</h4>
                        <p>Date de demande : <?= htmlspecialchars($certificate['date_mariage']) ?></p>
                        <p>Lieu : <?= htmlspecialchars($certificate['lieu_mariage']) ?></p>
                        <p>Marié : <?= htmlspecialchars($certificate['nom_epoux']) ?> <?= htmlspecialchars($certificate['prenom_epoux']) ?></p>
                        <p>Nationalité  Marié: <?= htmlspecialchars($certificate['nationalite_epoux']) ?></p>
                        <p>Profession de la Marié: <?= htmlspecialchars($certificate['profession_epoux']) ?></p>
                        <p>Témoin du  Marié: <?= htmlspecialchars($certificate['temoin_epoux']) ?></p>
                        <p>Date de naissance du Marié: <?= htmlspecialchars($certificate['date_naissance_epoux']) ?></p>
                        <p>Lieu de naissance du  Marié: <?= htmlspecialchars($certificate['lieu_naissance_epoux']) ?></p>
                        <p>Mariée : <?= htmlspecialchars($certificate['nom_epouse']) ?> <?= htmlspecialchars($certificate['prenom_epouse']) ?></p>
                        <p>Nationalité  Mariée: <?= htmlspecialchars($certificate['nationalite_epouse']) ?></p>
                        <p>Profession de la Mariée: <?= htmlspecialchars($certificate['profession_epouse']) ?></p>
                        <p>Témoin de la  Mariée: <?= htmlspecialchars($certificate['temoin_epouse']) ?></p>
                        <p>Date de naissance de la  Mariée: <?= htmlspecialchars($certificate['date_naissance_epouse']) ?></p>
                        <p>Lieu de naissance de la  Mariée: <?= htmlspecialchars($certificate['lieu_naissance_epouse']) ?></p>
                        <p>Enregistré le : <?= htmlspecialchars($certificate['mariage_date_creation']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($certificate['date_deces'])): ?>
                        <h4>Décès</h4>
                        <p>Nom du défunt : <?= htmlspecialchars($certificate['prenom_defunt']) ?> <?= htmlspecialchars($certificate['nom_defunt']) ?></p>
                        <p>Date : <?= htmlspecialchars($certificate['date_deces']) ?></p>
                        <p>Lieu : <?= htmlspecialchars($certificate['lieu_deces']) ?></p>
                        <p>Cause : <?= htmlspecialchars($certificate['cause']) ?></p>
                        <p>Genre : <?= htmlspecialchars($certificate['genre']) ?></p>
                        <p>Profession : <?= htmlspecialchars($certificate['profession']) ?></p>
                        <p>Enregistré le : <?= htmlspecialchars($certificate['deces_date_creation']) ?></p>
                    <?php endif; ?>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<!-- SweetAlert2 -->
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($erreur)) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: <?= json_encode($erreur) ?>,
            confirmButtonColor: '#ff8008'
        });
        <?php endif; ?>
    });
</script>


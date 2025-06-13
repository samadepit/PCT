<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styleSuivie.css">
</head>

<body>
    <?php
session_start();
//session_destroy();
require_once __DIR__ . '/../Controller/certificatedemandController.php';

$certificate_demandController = new ActeDemandeController();
$certificates = [];
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code_demande']);
    var_dump($code);   
    if (!empty($code)) {
        try {
            $certificates = $certificate_demandController->get_certificateby_Demande($code);
            if (empty($certificates)) {
                $_SESSION['erreur'] = "Aucun acte trouvé pour ce code de demande.";
            } else {
                $_SESSION['actes_'] = $certificates;
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = "Erreur lors de la récupération des actes : " . $e->getMessage();
        }
    } else {
      $_SESSION['erreur'] = "Veuillez entrer un code de demande.";
      
    }

    header("Location: " . $_SERVER['PHP_SELF']); 
    exit();
}

if (isset($_SESSION['actes_'])) {
    $certificates = $_SESSION['actes_'];
    // unset($_SESSION['actes_']);

    // var_dump($certificates);
    // die;
}

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
?>

    <?php
       require_once './partials/header.php'
     ?>

    <form method="POST" class="search-form container">
        <label for="code_demande">Code de la demande :</label>
        <input type="text" id="code_demande" name="code_demande" required />
        <button type="submit">🔍 Rechercher</button>
    </form>

    <?php if (!empty($certificates)): ?>
    <h3 >Actes trouvés :</h3>
    <?php foreach ($certificates as $index => $certificate): ?>
    <?php
    // var_dump($certificate);   
$status = strtolower($certificate['statut'] ?? '');
$canPrint = (

    isset($certificate['statut'], $certificate['est_signer'], $certificate['payer']) &&
    $status === 'valider' &&
    $certificate['est_signer'] == 1 &&
    $certificate['payer'] == 1
);

$cssClass = 'autre'; // par défaut

if ($status === 'valider') {
    $cssClass = $canPrint ? 'valide' : 'en-attente';
} elseif ($status === 'rejeter') {
    $cssClass = 'rejeter';
}
?>
    <div class="acte <?= $cssClass ?>">
        <div class="acte-header">
            <div class="acte-header-item"><strong>Type d'acte :</strong>
                <?= htmlspecialchars($certificate['type_acte']) ?>
            </div>
            <div class="acte-header-item">
                <strong>Statut de la demande :</strong>
                <span><?= htmlspecialchars($certificate['statut']) ?></span>
                <?php if ($status === 'rejeter' && !empty($certificate['motif_rejet'])): ?>
                <p style="color: red; margin: 5px 0 0;"><strong>Motif :</strong>
                    <?= htmlspecialchars($certificate['motif_rejet']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
                $canPrint = (
                    isset($certificate['statut'], $certificate['est_signer'], $certificate['payer']) &&
                    strtolower($certificate['statut']) === 'valider' &&
                    $certificate['est_signer'] == 1 &&
                    $certificate['payer'] == 1
                );
            ?>
        <?php if ($canPrint): ?>
            <?php var_dump($code);  ?>

        <form method="POST" action="impression.php" target="_blank" class="print-form">
            <input type="hidden" name="code_demande" value="<?= htmlspecialchars($code) ?>" />
            <input type="hidden" name="type_acte" value="<?= htmlspecialchars($certificate['type_acte']) ?>" />
            <input type="hidden" name="index" value="<?= $index ?>" />
            <button class="imprimer-btn" type="submit">🖨️ Imprimer</button>
        </form>
        <?php endif; ?>

        <details>
            <summary>Voir les détails</summary>
            <div class="details-content">
                <?php if (!empty($certificate['nom_beneficiaire'])): ?>
                <h4>Naissance</h4>
                <p>Nom : <?= htmlspecialchars($certificate['nom_beneficiaire']) ?>
                    <?= htmlspecialchars($certificate['prenom_beneficiaire']) ?></p>
                <p>Né(e) le : <?= htmlspecialchars($certificate['date_naissance']) ?> à
                    <?= htmlspecialchars($certificate['lieu_naissance']) ?></p>
                <p>Père : <?= htmlspecialchars($certificate['prenom_pere']) ?>
                    <?= htmlspecialchars($certificate['nom_pere']) ?>
                    (<?= htmlspecialchars($certificate['profession_pere']) ?>)</p>
                <p>Mère : <?= htmlspecialchars($certificate['prenom_mere']) ?>
                    <?= htmlspecialchars($certificate['nom_mere']) ?>
                    (<?= htmlspecialchars($certificate['profession_mere']) ?>)</p>
                <p>Enregistré le : <?= htmlspecialchars($certificate['naissance_date_creation']) ?></p>
                <?php endif; ?>

                <?php if (!empty($certificate['date_mariage'])): ?>
                <h4>Mariage</h4>
                <p>Date : <?= htmlspecialchars($certificate['date_mariage']) ?></p>
                <p>Lieu : <?= htmlspecialchars($certificate['lieu_mariage']) ?></p>
                <p>Marié : <?= htmlspecialchars($certificate['prenom_mari']) ?>
                    <?= htmlspecialchars($certificate['nom_mari']) ?></p>
                <p>Mariée : <?= htmlspecialchars($certificate['prenom_femme']) ?>
                    <?= htmlspecialchars($certificate['nom_femme']) ?></p>
                <p>Enregistré le : <?= htmlspecialchars($certificate['mariage_date_creation']) ?></p>
                <?php endif; ?>

                <?php if (!empty($certificate['date_deces'])): ?>
                <h4>Décès</h4>
                <p>Nom du défunt : <?= htmlspecialchars($certificate['prenom_defunt']) ?>
                    <?= htmlspecialchars($certificate['nom_defunt']) ?></p>
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

    <?php
       require_once './partials/footer.php'
     ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    <?php if (!empty($erreur)) : ?>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: <?= json_encode($erreur) ?>,
        confirmButtonColor: '#ff8008'
    });
    <?php endif; ?>
    </script>
</body>

</html>
<?php
session_start();
require_once __DIR__.'/../Controller/certificatedemandController.php';

$certificate_demandController = new ActeDemandeController();
$certificates = [];
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code_demande']);
    if (!empty($code)) {
        try {
            $certificates = $certificate_demandController->get_certificateby_Demande($code);
            if (empty($certificates)) {
                $_SESSION['erreur'] = 'Aucun acte trouvé pour ce code de demande.';
            } else {
                $_SESSION['actes'] = $certificates;
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = 'Erreur lors de la récupération des actes : '.$e->getMessage();
        }
    } else {
        $_SESSION['erreur'] = 'Veuillez entrer un code de demande.';
    }
}

if (isset($_SESSION['actes'])) {
    $certificates = $_SESSION['actes'];
}

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Recherche d'actes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- SweetAlert2 -->
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background-color: #f5f7fa;

    }

    .btn-primary {
        background: #ff8008 !important;
        border: #ff8008 !important;
    }
    </style>
</head>

<body>

         <?php
       require_once './partials/header.php';
?>

    <div class="container">

        <!-- Formulaire de recherche -->

        <div class="container my-4 d-flex justify-content-center">
            <div class="card shadow-sm mb-4 col-md-6">
                <div class="card-body">
                    <h5 class="card-title text-center">Rechercher un acte</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="code_demande" class="form-label">Code de la demande :</label>
                            <input type="text" class="form-control" id="code_demande" name="code_demande" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Affichage des résultats -->
        <?php if (!empty($certificates)) { ?>
        <h3 class="mb-4">Actes trouvés :</h3>

        <?php foreach ($certificates as $index => $certificate) { ?>
        <?php
            $status = strtolower($certificate['statut'] ?? '');
            $canPrint = (
                isset($certificate['statut'], $certificate['est_signer'])
                && $status === 'valider'
                && $certificate['est_signer'] == 1
            );

            $borderClass = match ($status) {
                'valider' => $canPrint ? 'border-success' : 'border-warning',
                'rejeter' => 'border-danger',
                default => 'border-secondary'
            };

            $bgClass = match ($status) {
                'valider' => $canPrint ? 'bg-light' : 'bg-warning-subtle',
                'rejeter' => 'bg-danger-subtle',
                default => 'bg-body'
            };
            ?>

        <div class="card mb-4 shadow-sm border <?php echo $borderClass; ?> <?php echo $bgClass; ?>">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Type d'acte :</strong> <?php echo htmlspecialchars($certificate['type_acte']); ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Statut :</strong>
                        <span class="badge
                                <?php echo $status === 'valider' && $certificate['est_signer'] ? 'bg-success' :
                                    ($status === 'rejeter' ? 'bg-danger' : 'bg-warning'); ?>">
                            <?php echo htmlspecialchars($certificate['statut']); ?>
                            <?php echo $certificate['est_signer'] ? 'et signé' : ''; ?>
                        </span>

                        <?php if ($status === 'rejeter' && !empty($certificate['motif_rejet'])) { ?>
                        <p class="text-danger mt-2"><strong>Motif :</strong>
                            <?php echo htmlspecialchars($certificate['motif_rejet']); ?></p>
                        <?php } ?>
                    </div>
                </div>

                <?php
                        $canPrint = (
                            isset($certificate['statut'], $certificate['est_signer'])
                            && $status === 'valider'
                            && $certificate['est_signer'] == 1
                            && $certificate['payer'] == 0
                        );
            ?>

                <?php if ($canPrint) { ?>
                <form method="POST" action="paiement.php?code_demande=<?php echo urlencode($code); ?>" target="_blank">
                    <input type="hidden" name="code_demande" value="<?php echo htmlspecialchars($code); ?>" />
                    <input type="hidden" name="type_acte"
                        value="<?php echo htmlspecialchars($certificate['type_acte']); ?>" />
                    <input type="hidden" name="index" value="<?php echo $index; ?>" />
                    <button type="submit" class="btn btn-success">💸 Payer le timbre</button>
                </form>
                <?php } ?>

                <!-- Accordion Bootstrap -->
                <div class="accordion mt-4" id="accordion<?php echo $index; ?>">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false"
                                aria-controls="collapse<?php echo $index; ?>">
                                Détails de l’acte
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse"
                            aria-labelledby="heading<?php echo $index; ?>"
                            data-bs-parent="#accordion<?php echo $index; ?>">
                            <div class="accordion-body">
                                <?php if (!empty($certificate['nom_beneficiaire'])) { ?>
                                <h5>Naissance</h5>
                                <p>Nom : <?php echo htmlspecialchars($certificate['nom_beneficiaire']); ?>
                                    <?php echo htmlspecialchars($certificate['prenom_beneficiaire']); ?></p>
                                <p>Né(e) le : <?php echo htmlspecialchars($certificate['date_naissance']); ?> à
                                    <?php echo htmlspecialchars($certificate['lieu_naissance']); ?></p>
                                <p>Père : <?php echo htmlspecialchars($certificate['prenom_pere']); ?>
                                    <?php echo htmlspecialchars($certificate['nom_pere']); ?>
                                    (<?php echo htmlspecialchars($certificate['profession_pere']); ?>)</p>
                                <p>Mère : <?php echo htmlspecialchars($certificate['prenom_mere']); ?>
                                    <?php echo htmlspecialchars($certificate['nom_mere']); ?>
                                    (<?php echo htmlspecialchars($certificate['profession_mere']); ?>)</p>
                                <p>Enregistré le :
                                    <?php echo htmlspecialchars($certificate['naissance_date_creation']); ?></p>
                                <?php } ?>

                                <?php if (!empty($certificate['date_mariage'])) { ?>
                                <h5>Mariage</h5>
                                <p>Date de demande : <?php echo htmlspecialchars($certificate['date_mariage']); ?></p>
                                <p>Lieu : <?php echo htmlspecialchars($certificate['lieu_mariage']); ?></p>
                                <p>Marié : <?php echo htmlspecialchars($certificate['nom_epoux']); ?>
                                    <?php echo htmlspecialchars($certificate['prenom_epoux']); ?></p>
                                <p>Mariée : <?php echo htmlspecialchars($certificate['nom_epouse']); ?>
                                    <?php echo htmlspecialchars($certificate['prenom_epouse']); ?></p>
                                <p>Nationalité Marié(e) :
                                    <?php echo htmlspecialchars($certificate['nationalite_epoux']); ?> /
                                    <?php echo htmlspecialchars($certificate['nationalite_epouse']); ?></p>
                                <p>Profession : <?php echo htmlspecialchars($certificate['profession_epoux']); ?> /
                                    <?php echo htmlspecialchars($certificate['profession_epouse']); ?></p>
                                <p>Témoin : <?php echo htmlspecialchars($certificate['temoin_epoux']); ?> /
                                    <?php echo htmlspecialchars($certificate['temoin_epouse']); ?></p>
                                <p>Date/lieu naissance :
                                    <?php echo htmlspecialchars($certificate['date_naissance_epoux']); ?> /
                                    <?php echo htmlspecialchars($certificate['lieu_naissance_epoux']); ?> <br>
                                    <?php echo htmlspecialchars($certificate['date_naissance_epouse']); ?> /
                                    <?php echo htmlspecialchars($certificate['lieu_naissance_epouse']); ?></p>
                                <p>Enregistré le :
                                    <?php echo htmlspecialchars($certificate['mariage_date_creation']); ?></p>
                                <?php } ?>

                                <?php if (!empty($certificate['date_deces'])) { ?>
                                <h5>Décès</h5>
                                <p>Nom du défunt : <?php echo htmlspecialchars($certificate['prenom_defunt']); ?>
                                    <?php echo htmlspecialchars($certificate['nom_defunt']); ?></p>
                                <p>Date : <?php echo htmlspecialchars($certificate['date_deces']); ?> | Lieu :
                                    <?php echo htmlspecialchars($certificate['lieu_deces']); ?></p>
                                <p>Cause : <?php echo htmlspecialchars($certificate['cause']); ?> | Genre :
                                    <?php echo htmlspecialchars($certificate['genre']); ?> | Profession :
                                    <?php echo htmlspecialchars($certificate['profession']); ?></p>
                                <p>Enregistré le : <?php echo htmlspecialchars($certificate['deces_date_creation']); ?>
                                </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin accordion -->
            </div>
        </div>
        <?php } ?>
        <?php } ?>


        <!-- SweetAlert -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($erreur)) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: <?php echo json_encode($erreur); ?>,
                confirmButtonClass: 'btn btn-warning',
                buttonsStyling: false
            });
            <?php } ?>
        });
        </script>
       
    </div>
      <?php
       require_once './partials/footer.php';
   ?>    

</body>

</html>
<?php
session_start();

require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/marriageController.php';
require_once __DIR__ . '/../Controller/deathController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/requestroController.php';
require_once __DIR__ . '/../service/mail_functions.php';

$birthController = new NaissanceController();
$marriageController = new MarriageController();
$deathController = new DecesController();
$demandController = new DemandeController();
$certificate_demandController = new ActeDemandeController();
$requestroController = new DemandeurController();

$data_certificate = $_SESSION['donnees_actes'] ?? [];
$requestor_data = $_SESSION['demandeur'] ?? [];

foreach ($data_certificate as $type => $certificate) {
    if (!is_array($certificate) || array_keys($certificate) === range(0, count($certificate) - 1)) {
        continue;
    }
    $data_certificate[$type] = [$certificate];
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['confirmer'])) {
        try {
            if (!empty($data_certificate)) {
                $certificat_ids = [];
                $NewCertificate = false;

                foreach ($data_certificate as $type => $certificates) {
                    if (!is_array($certificates)) $certificates = [$certificates];

                    foreach ($certificates as $certificate) {
                        $certificate_id = null;

                        foreach ($certificate as $key => $value) {
                            if (is_string($value) && file_exists(__DIR__ . '/../uploads/tmp/' . $value)) {
                                $certificate[$key] = moveFromTmpToFinalStructured($value, $type, $key);
                            }
                        }

                        switch ($type) {
                            case 'naissance':
                                $certificate_id = $birthController->get_existing_birth_id($certificate);
                                if (!$certificate_id) {
                                    $certificate_id = $birthController->create_birth_certificate($certificate);
                                    $NewCertificate = true;
                                }
                                break;

                            case 'mariage':
                                $certificate_id = $marriageController->get_existing_marriage_id($certificate);
                                if (!$certificate_id) {
                                    $certificate_id = $marriageController->create_marriage_certificate($certificate);
                                    $NewCertificate = true;
                                }

                                $id_naissance_epoux = $birthController->get_existing_birth_id([
                                    'nom' => $certificate['nom_epoux'],
                                    'prenom' => $certificate['prenom_epoux'],
                                    'date_naissance' => $certificate['date_naissance_epoux'],
                                    'lieu_naissance' => $certificate['lieu_naissance_epoux'],
                                    'genre' => 'masculin'
                                ]);

                                $id_naissance_epouse = $birthController->get_existing_birth_id([
                                    'nom' => $certificate['nom_epouse'],
                                    'prenom' => $certificate['prenom_epouse'],
                                    'date_naissance' => $certificate['date_naissance_epouse'],
                                    'lieu_naissance' => $certificate['lieu_naissance_epouse'],
                                    'genre' => 'feminin'
                                ]);

                                if ($id_naissance_epoux || $id_naissance_epouse) {
                                    $certificate['id_naissance_epoux'] = $id_naissance_epoux;
                                    $certificate['id_naissance_epouse'] = $id_naissance_epouse;
                                    $birthController->addMarriageInbirthcertificate($certificate);
                                }
                                break;

                            case 'deces':
                                $certificate_id = $deathController->get_existing_death_id($certificate);
                                if (!$certificate_id) {
                                    $certificate_id = $deathController->create_death_certificate($certificate);
                                    $NewCertificate = true;
                                }

                                $birth_id = $birthController->get_existing_birth_id([
                                    'nom' => $certificate['nom_defunt'],
                                    'prenom' => $certificate['prenom_defunt'],
                                    'date_naissance' => $certificate['date_naissance'],
                                    'lieu_naissance' => $certificate['lieu_naissance'],
                                    'genre' => $certificate['genre'] ?? null
                                ]);

                                if ($birth_id) {
                                    $birthController->addDeathInbirthcertificate($certificate, $birth_id);
                                }
                                break;
                        }

                        $certificat_ids[] = ['type' => $type, 'id' => $certificate_id];
                    }
                }

                if ($NewCertificate) {
                    $code_demand = $demandController->create_demand($_SESSION['localiter'] ?? null);
                    $requestor_mail = $requestroController->create_requestor($code_demand, $requestor_data);

                    foreach ($certificat_ids as $certif) {
                        $certificate_demandController->certificate_demand($code_demand, $certif['type'], $certif['id']);
                    }

                    if (!empty($requestor_mail)) {
                        notifierDemandeur($requestor_mail, $code_demand, 'cree');
                    }

                    $_SESSION['code_demande'] = $code_demand;
                    unset($_SESSION['demandeur'], $_SESSION['localiter'], $_SESSION['donnees_actes'], $_SESSION['code_paiement']);
                    header('Location: code_suivie.php');
                    exit;
                } else {
                    $message = "Aucun nouvel acte à enregistrer. Demande non créée.";
                    error_log("Aucune création car tous les actes existent déjà.");
                }
            } else {
                header('Location: dahsboard.php');
                exit;
            }
        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
            error_log("Erreur traitement: " . $e->getMessage());
        }
    }

    if (isset($_POST['modifier_type'])) {
        $type = $_POST['modifier_type'];
        header("Location: demande_etape1.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vérification des actes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style> 
        .btn-success{
            background: #ff8008 !important;
            border: #ff8008 !important;
        }
    </style>
</head>
<body>

<?php require_once './partials/header.php'; ?>

<div class="container my-5">
    <?php if (!empty($data_certificate)): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4 text-center">🧾 Vérification des informations</h2>

                <?php if (!empty($_SESSION['localiter'])): ?>
                    <div class="mb-4 text-center">
                        <h5>📍 Localité</h5>
                        <p class="fw-bold"><?= htmlspecialchars($_SESSION['localiter']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($requestor_data)): ?>
                    <div class="mb-4">
                        <h5>🙋‍♂️ Informations sur le demandeur</h5>
                        <ul class="list-group">
                            <?php foreach ($requestor_data as $cle => $val): ?>
                                <li class="list-group-item d-flex align-items-center">
                                    <strong class="me-2"><?= htmlspecialchars($cle) ?>:</strong>
                                    <?php if (is_string($val) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $val)): ?>
                                        <img src="<?= htmlspecialchars($val) ?>" alt="Image" style="max-height: 100px; border-radius: 5px; border: 1px solid #ccc;">
                                    <?php else: ?>
                                        <span><?= htmlspecialchars($val ?? '') ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php foreach ($data_certificate as $type => $certificates): ?>
                    <div class="mb-4">
                        <h5>📄 <?= ucfirst($type) ?></h5>
                        <div class="accordion" id="accordion<?= htmlspecialchars($type) ?>">
                            <?php foreach ($certificates as $i => $certificate): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= htmlspecialchars($type . $i) ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= htmlspecialchars($type . $i) ?>" aria-expanded="false" aria-controls="collapse<?= htmlspecialchars($type . $i) ?>">
                                            <?= ucfirst($type) ?> #<?= $i + 1 ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= htmlspecialchars($type . $i) ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= htmlspecialchars($type . $i) ?>">
                                        <div class="accordion-body">
                                            <ul class="list-unstyled mb-0">
                                                <?php foreach ($certificate as $cle => $val): ?>
                                                    <li class="mb-2">
                                                        <strong><?= htmlspecialchars($cle) ?>:</strong>
                                                        <?php if (is_string($val) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $val)): ?>
                                                            <div class="mt-2">
                                                                <img src="<?= htmlspecialchars($val) ?>" alt="Image" class="img-fluid rounded border" style="max-height: 300px;">
                                                            </div>
                                                        <?php else: ?>
                                                            <span><?= htmlspecialchars($val ?? '') ?></span>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex gap-3 justify-content-start mt-4">
                    <form method="post" class="m-0">
                        <button type="submit" name="confirmer" class="btn btn-success">✅ Confirmer</button>
                    </form>
                    <form method="post" class="m-0">
                        <input type="hidden" name="modifier_type" value="<?= htmlspecialchars($type) ?>">
                        <button type="submit" class="btn btn-warning">✏️ Modifier cet acte</button>
                    </form>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-info mt-3" role="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once './partials/footer.php'; ?>

</body>
</html>
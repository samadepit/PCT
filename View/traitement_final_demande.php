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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
    try {
        if (!empty($data_certificate)) {
            $certificat_ids = [];
            
            foreach ($data_certificate as $type => $certificates) {
                if (!is_array($certificates)) $certificates = [$certificates];

                foreach ($certificates as $certificate) {
                    $certificate_id = null;

                    foreach ($certificate as $key => $value) {
                        if (is_string($value) && file_exists(__DIR__ . '/../uploads/tmp/' . $value)) {
                            $certificate[$key] = moveFromTmpToFinalStructured($value, $type, $key);
                        }
                    }
                    $NewCertificate = false;
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
                    notifierDemandeur($requestor_mail, $code_demand, 'cree');
                    $_SESSION['code_demande'] = $code_demand;
                    unset($_SESSION['demandeur'], $_SESSION['localiter'], $_SESSION['donnees_actes'], $_SESSION['code_paiement']);
                    header('Location: code_suivie.php');
                    exit;
                } else {
                    $message = "Aucun nouvel acte à enregistrer. Demande non créée.";
                    error_log("Aucune création car tous les actes existent déjà.");
                }
                
        }
        else {
            // $code_demand = $_SESSION['code_demande'] ?? null;
            // if (!$code_demand) throw new Exception("Code de demande manquant pour duplicata.");
            // $paymentcontroller->createPayment($code_demande_duplicate, $numero, $code_paiement_generate,$is_duplicate=1);
            // unset($_SESSION['code_paiement']);
            header('Location: dahsboard.php');
            exit;
        }

    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        error_log("Erreur traitement: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_type'])) {
    $type = $_POST['modifier_type'];
    $redirectPage = 'demande_etape1.php';
    if ($redirectPage) {
        header("Location: $redirectPage");
        exit;
    }
}
?>

<?php if (!empty($data_certificate)): ?>
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI" />
        <h1>Bienvenue sur le Portail des Demande d'actes d'état civil</h1>
        <nav>
            <a href="dashboard.php" class="nav-btn">Accueil</a>
            <a href="demande_etape1.php" class="nav-btn"><span>Faire une demande</span></a>
            <a href="consulter_demande.php" class="nav-btn">Suivre une demande</a>
        </nav>
    </div>
    <div class="wrapper">
        <div class="card">
            <h2>🧾 Vérification des informations</h2>

            <?php if (!empty($_SESSION['localiter'])): ?>
                <div class="section">
                    <h3>📍 Localité</h3>
                    <p style="text-align:center; font-weight:bold;">
                        <?= htmlspecialchars($_SESSION['localiter']) ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($requestor_data)): ?>
                <div class="section">
                    <h3>🙋‍♂️ Informations sur le demandeur</h3>
                    <ul>
                        <?php foreach ($requestor_data as $cle => $val): ?>
                            <li><strong><?= htmlspecialchars($cle) ?>:</strong>
                                <?php if (is_string($val) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $val)): ?>
                                <div style="margin-top: 8px; text-align:center;">
                                    <img src="<?= htmlspecialchars($val) ?>" alt="Image" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid #ccc;">
                                </div>
                                <?php else: ?>
                                    <?= htmlspecialchars($val ?? '') ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php foreach ($data_certificate as $type => $certificates): ?>
                <div class="section">
                    <h3>📄 <?= ucfirst($type) ?></h3>
                    
                    <div class="scroll-container">
                        <?php foreach ($certificates as $i => $certificate): ?>
                            <details class="acte-details">
                                <summary><?= ucfirst($type) ?> #<?= (int)$i + 1 ?> - Afficher / Masquer</summary>
                                <fieldset>
                                    <ul>
                                        <?php foreach ($certificate as $cle => $val): ?>
                                            <li><strong><?= htmlspecialchars($cle) ?>:</strong> 
                                            
                                            <?php if (is_string($val) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $val)): ?>
                                            <div style="margin-top: 8px; text-align:center;">
                                                <img src="<?= htmlspecialchars($val) ?>" alt="Image" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid #ccc;">
                                            </div>
                                            <?php else: ?>
                                                <?= htmlspecialchars($val ?? '') ?>
                                            <?php endif; ?>

                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </fieldset>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <form method="post">
                <button type="submit" name="confirmer">✅ Confirmer et payer</button>
            </form>
            <form method="post" style="margin-top: 15px;">
                <input type="hidden" name="modifier_type" value="<?= htmlspecialchars($type) ?>">
                <button type="submit" class="modifier-btn" id="modifier_btn">✏️ Modifier cet acte</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<style>
        html, body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: linear-gradient(to right, #ff8008, #ffc837);
    }

    body {
        font-family: Arial, sans-serif;
        /* background: linear-gradient(to right, #ff8008, #ffc837); */
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

    .wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        padding: 40px 20px;
    }

    .card {
    background: #fff;
    width: 100%;
    max-width: 1000px; /* élargi à 1000px au lieu de 700px */
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}


    h2 {
        color: #e67e22;
        text-align: center;
        margin-bottom: 30px;
    }

    h3 {
        color: #d35400;
        margin-top: 25px;
        text-align: center;
    }

    .section {
        margin-bottom: 30px;
    }

    fieldset {
        border: 2px solid #f39c12;
        border-radius: 8px;
        padding: 20px;
        margin-top: 15px;
        background: #fffaf2;
        text-align: center;
    }

    legend {
        font-weight: bold;
        color: #e67e22;
        padding: 0 10px;
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0 auto;
        max-width: 400px;
        text-align: left;
    }

    li {
        background: #fff5e6;
        margin-bottom: 10px;
        padding: 10px 14px;
        border-left: 4px solid #e67e22;
        border-radius: 6px;
        font-size: 15px;
    }

    button[type="submit"],
    .modifier-btn {
        display: block;
        width: 100%;
        background-color: #e67e22;
        color: white;
        border: none;
        padding: 14px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
    }

    .modifier-btn {
    background-color: #3498db; /* Bleu */
    margin-top: 10px;
}

.modifier-btn:hover {
    background-color: #2c80b4;
}

#modifier_btn{
    background-color: #2c80b4;
}

.acte-details summary {
    font-weight: bold;
    font-size: 16px;
    background: #ecf0f1;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 10px;
    outline: none;
}

.acte-details[open] summary {
    background-color: #dfe6e9;
}


    button[type="submit"]:hover {
        background-color: #cf711f;
    }

    .scroll-container {
    max-height: 400px; /* tu peux ajuster selon la taille que tu veux */
    overflow-y: auto;
    padding-right: 10px;
    margin-top: 10px;
    border: 1px solid #f1c40f;
    border-radius: 10px;
    background-color: #fffdf7;
}

/* Ajout scrollbar stylée (optionnel) */
.scroll-container::-webkit-scrollbar {
    width: 8px;
}

.scroll-container::-webkit-scrollbar-thumb {
    background-color: #e67e22;
    border-radius: 4px;
}

.scroll-container::-webkit-scrollbar-track {
    background-color: #f9f9f9;
}

</style>

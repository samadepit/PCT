<?php
session_start();
require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/marriageController.php';
require_once __DIR__ . '/../Controller/deathController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/requestroController.php';

$birthController = new NaissanceController();
$marriageController = new MarriageController();
$deathController = new DecesController();
$demandController = new DemandeController();
$certificate_demandController = new ActeDemandeController();
$requestroController=new DemandeurController();

$data_certificate = $_SESSION['donnees_actes'] ?? [];
$requestor_data=$_SESSION['demandeur'] ?? [];
// var_dump($requestro_data);

foreach ($data_certificate as $type => $certificate) {
    if (!is_array($certificate) || array_keys($certificate) === range(0, count($certificate) - 1)) {
        continue;
    }

    $data_certificate[$type] = [$certificate];
}
// var_dump($data_certificate);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        foreach ($data_certificate as $type => $certificates) {
            // $birth_id='';
            foreach ($certificates as $certificate) {
                // var_dump($certificates);
                switch ($type) {
                    case 'naissance':
                        $birth_id = $birthController->get_existing_birth_id($certificate);
                        if (!$birth_id){
                            $certificate_id = $birthController->create_birth_certificate($certificate);
                            if (!$certificate_id) throw new Exception("Erreur dans l'acte de naissance.");
                            break;
                        }
                        
                    case 'mariage':
                        $certificate_id = $marriageController->create_marriage_certificate($certificate);
                        if (!$certificate_id) throw new Exception("Erreur dans l'acte de mariage.");
                        break;

                    case 'deces':
                        $birth_id = $birthController->get_existing_birth_id($certificate);
                        if (!$birth_id) throw new Exception("ID naissance introuvable pour décès.");
                        $certificate_id = $deathController->create_death_certificate($certificate, $birth_id);
                        if (!$certificate_id) throw new Exception("Erreur dans l'acte de décès.");
                        break;
                }
                $certificat_ids[] = ['type' => $type, 'id' => $certificate_id];
                // try {
                //     if ($certificate_id) {
                //         $certificate_demandController->certificate_demand($code_demand, $type, $certificate_id);
                //     }
                // } catch (Exception $e) {
                //     $erreurs[] = $e->getMessage();
                // }
            }
        }
        $code_demand = $demandController->create_demand($_SESSION['localiter']);
        $demandeur=$requestroController->create_requestor($code_demand,$requestor_data);
        
        foreach ($certificat_ids as $certif) {
            $certificate_demandController->certificate_demand($code_demand, $certif['type'], $certif['id']);
        }
        $message = "Toutes les demandes ont été enregistrées avec succès";
        $_SESSION['code_demande'] = $code_demand;
        $_SESSION['donnees_actes'] = $data_certificate;

        unset($_SESSION['demandeur'], $_SESSION['localiter']);

        header('Location:code_suivie.php');
        exit;
        
    } catch (Exception $e) {
        $erreurs[] = $e->getMessage();
        error_log("Erreur traitement: " . $e->getMessage());
    }

}
?>

<?php if (!empty($data_certificate)): ?>
    <h2>Vérifiez les informations avant soumission :</h2>
    <?php if (!empty($_SESSION['localiter'])): ?>
    <h2>La localite à laquelle vous faites la demande :</h2>
    <ul>
        <li><strong>Localité :</strong> <?= htmlspecialchars($_SESSION['localiter']) ?></li>
    </ul>
    <?php endif; ?>
    <?php if (!empty($requestro_data)): ?>
    <h2>Informations sur le demandeur</h2>
    <fieldset>
        <ul>
            <?php foreach ($requestro_data as $cle => $val): ?>
                <li><strong><?= htmlspecialchars($cle) ?>:</strong> <?= htmlspecialchars($val) ?></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <?php endif; ?>
    <?php foreach ($data_certificate as $type => $certificates): ?>
        <h3><?= ucfirst($type) ?></h3>
        <?php foreach ($certificates as $i => $certificate): ?>
            <fieldset>
                <legend><?= ucfirst($type) ?> #<?= (int)$i + 1 ?></legend>
                <ul>
                    <?php foreach ($certificate as $cle => $val): ?>
                        <li><strong><?= htmlspecialchars($cle) ?>:</strong> <?= htmlspecialchars($val) ?></li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <form method="post">
        <button type="submit">✅ Confirmer et envoyer toutes les demandes</button>
    </form>
 
<?php endif; ?>


<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
    }
    
    form {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    h3 {
        color: #2c3e50;
        margin-top: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }
    
    label {
        display: block;
        margin-bottom: 15px;
        font-weight: 500;
    }
    
    input[type="text"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }
    
    input[type="text"]:focus,
    input[type="date"]:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }
    
    button[type="submit"] {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
        transition: background-color 0.3s;
    }
    
    button[type="submit"]:hover {
        background-color: #2980b9;
    }
    
    @media (min-width: 600px) {
        label {
            display: grid;
            grid-template-columns: 200px 1fr;
            align-items: center;
            gap: 15px;
        }
        
        input[type="text"],
        input[type="date"] {
            margin-top: 0;
        }
    }
</style>
<?php
session_start();
require_once __DIR__ . '/../Controller/naissanceController.php';
require_once __DIR__ . '/../Controller/mariageController.php';
require_once __DIR__ . '/../Controller/decesController.php';
require_once __DIR__ . '/../Controller/demandeController.php';
require_once __DIR__ . '/../Controller/actedemandeController.php';
require_once __DIR__ . '/../Controller/demandeurController.php';

$naissanceController = new NaissanceController();
$mariageController = new MariageController();
$decesController = new DecesController();
$demandeController = new DemandeController();
$traitementController = new ActeDemandeController();
$demandeurController=new DemandeurController();

$donnees_actes = $_SESSION['donnees_actes'] ?? [];
$donnees_demandeur=$_SESSION['demandeur'] ?? [];
// var_dump($donnees_demandeur);

foreach ($donnees_actes as $type => $acte) {
    if (!is_array($acte) || array_keys($acte) === range(0, count($acte) - 1)) {
        continue;
    }

    $donnees_actes[$type] = [$acte];
}
// var_dump($donnees);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $code_demande = $demandeController->creer_demande($_SESSION['localiter']);
        $demandeur=$demandeurController->creerDemandeur($code_demande,$donnees_demandeur);
        // $id_acte = $naissanceController->creerActeNaissance($acte,$code_demande);
        // $traitementController->acte_demande($code_demande, $type, $id_acte);


        // try {
        //     $id_acte = $naissanceController->creerActeNaissance($acte);
        // } catch (Exception $e) {
        //     $erreurs[] = $e->getMessage();
        // }
        foreach ($donnees_actes as $type => $actes) {
            $id_naissance='';
            foreach ($actes as $acte) {
                // var_dump($actes);
                switch ($type) {
                    case 'naissance':
                        $id_acte= $naissanceController->creerActeNaissance($acte);
                        $id_naissance= $id_acte;
                        break;
                    case 'mariage':
                        $id_acte = $mariageController->creerActeMariage($acte);
                        break;
                    case 'deces':
                        $id_acte = $decesController->creerActeDeces($acte,$id_naissance);
                        break;
                }
                try {
                    if ($id_acte) {
                        $traitementController->acte_demande($code_demande, $type, $id_acte);
                    }
                } catch (Exception $e) {
                    $erreurs[] = $e->getMessage();
                }
            }
        }
        $message = "Toutes les demandes ont été enregistrées avec succès.";
        $_SESSION['code_demande'] = $code_demande;
        $_SESSION['donnees_actes'] = $donnees_actes;

        unset($_SESSION['demandeur'], $_SESSION['localiter']);

        header('Location: code_suivie.php');
        exit;
        // unset($_SESSION['donnees_actes']);
        
    } catch (Exception $e) {
        $erreurs[] = $e->getMessage();
        error_log("Erreur traitement: " . $e->getMessage());
    }

}
?>

<?php if (!empty($donnees_actes)): ?>
    <h2>Vérifiez les informations avant soumission :</h2>
    <?php if (!empty($_SESSION['localiter'])): ?>
    <h2>La localite à laquelle vous faites la demande :</h2>
    <ul>
        <li><strong>Localité :</strong> <?= htmlspecialchars($_SESSION['localiter']) ?></li>
    </ul>
    <?php endif; ?>
    <?php if (!empty($donnees_demandeur)): ?>
    <h2>Informations sur le demandeur</h2>
    <fieldset>
        <ul>
            <?php foreach ($donnees_demandeur as $cle => $val): ?>
                <li><strong><?= htmlspecialchars($cle) ?>:</strong> <?= htmlspecialchars($val) ?></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <?php endif; ?>
    <?php foreach ($donnees_actes as $type => $actes): ?>
        <h3><?= ucfirst($type) ?></h3>
        <?php foreach ($actes as $i => $acte): ?>
            <fieldset>
                <legend><?= ucfirst($type) ?> #<?= (int)$i + 1 ?></legend>
                <ul>
                    <?php foreach ($acte as $cle => $val): ?>
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
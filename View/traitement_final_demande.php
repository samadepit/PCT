 <?php
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

        header('Location:paiement.php');
        exit;

    } catch (Exception $e) {
        $erreurs[] = $e->getMessage();
        error_log("Erreur traitement: " . $e->getMessage());
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/etape.css">
</head>
<body>
     <div class="container stepper-container mt-4">
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
             <li><strong><?= htmlspecialchars($cle ?? '');?>:</strong> <?= htmlspecialchars($val ?? ''); ?></li>
             <?php endforeach; ?>
         </ul>
     </fieldset>
     <?php endforeach; ?>
     <?php endforeach; ?>
     <form method="post">
       <a href="index.php?controller=demande&action=paiement" class="btn btn-success ms-2">Continuer</a>  
     </form>

     <?php endif; ?>

 </div>
</body>
</html>





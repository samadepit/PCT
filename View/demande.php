<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styledemande.css">
</head>

<body>
      <?php
       require_once './partials/header.php'
     ?>

    <div class="section-demande">
    <h1>Section Demande</h1>
    <div class="button-container">
        <a href="./demande_etape1.php" class="button">Faire une demande</a>
        <a href="./consulter_demande.php" class="button">Suivie une demande</a>
    </div>
</div>
    <?php
       require_once './partials/footer.php'
     ?>
</body>

</html>
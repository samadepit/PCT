<?php
session_start();

if (!isset($_SESSION['actes']) || !isset($_POST['type_acte']) || !isset($_POST['code_demande']) || !isset($_POST['index'])) {
    header('Location: consulter_demande.php');
    exit;
}

$actes = $_SESSION['actes'];
$type = $_POST['type_acte'];
$index = (int) $_POST['index'];
$code_demande = $_POST['code_demande'];

if (!isset($actes[$index])) {
    echo "Erreur : acte introuvable.";
    exit;
}

$acte = $actes[$index];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Extrait de Naissance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .document {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            position: relative;
            page-break-inside: avoid;
        }
        
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .divider {
            border-top: 1px solid #000;
            margin: 10px 0;
        }
        
        .section {
            margin-bottom: 15px;
        }
        
        .section-center {
            text-align: center;
            margin: 15px 0;
        }
        
        .section-title {
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
        }
        
        .info-block {
            margin: 20px 0;
        }
        
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        
        .stamp-placeholder {
            float: right;
            width: 100px;
            height: 100px;
            border: 1px dashed #000;
            margin-left: 20px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .mentions {
            margin-top: 30px;
        }
        
        .neant {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #000;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .print-button {
            margin: 40px auto;
            display: block;
            padding: 10px 25px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .print-button:hover {
            background: #2563eb;
        }

        @media print {
        .print-button {
            display: none !important;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .document {
            box-shadow: none;
            border: none;
            padding: 0;
            margin: 0;
            page-break-inside: avoid;
        }
    }
    img{
        width: 150px;
    }
    </style>
</head>
<body>

<?php if ($type === 'naissance'): ?>
<div class="document" id="print-zone">
    <div class="header">REPUBLIQUE DE CÔTE D'IVOIRE</div>
    
    <div class="divider"></div>
    
    <div class="section-title">ETAT-CIVIL</div>
    <div class="section-center">CIRCONSCRIPTION D'ETAT CIVIL<br>de <?= htmlspecialchars($acte['localiter']) ?></div>
    
    <div class="divider"></div>
    
    <div class="section-center">N° <strong><?= htmlspecialchars($acte['code_demande']) ?></strong> du <?= date('d/m/Y', strtotime($acte['naissance_date_creation'])) ?> du Registre</div>
    
    <div class="section-title">EXTRAIT DE NAISSANCE</div>
    
    <div class="info-block">
        Le <?= date('d F Y', strtotime($acte['date_naissance'])) ?><br>
        à <?= htmlspecialchars($acte['heure_naissance']) ?><br><br>
        est né(e) <strong><?= htmlspecialchars($acte['nom_beneficiaire']) ?> <?= htmlspecialchars($acte['prenom_beneficiaire']) ?></strong><br><br>
        à <strong><?= htmlspecialchars($acte['lieu_naissance']) ?></strong><br><br>
        Fils de : <strong><?= htmlspecialchars($acte['nom_pere']) ?> <?= htmlspecialchars($acte['prenom_pere']) ?></strong><br>
        Profession : <strong><?= htmlspecialchars($acte['profession_pere']) ?></strong><br><br>
        Et de : <strong><?= htmlspecialchars($acte['nom_mere']) ?> <?= htmlspecialchars($acte['prenom_mere']) ?></strong><br>
        Profession : <strong><?= htmlspecialchars($acte['profession_mere']) ?></strong><br><br>
        
    </div>
    
    <div class="mentions">
        <div class="section-title">MENTIONS (éventuellement) :</div>
        <div>Marié(e): le <span class="neant">NEANT</span> À <span class="neant">NEANT</span></div>
        <div>Avec : <span class="neant">NEANT</span></div>
        <div>Mariage dissous par décision de divorce en date du <span class="neant">NEANT</span></div>
        <div>Décédé(e): le <span class="neant">NEANT</span> À <span class="neant">NEANT</span></div>
    </div>
    
    <div style="clear: both;"></div>
    
    <div>Certifié le présent extrait conforme aux indications portées au registre.</div>
    
    <div class="footer">
        Délivré à <?= htmlspecialchars($acte['localiter']) ?>, le <?= date('d/m/Y') ?><br>
        L' Officier de l'Etat Civil<br><br>
        <strong><?= htmlspecialchars($acte['officier_nom']) ?>  <?= htmlspecialchars($acte['officier_prenom']) ?></strong> <br>
        <img src=<?= htmlspecialchars($acte['signature']) ?> alt="signature">    
    </div>
</div>

<?php elseif ($type === 'mariage'): ?>
<div class="document" id="print-zone">
    <div class="header">REPUBLIQUE DE CÔTE D'IVOIRE</div>
    
    <div class="divider"></div>
    
    <div class="section-title">ETAT-CIVIL</div>
    <div class="section-center">CIRCONSCRIPTION D'ETAT CIVIL<br>de <?= htmlspecialchars($acte['localiter']) ?></div>
    
    <div class="divider"></div>
    
    <div class="section-center">N° <strong><?= htmlspecialchars($acte['code_demande']) ?></strong> du <?= date('d/m/Y', strtotime($acte['mariage_date_creation'])) ?> du Registre</div>
    
    <div class="section-title">EXTRAIT D'ACTE DE MARIAGE</div>
    
    <div class="info-block">
        Le mariage a été célébré le <strong><?= date('d F Y', strtotime($acte['mariage_date_creation'])) ?></strong><br>
        à <strong><?= htmlspecialchars($acte['lieu_mariage']) ?></strong><br><br>

        Entre :<br>
        <strong><?= htmlspecialchars($acte['nom_epoux']) ?> <?= htmlspecialchars($acte['prenom_epoux']) ?></strong>, né le <?= date('d/m/Y', strtotime($acte['date_naissance_epoux'])) ?> à <?= htmlspecialchars($acte['lieu_naissance_epoux']) ?><br>
        Profession : <strong><?= htmlspecialchars($acte['profession_epoux']) ?></strong><br><br>

        Et :<br>
        <strong><?= htmlspecialchars($acte['nom_epouse']) ?> <?= htmlspecialchars($acte['prenom_epouse']) ?></strong>, née le <?= date('d/m/Y', strtotime($acte['date_naissance_epouse'])) ?> à <?= htmlspecialchars($acte['lieu_naissance_epouse']) ?><br>
        Nombre d'enfant en commun : <strong><?= htmlspecialchars($acte['nombre_enfant']) ?></strong>
    </div>
    
    <div class="mentions">
        <div class="section-title">MENTIONS (éventuellement) :</div>
        <div>Mariage dissous par décision de divorce en date du <span class="neant">NEANT</span></div>
    </div>

    <div>Certifié le présent extrait conforme aux indications portées au registre.</div>
    <div class="footer">
        Délivré à <?= htmlspecialchars($acte['localiter']) ?>, le <?= date('d/m/Y') ?><br>
        L' Officier de l'Etat Civil<br><br>
        <strong><?= htmlspecialchars($acte['officier_nom']) ?> <?= htmlspecialchars($acte['officier_prenom']) ?></strong><br>
        <img src=<?= htmlspecialchars($acte['signature']) ?> alt="signature">
    </div>
</div>

<?php elseif ($type === 'deces'): ?>
<div class="document" id="print-zone">
    <div class="header">REPUBLIQUE DE CÔTE D'IVOIRE</div>
    
    <div class="divider"></div>
    
    <div class="section-title">ETAT-CIVIL</div>
    <div class="section-center">CIRCONSCRIPTION D'ETAT CIVIL<br>de <?= htmlspecialchars($acte['localiter']) ?></div>
    
    <div class="divider"></div>
    
    <div class="section-center">N° <strong><?= htmlspecialchars($acte['code_demande']) ?></strong> du <?= date('d/m/Y', strtotime($acte['deces_date_creation'])) ?> du Registre</div>
    
    <div class="section-title">EXTRAIT D'ACTE DE DECES</div>
    
    <div class="info-block">
        Est décédé(e) le <strong><?= date('d F Y', strtotime($acte['date_deces'])) ?></strong><br>
        à <strong><?= htmlspecialchars($acte['lieu_deces']) ?></strong><br><br>
        Nom : <strong><?= htmlspecialchars($acte['nom_defunt']) ?> <?= htmlspecialchars($acte['prenom_defunt']) ?></strong><br>
        Genre : <strong><?= htmlspecialchars($acte['genre']) ?></strong><br>
        Né(e) le <?= date('d/m/Y', strtotime($acte['defunt_date_naissance'])) ?> à <?= htmlspecialchars($acte['defunt_lieu_naissance']) ?><br>
        Profession : <strong><?= htmlspecialchars($acte['profession']) ?></strong><br>
        cause du décès <strong><?= htmlspecialchars($acte['cause']) ?></strong><br><br>
    </div>

    <div>Certifié le présent extrait conforme aux indications portées au registre.</div>
    
    <div class="footer">
        Délivré à <?= htmlspecialchars($acte['localiter']) ?>, le <?= date('d/m/Y') ?><br>
        L' Officier de l'Etat Civil<br><br>
        <strong><?= htmlspecialchars($acte['officier_nom']) ?> <?= htmlspecialchars($acte['officier_prenom']) ?></strong><br>
        <img src=<?= htmlspecialchars($acte['signature']) ?> alt="signature">
    </div>
</div>
<?php endif; ?>


<button class="print-button" onclick="window.print()">🖨️ Imprimer l’extrait</button>

</body>
</html>

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
    <title>Acte officiel - Impression</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 40px;
        }

        .acte-container {
            background: white;
            padding: 60px;
            width: 800px;
            margin: auto;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            border: 1px solid #ccc;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 16px;
        }

        .section {
            margin-bottom: 30px;
        }

        .label {
            display: inline-block;
            width: 250px;
            font-weight: bold;
            vertical-align: top;
        }

        .value {
            display: inline-block;
            width: 450px;
        }

        .field {
            margin-bottom: 12px;
            font-size: 18px;
        }

        .footer {
            margin-top: 50px;
            font-size: 14px;
            text-align: right;
        }

        .print-button {
            display: block;
            margin: 40px auto;
            padding: 12px 30px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #2563eb;
        }

        .signature-img {
            max-width: 200px;
            max-height: 100px;
        }
    </style>
</head>
<body>

<div class="acte-container" id="print-zone">
    <div class="header">
        <h1>République de Côte d’Ivoire</h1>
        <p>Union - Discipline - Travail</p>
    </div>

    <?php if ($type === 'naissance'): ?>
        <div class="section">
            <h1><strong>Acte de naissance</strong></h1>
            <div class="field"><span class="label">N° du registre :</span><span class="value"><?= htmlspecialchars($acte['numero_registre']) ?></span></div>
            <div class="field"><span class="label">Centre d’état civil de :</span><span class="value"><?= htmlspecialchars($acte['localiter']) ?></span></div>
            <div class="field"><span class="label">Nom :</span><span class="value"><?= htmlspecialchars($acte['nom_beneficiaire']) ?></span></div>
            <div class="field"><span class="label">Prénom :</span><span class="value"><?= htmlspecialchars($acte['prenom_beneficiaire']) ?></span></div>
            <div class="field"><span class="label">Lieu de naissance :</span><span class="value"><?= htmlspecialchars($acte['lieu_naissance']) ?></span></div>
            <div class="field"><span class="label">Date de naissance :</span><span class="value"><?= htmlspecialchars($acte['date_naissance']) ?></span></div>
            <div class="field"><span class="label">Heure :</span><span class="value"><?= htmlspecialchars($acte['heure_naissance']) ?></span></div>
            <div class="field"><span class="label">Genre:</span><span class="value"><?= htmlspecialchars($acte['naissance_genre']) ?></span></div>
            <div class="field"><span class="label">Nom et prénom du père :</span><span class="value"><?= htmlspecialchars($acte['nom_pere']) ?> <?= htmlspecialchars($acte['prenom_pere']) ?></span></div>
            <div class="field"><span class="label">Profession du père :</span><span class="value"><?= htmlspecialchars($acte['profession_pere']) ?></span></div>
            <div class="field"><span class="label">Nom et prénom de la mère :</span><span class="value"><?= htmlspecialchars($acte['nom_mere']) ?> <?= htmlspecialchars($acte['prenom_mere']) ?></span></div>
            <div class="field"><span class="label">Profession de la mère :</span><span class="value"><?= htmlspecialchars($acte['profession_mere']) ?></span></div>
            <div class="field"><span class="label">Date de déclaration :</span><span class="value"><?= htmlspecialchars($acte['naissance_date_creation']) ?></span></div>
            <div class="field"><span class="label">Officier de l’état civil :</span><span class="value"><?= htmlspecialchars($acte['officier']) ?></span></div>
            <div class="field"><span class="label">Cachet et signature :</span>
                <div class="value">
                    <img src=<?= htmlspecialchars($acte['signature']) ?> alt='Signature' class='signature-img'>
                </div>
            </div>
        </div>

    <?php elseif ($type === 'mariage'): ?>
        <div class="section">
            <h1><strong>Acte de mariage</strong></h1>
            <div class="field"><span class="label">N° du registre :</span><span class="value"><?= htmlspecialchars($acte['numero_registre']) ?></span></div>
            <div class="field"><span class="label">Centre d’état civil de :</span><span class="value"><?= htmlspecialchars($acte['localiter']) ?></span></div>

            <p><strong>Époux</strong></p>
            <div class="field"><span class="label">Nom et prénom :</span><span class="value"><?= htmlspecialchars($acte['nom_mari']) ?> <?= htmlspecialchars($acte['prenom_mari']) ?></span></div>
            <div class="field"><span class="label">Date de naissance :</span><span class="value"><?= htmlspecialchars($acte['date_naissance_mari']) ?></span></div>

            <p><strong>Épouse</strong></p>
            <div class="field"><span class="label">Nom et prénom :</span><span class="value"><?= htmlspecialchars($acte['nom_femme']) ?> <?= htmlspecialchars($acte['prenom_femme']) ?></span></div>
            <div class="field"><span class="label">Date de naissance :</span><span class="value"><?= htmlspecialchars($acte['date_naissance_femme']) ?></span></div>

            <div class="field"><span class="label">Date de célébration :</span><span class="value"><?= htmlspecialchars($acte['date_mariage']) ?></span></div>
            <div class="field"><span class="label">Lieu de célébration :</span><span class="value">Mairie de <?= htmlspecialchars($acte['lieu_mariage']) ?></span></div>
            <div class="field"><span class="label">Date de déclaration :</span><span class="value"><?= htmlspecialchars($acte['mariage_date_creation']) ?></span></div>
            <div class="field"><span class="label">Officier de l’état civil :</span><span class="value"><?= htmlspecialchars($acte['officier']) ?></span></div>
            <div class="field"><span class="label">Cachet et signature :</span>
                <div class="value">
                    <img src=<?= htmlspecialchars($acte['signature']) ?> alt='Signature' class='signature-img'>
                </div>
            </div>
        </div>

    <?php elseif ($type === 'deces'): ?>
        <div class="section">
            <h1><strong>Acte de décès</strong></h1>
            <div class="field"><span class="label">N° du registre :</span><span class="value"><?= htmlspecialchars($acte['numero_registre']) ?></span></div>
            <div class="field"><span class="label">Centre d’état civil de :</span><span class="value"><?= htmlspecialchars($acte['localiter']) ?></span></div>
            <div class="field"><span class="label">Nom et prénoms :</span><span class="value"><?= htmlspecialchars($acte['nom_defunt']) ?> <?= htmlspecialchars($acte['prenom_defunt']) ?></span></div>
            <div class="field"><span class="label">Date de naissance :</span><span class="value"><?= htmlspecialchars($acte['defunt_date_naissance']) ?></span></div>
            <div class="field"><span class="label">Date de décès :</span><span class="value"><?= htmlspecialchars($acte['date_deces']) ?></span></div>
            <div class="field"><span class="label">Lieu de décès :</span><span class="value"><?= htmlspecialchars($acte['lieu_deces']) ?></span></div>
            <div class="field"><span class="label">Profession :</span><span class="value"><?= htmlspecialchars($acte['profession']) ?></span></div>
            <div class="field"><span class="label">Cause du décès :</span><span class="value"><?= htmlspecialchars($acte['cause']) ?></span></div>
            <div class="field"><span class="label">Déclaré par :</span><span class="value"><?= htmlspecialchars($acte['declarant']) ?></span></div>
            <div class="field"><span class="label">Lien avec le défunt :</span><span class="value"><?= htmlspecialchars($acte['lien_declarant']) ?></span></div>
            <div class="field"><span class="label">Date de déclaration :</span><span class="value"><?= htmlspecialchars($acte['deces_date_creation']) ?></span></div>
            <div class="field"><span class="label">Officier de l’état civil :</span><span class="value"><?= htmlspecialchars($acte['officier']) ?></span></div>
            <div class="field"><span class="label">Cachet et signature :</span>
                <div class="value">
                    <img src=<?= htmlspecialchars($acte['signature']) ?> alt='Signature' class='signature-img'>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>Fait à Abidjan, le <?= date('d/m/Y') ?></p>
    </div>
</div>

<button class="print-button" type="button">🖨️ Imprimer l’acte</button>

<script>
    document.querySelector('.print-button').addEventListener('click', function () {
    const content = document.getElementById('print-zone').innerHTML;
    const printWindow = window.open('', '', 'width=800,height=700');
    printWindow.document.write(`
        <html>
        <head>
            <title>Impression</title>
            <base href="${location.origin}/">
            <style>
                body { font-family: "Times New Roman", serif; padding: 40px; }
                .signature-img { max-width: 200px; max-height: 100px; }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.onload = () => {
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };
});
</script>

</body>
</html>

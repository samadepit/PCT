<?php
session_start();

if (!isset($_SESSION['actes']) || !isset($_SESSION['type_acte'])) {
    header('Location: votre_page_recherche.php');
    exit;
}

$acte = $_SESSION['actes'];
$type = $_SESSION['type_acte'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Duplicata d'acte - Impression</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
            text-align: center;
        }

        .container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: auto;
            text-align: left;
        }

        h1 {
            color: #1f2937;
            font-size: 26px;
            margin-bottom: 30px;
            text-align: center;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h2 {
            color: #111827;
            font-size: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .section p {
            font-size: 16px;
            color: #374151;
            margin: 8px 0;
        }

        .label {
            font-weight: bold;
            color: #111827;
        }

        .print-button {
            display: block;
            margin: 40px auto 0;
            padding: 12px 30px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Duplicata d'acte</h1>

    <div class="section">
        <?php if ($type === 'naissance'): ?>
            <h2>Acte de naissance</h2>
            <p><span class="label">Nom :</span> <?= htmlspecialchars($acte['nom_beneficiaire']) ?></p>
            <p><span class="label">Prénom :</span> <?= htmlspecialchars($acte['prenom_beneficiaire']) ?></p>
            <p><span class="label">Lieu de naissance :</span> <?= htmlspecialchars($acte['lieu_naissance']) ?></p>
            <p><span class="label">Date de naissance :</span> <?= htmlspecialchars($acte['date_naissance']) ?></p>

        <?php elseif ($type === 'mariage'): ?>
            <h2>Acte de mariage</h2>
            <p><span class="label">Nom de l'époux :</span> <?= htmlspecialchars($acte['nom_mari']) ?></p>
            <p><span class="label">Prénom de l'époux :</span> <?= htmlspecialchars($acte['prenom_mari']) ?></p>
            <p><span class="label">Nom de l'épouse :</span> <?= htmlspecialchars($acte['nom_femme']) ?></p>
            <p><span class="label">Prénom de l'épouse :</span> <?= htmlspecialchars($acte['prenom_femme']) ?></p>
            <p><span class="label">Lieu de mariage :</span> <?= htmlspecialchars($acte['lieu_mariage']) ?></p>
            <p><span class="label">Date de mariage :</span> <?= htmlspecialchars($acte['date_mariage']) ?></p>

        <?php elseif ($type === 'deces'): ?>
            <h2>Acte de décès</h2>
            <p><span class="label">Nom du défunt :</span> <?= htmlspecialchars($acte['nom_beneficiaire']) ?></p>
            <p><span class="label">Prénom du défunt :</span> <?= htmlspecialchars($acte['prenom_beneficiaire']) ?></p>
            <p><span class="label">Lieu de décès :</span> <?= htmlspecialchars($acte['lieu_deces']) ?></p>
            <p><span class="label">Date de décès :</span> <?= htmlspecialchars($acte['date_deces']) ?></p>
        <?php endif; ?>
    </div>

    <button class="print-button" onclick="window.print()">🖨️ Imprimer</button>
</div>

</body>
</html>

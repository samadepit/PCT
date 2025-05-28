<?php
// liste_demandes.php
require_once __DIR__ . '/../Controller/certificatedemandController.php';
$actedemandeController = new ActeDemandeController();
$demandes = $actedemandeController->getAllvalidationCertificate();
// var_dump(count($demandes))
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes à signer</title>
    <style>
        body { font-family: Arial; padding: 20px; background-color: #f9fafb; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background-color: #f3f4f6; }
        a.btn { padding: 6px 12px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; }
        a.btn:hover { background: #2563eb; }
    </style>
</head>
<body>
    <h2>Demandes à signer</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Demandeur</th>
                <th>Type d'acte</th>
                <th>Personne concernée</th>
                <th>Lien</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                    <td><?= htmlspecialchars($demande['nom_demandeur']) ?> <?= htmlspecialchars($demande['prenom_demandeur']) ?></td>
                    <td><?= htmlspecialchars($demande['type_acte']) ?></td>
                    <td>
                        <?php if ($demande['type_acte'] === 'naissance'): ?>
                            <?= htmlspecialchars($demande['nom_beneficiaire']) ?> <?= htmlspecialchars($demande['prenom_beneficiaire']) ?>
                        <?php elseif ($demande['type_acte'] === 'mariage'): ?>
                            <?= htmlspecialchars($demande['nom_mari']) ?> <?= htmlspecialchars($demande['prenom_mari']) ?> & 
                            <?= htmlspecialchars($demande['nom_femme']) ?> <?= htmlspecialchars($demande['prenom_femme']) ?>
                        <?php elseif ($demande['type_acte'] === 'deces'): ?>
                            <?= htmlspecialchars($demande['nom_defunt']) ?> <?= htmlspecialchars($demande['prenom_defunt']) ?>
                        <?php else: ?>
                            -
                        <?php endif;?>
                    </td>
                    <td><?= htmlspecialchars($demande['relation_avec_beneficiaire']) ?></td>
                    <td><a href="certificate_signing.php?code_demande=<?= urlencode($demande['code_demande']) ?>" class="btn">Voir</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

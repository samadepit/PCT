<?php
// details_demande.php
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/demandController.php';
$actedemandeController = new ActeDemandeController();
$demandeController= new DemandeController();
if (!isset($_GET['code_demande'])) {
    echo "ID de la demande manquant.";
    exit;
}

$id_certificate = $_GET['code_demande'];
$demande = $actedemandeController->getCertificateById($id_certificate); // Méthode à créer dans le contrôleur

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $motif = $_POST['motif'] ?? null;

    if ($action === 'valider') {
        $demandeController->updateStatut($id_certificate, 'valider');
    } elseif ($action === 'rejeter') {
        $demandeController->updateStatut($id_certificate, 'en_attente', $motif);
    }

    header('Location: list_demand.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la demande</title>
    <style>
        body { font-family: Arial; padding: 30px; background-color: #f9fafb; }
        .container { background: white; padding: 25px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 20px; }
        p { margin: 8px 0; }
        label { font-weight: bold; }
        textarea { width: 100%; height: 100px; margin-top: 10px; margin-bottom: 20px; }
        .btns { display: flex; gap: 10px; }
        button { padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .valider { background-color: #10b981; color: white; }
        .rejeter { background-color: #ef4444; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Détails de la demande</h2>
        <p><label>Demandeur :</label> <?= htmlspecialchars($demande['nom_demandeur']) ?> <?= htmlspecialchars($demande['prenom_demandeur']) ?></p>
        <p><label>Lien :</label> <?= htmlspecialchars($demande['relation_avec_beneficiaire']) ?></p>
        <p><label>Type d'acte :</label> <?= htmlspecialchars($demande['type_acte']) ?></p>

        <?php if ($demande['type_acte'] === 'naissance'): ?>
            <p><label>Personne concernée :</label> <?= htmlspecialchars($demande['nom_beneficiaire']) ?> <?= htmlspecialchars($demande['prenom_beneficiaire']) ?>
             née le <?= htmlspecialchars($demande['date_naissance']) ?> à <?= htmlspecialchars($demande['lieu_naissance']) ?></p>
            <p><label>Nom et prénom du père :</label> <?= htmlspecialchars($demande['nom_pere']) ?> <?= htmlspecialchars($demande['prenom_pere']) ?>  <?= htmlspecialchars($demande['profession_pere']) ?></p>
            <p><label>Nom et prénom de la mère :</label> <?= htmlspecialchars($demande['nom_mere']) ?> <?= htmlspecialchars($demande['prenom_mere']) ?> <?= htmlspecialchars($demande['profession_mere']) ?></p>
        <?php elseif ($demande['type_acte'] === 'mariage'): ?>
            <p><label>Mari :</label> <?= htmlspecialchars($demande['nom_mari']) ?> <?= htmlspecialchars($demande['prenom_mari']) ?></p>
            <p><label>Femme :</label> <?= htmlspecialchars($demande['nom_femme']) ?> <?= htmlspecialchars($demande['prenom_femme']) ?></p>
        <?php elseif ($demande['type_acte'] === 'deces'): ?>
            <p><label>Défunt :</label> <?= htmlspecialchars($demande['nom_defunt']) ?> <?= htmlspecialchars($demande['prenom_defunt']) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="motif">Motif de rejet (facultatif, obligatoire si rejet):</label>
            <textarea name="motif" id="motif" placeholder="Expliquer pourquoi la demande est rejetée..."></textarea>
            <div class="btns">
                <button type="submit" name="action" value="valider" class="valider">Valider</button>
                <button type="submit" name="action" value="rejeter" class="rejeter">Rejeter</button>
            </div>
        </form>
    </div>
</body>
</html>

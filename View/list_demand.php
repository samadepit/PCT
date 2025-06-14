<?php
session_start();
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../service/date_convert.php';
$id = $_GET['id'] ?? null;
if (empty($id)) {
    $_SESSION['erreur'] = "Accès invalide. Vous avez été redirigé vers la page de connexion.";
    header("Location: login.php");
    exit;
}
$actedemandeController = new ActeDemandeController();

$demandes = $actedemandeController->getAllPending();

$stats = $actedemandeController->getStatistics();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes en attente</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .top-header {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .top-header img {
            height: 60px;
        }

        .top-header h1 {
            font-size: 22px;
            color: #1f2937;
            font-weight: bold;
            flex: 1;
            text-align: center;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #f97316;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #ea580c;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .kpi-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .kpi {
            flex: 1;
            min-width: 150px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .kpi:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(249, 115, 22, 0.3);
        }

        .kpi h3 {
            margin: 0;
            font-size: 20px;
            color: #6b7280;
        }

        .kpi p {
            font-size: 28px;
            font-weight: bold;
            color: #f97316;
            margin-top: 10px;
        }


        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            padding: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 600;
        }

        tr:hover {
            background-color: #fef3c7;
        }

        a.btn {
            padding: 8px 16px;
            background-color: #f97316;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        a.btn:hover {
            background-color: #ea580c;
        }

        @media screen and (max-width: 768px) {
        .top-header {
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }

        .top-header h1 {
            font-size: 18px;
        }

        .logout-btn {
            margin-top: 10px;
        }

        .kpi-container {
            flex-direction: column;
            gap: 12px;
        }

        .kpi h3 {
            font-size: 14px;
        }

        .kpi p {
            font-size: 18px;
        }

        table {
            font-size: 13px;
        }

        th, td {
            padding: 8px 10px;
        }
    }

    @media screen and (max-width: 480px) {
        .top-header h1 {
            font-size: 16px;
        }

        .kpi p {
            font-size: 16px;
        }

        .kpi h3 {
            font-size: 13px;
        }

        th, td {
            font-size: 12px;
            padding: 6px 8px;
        }

        a.btn {
            padding: 5px 10px;
            font-size: 12px;
        }
    }

    </style>
</head>
<body>

    <!-- Bandeau supérieur -->
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
        <h1>Portail des Agents de l'état civil</h1>
        <a href="index.php?page=logout" class="logout-btn">Déconnexion</a>
    </div>

    <div class="container">
        <!-- KPI -->
        <div class="kpi-container">
            <div class="kpi">
                <h3>Actes</h3>
                <p><?= $stats['total_certificate'] ?></p>
            </div>
            <div class="kpi">
                <h3>Naissances</h3>
                <p><?= $stats['birth'] ?></p>
            </div>
            <div class="kpi">
                <h3>Décès</h3>
                <p><?= $stats['death'] ?></p>
            </div>
            <div class="kpi">
                <h3>Mariages</h3>
                <p><?= $stats['marriage'] ?></p>
            </div>
            <div class="kpi">
                <h3>En attente</h3>
                <p><?= $stats['pending'] ?></p>
            </div>
            <div class="kpi">
                <h3>Validés</h3>
                <p><?= $stats['validated'] ?></p>
            </div>
            
            <div class="kpi">
                <h3>Rejetés</h3>
                <p><?= $stats['rejeted'] ?></p>
            </div>
        </div>

    <div class="container">
        <h2>Demandes en attente</h2>
        <div class="card">
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
                            <td><?=$dateConvertie = convertirDateEnFrancais(htmlspecialchars($demande['demande_date_creation'])) ?></td>
                            <td><?= htmlspecialchars($demande['nom_demandeur']) ?> <?= htmlspecialchars($demande['prenom_demandeur']) ?></td>
                            <td><?= htmlspecialchars($demande['type_acte']) ?></td>
                            <td>
                                <?php if ($demande['type_acte'] === 'naissance'): ?>
                                    <?= htmlspecialchars($demande['nom_beneficiaire']) ?> <?= htmlspecialchars($demande['prenom_beneficiaire']) ?>
                                <?php elseif ($demande['type_acte'] === 'mariage'): ?>
                                    <?= htmlspecialchars($demande['nom_epoux']) ?> <?= htmlspecialchars($demande['prenom_epoux']) ?> & 
                                    <?= htmlspecialchars($demande['nom_epouse']) ?> <?= htmlspecialchars($demande['prenom_epouse']) ?>
                                <?php elseif ($demande['type_acte'] === 'deces'): ?>
                                    <?= htmlspecialchars($demande['nom_defunt']) ?> <?= htmlspecialchars($demande['prenom_defunt']) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($demande['relation_avec_beneficiaire']) ?></td>
                            <td>
                                <a href="details_demand.php?code_demande=<?= urlencode($demande['code_demande']) ?>&id=<?= urlencode($id) ?>" class="btn">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

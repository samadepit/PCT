<?php
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/UserController.php';
$actedemandeController = new ActeDemandeController();
$userController= new UserController();
$demandes = $actedemandeController->getAllvalidationCertificate();
// $id = $_GET['id'] ?? null;
$stats = $actedemandeController->getStatistics();
$userstats=$userController->getStatisticsAdministration();
$usersadministration=$userController->getAllAdministration();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $userId = (int) $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'update_statut') {
        $userController->UpdateStatutAdministration($userId);
        
    } elseif ($action === 'update_role') {
        $userController->UpdateRoleAdministration($userId);
    }

    header('Location: administration_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Portail des officiers de l'état civil</title>
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
            margin-top: 15px;
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

        .btn-action {
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        }

        .btn-green {
            background-color: #10b981; /* Vert émeraude */
        }

        .btn-green:hover {
            background-color: #059669;
        }

        .btn-red {
            background-color: #ef4444; /* Rouge clair */
        }

        .btn-red:hover {
            background-color: #dc2626;
        }

        .btn-orange {
            background-color: #f97316;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .btn-orange:hover {
            background-color: #ea580c;
        }

        .btn-blue {
            background-color: #3b82f6;
        }

        .btn-blue:hover {
            background-color: #2563eb;
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

    <!-- Bandeau supérieur avec logo, titre et déconnexion -->
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
        <h1>Administration de l'état civil de Ouangolodougou</h1>
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
            <div class="kpi">
                <h3>Signés</h3>
                <p><?= $stats['signed'] ?></p>
            </div>
            <div class="kpi">
                <h3>Agent</h3>
                <p><?= $userstats['agent'] ?></p>
            </div>
            <div class="kpi">
                <h3>Officier</h3>
                <p><?= $userstats['officer'] ?></p>
            </div>
        </div>

        <!-- Tableau des demandes -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Telephone</th>
                        <th>Profession</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Email</th>
                        <th>date_creation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersadministration as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['prenom']) ?></td>
                            <td><?= htmlspecialchars($user['numero_telephone']) ?></td>
                            <td><?= htmlspecialchars($user['profession']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['statut']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['date_creation']) ?></td>
                            <td style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <form method="POST">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="action" value="update_statut">
                                    <button type="submit" class="btn-action btn-green">Changer statut</button>
                                </form>

                                <form method="POST">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="action" value="update_role">
                                    <button type="submit" class="btn-action btn-blue">Changer rôle</button>
                                </form>
                                <a href="certificate_signing.php?id=<?= urlencode($user['id']) ?>" class="btn-action btn-orange">Éditer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</body>
</html>

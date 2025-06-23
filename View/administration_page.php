<?php
require_once __DIR__ . '/../Controller/certificatedemandController.php';
require_once __DIR__ . '/../Controller/UserController.php';
require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/deathController.php';
require_once __DIR__ . '/../Controller/marriageController.php';
$actedemandeController = new ActeDemandeController();
$userController= new UserController();
$demandes = $actedemandeController->getAllvalidationCertificate();
$id = $_GET['id'] ?? null;
if (empty($id)) {
    $_SESSION['erreur'] = "Accès invalide. Vous avez été redirigé vers la page de connexion.";
    header("Location: login.php");
    exit;
}
$stats = $actedemandeController->getStatistics();
$userstats=$userController->getStatisticsAdministration();
$usersadministration=$userController->getAllAdministration();
$birthcontroller= new NaissanceController;
$marriagecontroller= new MarriageController;
$deathcontroller= new DecesController;
$birth=$birthcontroller->getBirth();
$death=$deathcontroller->getDeath();
$marriage=$marriagecontroller->getMarriage();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $userId = (int) $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'update_statut') {
        $userController->UpdateStatutAdministration($userId);
        
    } elseif ($action === 'update_role') {
        $userController->UpdateRoleAdministration($userId);
    }

    header('Location: administration_page.php?id=' . urlencode($id));
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

        .tab-bar {
        display: flex;
        border-bottom: 2px solid #ddd;
        margin-bottom: 20px;
        background-color: #fff;
        flex-wrap: wrap;
        }

        .tab-bar button {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 20px;
            color: #555;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .tab-bar button:hover {
            color:rgb(45, 163, 16);
        }

        .tab-bar button.active {
            color: #ea580c;
            border-color: #ea580c;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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
                <canvas id="chartType" class="chart-canvas"></canvas>
            </div>
            <div class="kpi">
                <canvas id="chartSignStatus" class="chart-canvas"></canvas>
            </div>
            <div class="kpi ">
                <canvas id="chartValidationStatus" class="chart-canvas" style="display: block; box-sizing: border-box; height: 180px; width: 180px;"></canvas>
            </div>
            <div class="kpi">
                <canvas id="chartEvolution" class="chart-canvas" 
                style="display: block; box-sizing: border-box; height: 180px; width: 180px;"></canvas>
            </div>
        </div>

        <!-- Tableau des demandes card etait ici claass la hun-->
        <!-- Onglets -->
        <div class="card">
            <div class="tab-container">
                <!-- Barre d'onglets -->
                <div class="tab-bar">
                    <button class="tab-btn" data-tab="admin">Administration</button>
                    <button class="tab-btn active" data-tab="naissance">Naissances</button>
                    <button class="tab-btn" data-tab="mariage">Mariages</button>
                    <button class="tab-btn" data-tab="deces">Décès</button>
                </div>

                <!-- Contenu des onglets -->
                <div id="tab-naissance" class="tab-content active">
                <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Date de naissance</th>
                                    <th>Lieu de naissance</th>
                                    <th>Genre</th>
                                    <th>analyser par</th>
                                    <th>Signer par</th>
                                    <th>Signer le</th>
                                    <th>Date création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($birth as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['nom_beneficiaire']) ?></td>
                                        <td><?= htmlspecialchars($user['prenom_beneficiaire']) ?></td>
                                        <td><?= htmlspecialchars($user['date_naissance']) ?></td>
                                        <td><?= htmlspecialchars($user['lieu_naissance']) ?></td>
                                        <td><?= htmlspecialchars($user['genre']) ?></td>
                                        <td><?= htmlspecialchars($user['agent_nom']) ?> <?= htmlspecialchars($user['agent_prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['officier_nom']) ?> <?= htmlspecialchars($user['officier_prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['date_signature']) ?></td>
                                        <td><?= htmlspecialchars($user['date_creation']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>

                <div id="tab-mariage" class="tab-content">
                <table>
                            <thead>
                                <tr>
                                    <th>Nom et prenom du conjoint</th>
                                    <th>Nom et prenom de la conjointe</th>
                                    <th>Date de mariage</th>
                                    <th>Lieu de mariage</th>
                                    <th>analyser par</th>
                                    <th>Signer par</th>
                                    <th>Signer le</th>
                                    <th>Date création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($marriage as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['nom_epoux']) ?> <?= htmlspecialchars($user['prenom_epoux']) ?></td>
                                        <td><?= htmlspecialchars($user['nom_epouse']) ?> <?= htmlspecialchars($user['prenom_epouse']) ?></td>
                                        <td><?= htmlspecialchars($user['date_mariage']) ?></td>
                                        <td><?= htmlspecialchars($user['lieu_mariage']) ?></td>
                                        <td><?= htmlspecialchars($user['agent_nom']) ?> <?= htmlspecialchars($user['agent_prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['officier_nom']) ?> <?= htmlspecialchars($user['officier_prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['date_signature']) ?></td>
                                        <td><?= htmlspecialchars($user['date_creation']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>

                <div id="tab-admin" class="tab-content">
                    <!-- ⬇️ Ici tu colles ton tableau HTML/PHP -->
                        <div style="text-align: right; margin-bottom: 15px;">
                            <a href="create_user.php?id=<?= urlencode($id) ?>" class="btn btn-green">➕ Ajouter un utilisateur</a>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Téléphone</th>
                                    <th>Profession</th>
                                    <th>Rôle</th>
                                    <th>Statut</th>
                                    <th>Email</th>
                                    <th>Date création</th>
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
                                            <a href="editer_user.php?id=<?= urlencode($user['id']) ?>" class="btn-action btn-orange">Éditer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>

                <div id="tab-deces" class="tab-content">
                <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Date de deces</th>
                                    <th>Lieu de deces</th>
                                    <th>cause du deces</th>
                                    <th>Genre</th>
                                    <th>analyser par</th>
                                    <th>Signer par</th>
                                    <th>Signer le</th>
                                    <th>Date création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($death as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['nom_defunt']) ?></td>
                                        <td><?= htmlspecialchars($user['prenom_defunt']) ?></td>
                                        <td><?= htmlspecialchars($user['date_deces']) ?></td>
                                        <td><?= htmlspecialchars($user['lieu_deces']) ?></td>
                                        <td><?= htmlspecialchars($user['cause']) ?></td>
                                        <td><?= htmlspecialchars($user['genre']) ?></td>
                                        <td><?= htmlspecialchars($user['agent_nom']) ?> <?= htmlspecialchars($user['agent_prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['officier_nom']) ?> <?= htmlspecialchars($user['officier_prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['date_signature']) ?></td>
                                        <td><?= htmlspecialchars($user['date_creation']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>

                <div id="tab-admin" class="tab-content">
                    <!-- ⬇️ Ici tu colles ton tableau HTML/PHP -->
                        <div style="text-align: right; margin-bottom: 15px;">
                            <a href="create_user.php?id=<?= urlencode($id) ?>" class="btn btn-green">➕ Ajouter un utilisateur</a>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Téléphone</th>
                                    <th>Profession</th>
                                    <th>Rôle</th>
                                    <th>Statut</th>
                                    <th>Email</th>
                                    <th>Date création</th>
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
                                            <a href="editer_user.php?id=<?=$id ?>&id_user=<?= urlencode($user['id']) ?>" class="btn-action btn-orange">Éditer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>


        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const stats = {
        birth: <?= $stats['birth'] ?>,
        marriage: <?= $stats['marriage'] ?>,
        death: <?= $stats['death'] ?>,
        signed: <?= $stats['signed'] ?>,
        pending: <?= $stats['pending'] ?>,
        validated: <?= $stats['validated'] ?>,
        rejeted: <?= $stats['rejeted'] ?>,
        total: <?= $stats['total_certificate'] ?>
    };

    // 1. Répartition des types d'actes
    new Chart(document.getElementById('chartType'), {
        type: 'pie',
        data: {
            labels: ['Naissances', 'Mariages', 'Décès'],
            datasets: [{
                data: [stats.birth, stats.marriage, stats.death],
                backgroundColor: ['#60a5fa', '#f97316', '#f43f5e']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Répartition par type d'acte"
                }
            }
        }
    });

    // 2. Actes signés vs en attente
    new Chart(document.getElementById('chartSignStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Signés', 'En attente'],
            datasets: [{
                data: [stats.signed, stats.pending],
                backgroundColor: ['#10b981', '#fbbf24']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Statut de signature"
                }
            }
        }
    });

    // 3. Validés vs Rejetés
    new Chart(document.getElementById('chartValidationStatus'), {
        type: 'bar',
        data: {
            labels: ['Validés', 'Rejetés'],
            datasets: [{
                label: 'Nombre',
                data: [stats.validated, stats.rejeted],
                backgroundColor: ['#4ade80', '#ef4444']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Validations des actes"
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 4. Line chart : évolution simulée
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
    const evolNaissance = months.map((_, i) => Math.floor(stats.birth * (0.6 + 0.07 * i)));
    const evolMariage = months.map((_, i) => Math.floor(stats.marriage * (0.5 + 0.1 * i)));
    const evolDécès = months.map((_, i) => Math.floor(stats.death * (0.4 + 0.08 * i)));

    new Chart(document.getElementById('chartEvolution'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Naissances',
                    data: evolNaissance,
                    borderColor: '#60a5fa',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Mariages',
                    data: evolMariage,
                    borderColor: '#f97316',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Décès',
                    data: evolDécès,
                    borderColor: '#f43f5e',
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Évolution mensuelle des actes "
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Réinitialiser tous les boutons
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Masquer tous les contenus
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            // Afficher l'onglet actif
            const tabId = 'tab-' + btn.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
</script>

</body>
</html>

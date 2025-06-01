<?php
session_start();

$code_demande = $_SESSION['code_demande'] ?? null;
$donnees_actes = $_SESSION['donnees_actes'] ?? [];

unset($_SESSION['code_demande'], $_SESSION['donnees_actes']);

$pageTitle = "Confirmation de demande";
$messagePrincipal = "Merci ! Votre demande a été enregistrée.";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <style>
        :root {
            --primary-color: #3498db;
            --success-color: #2ecc71;
            --text-color: #333;
            --light-bg: #f9f9f9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-bg);
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        h1, h2 {
            color: var(--primary-color);
        }

        .card {
            background: white;
            padding: 1.5rem;
            margin: 1rem 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .code {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .acte-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .print-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($messagePrincipal) ?></h1>
    </header>

    <main>
        <?php if ($code_demande): ?>
            <div class="card">
                <h2>Votre code de suivi :</h2>
                <p class="code"><?= htmlspecialchars($code_demande) ?></p>
                <p>Ce code vous permettra de suivre l’avancement de votre demande.</p>
            </div>

            <?php if (!empty($donnees_actes)): ?>
                <div class="card">
                    <h2>Détails des actes :</h2>
                    <?php foreach ($donnees_actes as $type => $actes): ?>
                        <div class="acte-item">
                            <h3><?= ucfirst(htmlspecialchars($type)) ?></h3>
                            <?php foreach ((array)$actes as $i => $details): ?>
                                <ul>
                                    <?php foreach ($details as $key => $value): ?>
                                        <li><strong><?= htmlspecialchars($key) ?> :</strong> <?= htmlspecialchars($value) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

                <button class="print-button" onclick="window.print()">Imprimer cette confirmation</button>
                <form action="dashboard.php" method="get" style="margin-top: 1rem;">
                    <button type="submit" class="print-button">🏠 Retour à l’accueil</button>
                </form>

                <p>Conservez précieusement ce code pour suivre votre demande.</p>

        <?php else: ?>
            <div class="card" style="border-left: 4px solid #e74c3c;">
                <h2>Erreur lors de l’enregistrement</h2>
                <p>Aucun code de suivi n’a été généré.</p>
                <p>Veuillez contacter le support en cas de problème.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>Service des actes administratifs - © <?= date('Y') ?></p>
    </footer>
</body>
</html>

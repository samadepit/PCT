<?php
require_once __DIR__ . '/../Controller/UserController.php';
$userController = new UserController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: administration_page.php');
    exit();
}

$user = $userController->getUserById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'numero_telephone' => $_POST['numero_telephone'],
        'profession' => $_POST['profession'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'statut' => $_POST['statut'],
    ];
    $userController->updateUserById($data);
    header('Location: administration_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'utilisateur</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding-top: 100px;
        }

        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .top-header img {
            height: 60px;
        }

        .top-header h1 {
            font-size: 20px;
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
        }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        .btn-submit,
        .btn-back {
            width: 100%;
            background-color: #10b981;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 15px;
            text-align: center;
        }

        .btn-submit:hover {
            background-color: #059669;
        }

        .btn-back {
            background-color: #3b82f6;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background-color: #2563eb;
        }

        @media screen and (max-width: 768px) {
            .top-header {
                flex-direction: column;
                text-align: center;
                padding: 10px;
            }

            .top-header h1 {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="top-header">
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
    <h1>Administration de l'état civil de Ouangolodougou</h1>
    <a href="index.php?page=logout" class="logout-btn">Déconnexion</a>
</div>

<div class="form-container">
    <h2>Modifier l'utilisateur</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
        </div>
        <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="numero_telephone" value="<?= htmlspecialchars($user['numero_telephone']) ?>" required>
        </div>
        <div class="form-group">
            <label>Profession</label>
            <input type="text" name="profession" value="<?= htmlspecialchars($user['profession']) ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Rôle</label>
            <select name="role" required>
                <option value="agent" <?= $user['role'] === 'agent' ? 'selected' : '' ?>>Agent</option>
                <option value="officier" <?= $user['role'] === 'officier' ? 'selected' : '' ?>>Officier</option>
            </select>
        </div>
        <div class="form-group">
            <label>Statut</label>
            <select name="statut" required>
                <option value="actif" <?= $user['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                <option value="inactif" <?= $user['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Enregistrer les modifications</button>
    </form>

    <a href="administration_page.php" class="btn-back">← Retour à l'administration</a>
</div>

</body>
</html>

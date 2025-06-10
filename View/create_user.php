<?php
require_once __DIR__ . '/../Controller/UserController.php';
$userController = new UserController();
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'] ?? '',
        'prenom' => $_POST['prenom'] ?? '',
        'numero_telephone' => $_POST['numero_telephone'] ?? '',
        'profession' => $_POST['profession'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'role' => $_POST['role'] ?? '',
        'statut' => $_POST['statut'] ?? '',
    ];


    $userController->createAdministrationUser($data);  

    header('Location: administration_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un utilisateur</title>
    <link rel="stylesheet" href="styles.css"> 
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
            position: fixed; 
            top: 0;           
            left: 0;
            right: 0;
            z-index: 1000;    
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

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
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

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #f97316;
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

        .btn-back {
            background-color: #3b82f6;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background-color: #2563eb;
        }

        .btn-submit:hover {
            background-color: #059669;
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
    <h2>Créer un nouvel utilisateur</h2>
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="numero_telephone">Téléphone</label>
            <input type="text" name="numero_telephone" required>
        </div>
        <div class="form-group">
            <label for="profession">Profession</label>
            <input type="text" name="profession">
        </div>
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" name="email" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" required autocomplete="new-password">
        </div>
        <div class="form-group">
            <label for="role">Rôle</label>
            <select name="role" required>
                <option value="agent">Agent</option>
                <option value="officier">Officier</option>
            </select>
        </div>
        <div class="form-group">
            <label for="statut">Statut</label>
            <select name="statut" required>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Créer l'utilisateur</button>
    </form>
    <a href="administration_page.php" class="btn-back">← Retour à la page d'acceuil</a>

</div>

</body>
</html>

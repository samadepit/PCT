<?php
session_start();
if (isset($_SESSION['erreur'])) {
    echo "<script>alert('" . $_SESSION['erreur'] . "');</script>";
    unset($_SESSION['erreur']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
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

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        h2 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 20px;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        form button {
            width: 100%;
            background-color: #f97316;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #ea580c;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #f97316;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 480px) {
            .top-header {
                flex-direction: column;
                padding: 10px;
            }

            .top-header h1 {
                font-size: 18px;
                margin: 10px 0;
            }

            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Bandeau supérieur -->
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
        <h1>Portail de l'administration de l'état civil de Ouangolodougou</h1>
    </div>

    <div class="container">
        <h2>Connexion</h2>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST" action="index.php?page=authenticate">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
            <!-- <a href="#">Mot de passe oublié ?</a> -->
        </form>
    </div>

</body>
</html>

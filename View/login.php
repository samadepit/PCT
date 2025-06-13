<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/styleConnexion.css">
   
</head>

<body>

    <?php
       require_once './partials/header.php'
     ?>

    <div class="login-wrapper">
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST" action="index.php?page=authenticate">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
            <!-- <a href="#">Mot de passe oublié ?</a> -->
        </form>
    </div>
</div>

    <?php
       require_once './partials/footer.php'
      ?>

</body>

</html>
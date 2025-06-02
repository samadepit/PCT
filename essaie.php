<?php
// pour generer un mot de passe hasher "(monmotdepasse)" pour pouvoir inserer dans la bases de données
echo password_hash('monmotdepasse', PASSWORD_DEFAULT);
?>
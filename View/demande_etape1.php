<?php
// fichier : demande_etape1.php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Demande - Étape 1</title>
    <style>
        body { font-family: Arial; max-width: 700px; margin: auto; padding: 20px; background: #f4f4f4; }
        form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin: 10px 0; }
        button { margin-top: 15px; padding: 10px 15px; background: #3498db; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>
<h2>Étape 1 : Choix des actes et localiter</h2>
<form method="post" action="demande_etape2.php">
    <label>Localité :
        <input type="text" name="localiter" required>
    </label>

    <label>Choisissez les actes à demander :</label>
    <input type="checkbox" name="actes[]" value="naissance"> Acte de naissance<br>
    <input type="checkbox" name="actes[]" value="mariage"> Acte de mariage<br>
    <input type="checkbox" name="actes[]" value="deces"> Acte de décès<br>

    <button type="submit">Suivant</button>
</form>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const checked = document.querySelectorAll("input[name='actes[]']:checked").length;
    if (checked === 0) {
        e.preventDefault();
        alert("Veuillez sélectionner au moins un acte.");
    }
});
</script>
</body>
</html>
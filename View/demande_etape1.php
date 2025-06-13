<?php
// fichier : demande_etape1.php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Demande - Étape 1</title>
     <link rel="stylesheet" href="../assets/css/styleEtape.css">
</head>

<body>

     <?php
       require_once './partials/header.php'
     ?>
     
    <div class="stepper-container container mt-4">
        <form method="post" action="demande_etape2.php" novalidate>
            <div class="header-etape">
                <h2>Étape 1 : Choix des actes et localité</h2>
            </div>

            <div class="form-grid">
                <div>
                    <label for="localiter" class="form-label">Localité :</label>
                    <input type="text" name="localiter" id="localiter" class="form-control" value="Ouangolodougou" readonly>
                </div>

                <div>
                    <label for="actes" class="form-label">Choisissez les actes à demander :</label>
                    <select name="actes[]" id="actes" class="form-select" multiple required>
                        <option value="naissance">Acte de naissance</option>
                        <option value="mariage">Acte de mariage</option>
                        <option value="deces">Acte de décès</option>
                    </select>
                    <div id="error-message" class="invalid-feedback" style="display: none;">
                        Veuillez sélectionner au moins un acte.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-25">Suivant</button>
            </div>
        </form>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const select = document.getElementById("actes");
        const errorMessage = document.getElementById("error-message");
        const selected = Array.from(select.options).filter(option => option.selected).length;
        
        if (selected === 0) {
            e.preventDefault();
            // Afficher le message d'erreur
            errorMessage.style.display = "block";
            select.classList.add("is-invalid");
        } else {
            // Masquer le message d'erreur si une sélection est faite
            errorMessage.style.display = "none";
            select.classList.remove("is-invalid");
        }
    });

    // Masquer le message d'erreur quand l'utilisateur fait une sélection
    document.getElementById("actes").addEventListener("change", function() {
        const errorMessage = document.getElementById("error-message");
        const selected = Array.from(this.options).filter(option => option.selected).length;
        
        if (selected > 0) {
            errorMessage.style.display = "none";
            this.classList.remove("is-invalid");
        }
    });
    </script>
    <?php
       require_once './partials/footer.php'
     ?>
</body>

</html>
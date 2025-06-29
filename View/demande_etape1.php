<?php
// fichier : demande_etape1.php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Demande - Étape 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/etapes.css">
   
</head>

<body>

    <?php
       require_once './partials/header.php'
     ?>


    <div class="form-page-wrapper">
        <div class="form-container">
            <!-- En-tête officiel -->


            <form method="post" action="demande_etape2.php" onsubmit="handleSubmit(event)">
                

                <h2>Choix des actes et localité</h2>

                <div class="form-group">
                    <label for="localiter">Localité de délivrance :</label>
                    <input type="text" name="localiter" id="localiter" value="Ouangolodougou" readonly>
                </div>

                <div class="form-group">
                    <label for="actes">Type(s) d'acte(s) demandé(s) :</label>
                    <select name="actes[]" id="actes" multiple required>
                        <option value="naissance">📋 Acte de naissance</option>
                        <option value="mariage">💒 Acte de mariage</option>
                        <option value="deces">⚰️ Acte de décès</option>
                    </select>
                    
                </div>

                <button type="submit" id="submitBtn">
                    <span>Étape suivante</span>
                </button>
            </form>


        </div>

        <script>
        function handleSubmit(event) {
            const button = document.getElementById('submitBtn');
            const span = button.querySelector('span');

            // Vérification de la sélection
            const select = document.getElementById('actes');
            if (select.selectedOptions.length === 0) {
                alert('Veuillez sélectionner au moins un type d\'acte.');
                event.preventDefault();
                return;
            }

            // Animation de chargement
            button.classList.add('loading');
            span.style.opacity = '0';

            // Simulation du traitement
            setTimeout(() => {
                button.classList.remove('loading');
                span.style.opacity = '1';
            }, 1500);
        }

        // Amélioration de l'expérience utilisateur
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });

            element.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Indication visuelle de la sélection multiple
        const selectElement = document.getElementById('actes');
        selectElement.addEventListener('change', function() {
            const selectedCount = this.selectedOptions.length;
            const infoDiv = document.querySelector('.select-info');

            if (selectedCount > 0) {
                infoDiv.innerHTML = `✅ ${selectedCount} type(s) d'acte(s) sélectionné(s)`;
                infoDiv.style.background = '#f0f9ff';
                infoDiv.style.borderLeftColor = '#059669';
            } else {
                infoDiv.innerHTML =
                    'Maintenez la touche Ctrl (Windows) ou Cmd (Mac) enfoncée pour sélectionner plusieurs types d\'actes';
                infoDiv.style.background = '#f8fafc';
                infoDiv.style.borderLeftColor = '#3b82f6';
            }
        });
        </script>

      
    </div>  
      <?php
       require_once './partials/footer.php'
         ?>   
</body>

</html>
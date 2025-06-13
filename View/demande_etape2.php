<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['localiter'] = $_POST['localiter'];
    $_SESSION['actes'] = $_POST['actes'];
    $actes_selectionnes = $_POST['actes'];
    $donnees_existantes = $_SESSION['donnees_actes'] ?? [];
    foreach ($donnees_existantes as $type => $data) {
        if (!in_array($type, $actes_selectionnes)) {
            unset($_SESSION['donnees_actes'][$type]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demande - Étape 2</title>
    <link rel="stylesheet" href="../assets/css/styleEtape.css">
</head>

<body>

    <?php
       require_once './partials/header.php'
     ?>


    <div class="stepper-container container mt-4">
        <form method="post" action="demande_etape3.php" novalidate>
            <div class="header-etape">
                <h2>Étape 2 : Informations sur le demandeur</h2>
            </div>

            <div class="form-grid">
                <div>
                    <label for="nom" class="form-label">Nom :</label>
                    <input type="text" name="nom" id="nom" class="form-control" pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                        required>
                    <div class="invalid-feedback" style="display: none;">Veuillez entrer un nom valide (2-50
                        caractères).</div>
                </div>

                <div>
                    <label for="prenom" class="form-label">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                        required>
                    <div class="invalid-feedback" style="display: none;">Veuillez entrer un prénom valide (2-50
                        caractères).</div>
                </div>

                <div>
                    <label for="relation" class="form-label">Relation avec le bénéficiaire :</label>
                    <select name="relation_avec_beneficiaire" id="relation" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="parent">Parent</option>
                        <option value="conjoint">Conjoint</option>
                        <option value="tuteur">Tuteur</option>
                        <option value="demandeur">Moi-même</option>
                        <option value="autre">Autre</option>
                    </select>
                    <div class="invalid-feedback" style="display: none;">Veuillez sélectionner une relation.</div>
                </div>

                <div>
                    <label for="lieu_residence" class="form-label">Lieu de résidence :</label>
                    <input type="text" name="lieu_residence" id="lieu_residence" class="form-control"
                        pattern="[A-Za-zÀ-ÿ0-9\s\-']{2,100}" required>
                    <div class="invalid-feedback" style="display: none;">Veuillez entrer un lieu de résidence valide
                        (2-100 caractères).</div>
                </div>

                <div>
                    <label for="numero_telephone" class="form-label">Téléphone :</label>
                    <input type="tel" name="numero_telephone" id="numero_telephone" class="form-control"
                        pattern="^\d{10,15}$" title="Entrez un numéro de téléphone valide (10 à 15 chiffres)" required>
                    <div class="invalid-feedback" style="display: none;">Veuillez entrer un numéro de téléphone valide
                        (10-15 chiffres).</div>
                </div>

                <div>
                    <label for="email" class="form-label">Email (optionnel) :</label>
                    <input type="email" name="email" id="email" class="form-control">
                    <div class="invalid-feedback" style="display: none;">Veuillez entrer une adresse email valide.</div>
                </div>

                <div class="">
                    <a href="demande_etape1.php" class="btn btn-secondary w-25">← Retour</a>
                    <button type="submit" class="btn btn-primary w-25">Suivant</button>
                </div>
                
            </div>
        </form>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(e) {
        let isValid = true;

        // Validation du nom
        const nom = document.getElementById("nom");
        const nomPattern = /^[A-Za-zÀ-ÿ\s\-']{2,50}$/;
        if (!nom.value.trim() || !nomPattern.test(nom.value.trim())) {
            showError(nom, "Veuillez entrer un nom valide (2-50 caractères).");
            isValid = false;
        } else {
            hideError(nom);
        }

        // Validation du prénom
        const prenom = document.getElementById("prenom");
        const prenomPattern = /^[A-Za-zÀ-ÿ\s\-']{2,50}$/;
        if (!prenom.value.trim() || !prenomPattern.test(prenom.value.trim())) {
            showError(prenom, "Veuillez entrer un prénom valide (2-50 caractères).");
            isValid = false;
        } else {
            hideError(prenom);
        }

        // Validation de la relation
        const relation = document.getElementById("relation");
        if (!relation.value) {
            showError(relation, "Veuillez sélectionner une relation.");
            isValid = false;
        } else {
            hideError(relation);
        }

        // Validation du lieu de résidence
        const lieuResidence = document.getElementById("lieu_residence");
        const lieuPattern = /^[A-Za-zÀ-ÿ0-9\s\-']{2,100}$/;
        if (!lieuResidence.value.trim() || !lieuPattern.test(lieuResidence.value.trim())) {
            showError(lieuResidence, "Veuillez entrer un lieu de résidence valide (2-100 caractères).");
            isValid = false;
        } else {
            hideError(lieuResidence);
        }

        // Validation du téléphone
        const telephone = document.getElementById("numero_telephone");
        const telPattern = /^\d{10,15}$/;
        if (!telephone.value.trim() || !telPattern.test(telephone.value.trim())) {
            showError(telephone, "Veuillez entrer un numéro de téléphone valide (10-15 chiffres).");
            isValid = false;
        } else {
            hideError(telephone);
        }

        // Validation de l'email (optionnel)
        const email = document.getElementById("email");
        if (email.value.trim() && !email.checkValidity()) {
            showError(email, "Veuillez entrer une adresse email valide.");
            isValid = false;
        } else {
            hideError(email);
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Fonction pour afficher l'erreur
    function showError(element, message) {
        const feedback = element.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
            feedback.style.display = "block";
        }
        element.classList.add("is-invalid");
    }

    // Fonction pour masquer l'erreur
    function hideError(element) {
        const feedback = element.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = "none";
        }
        element.classList.remove("is-invalid");
    }

    // Masquer les erreurs lors de la saisie
    const inputs = ["nom", "prenom", "relation", "lieu_residence", "numero_telephone", "email"];
    inputs.forEach(id => {
        const field = document.getElementById(id);
        field.addEventListener("input", function() {
            if (this.value.trim()) {
                hideError(this);
            }
        });

        if (field.tagName === "SELECT") {
            field.addEventListener("change", function() {
                if (this.value) {
                    hideError(this);
                }
            });
        }
    });
    </script>
    <?php
       require_once './partials/footer.php'
      ?>
</body>

</html>
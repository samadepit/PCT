<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['naissance'] = [
        'nom' => $_POST['nom_beneficiaire'],
        'prenom' => $_POST['prenom_beneficiaire'],
        'date_naissance' => $_POST['date_naissance'],
        'heure_naissance' => $_POST['heure_naissance'],
        'genre' => $_POST['genre'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'nom_pere' => $_POST['nom_pere'],
        'prenom_pere' => $_POST['prenom_pere'],
        'profession_pere' => $_POST['profession_pere'],
        'nom_mere' => $_POST['nom_mere'],
        'prenom_mere' => $_POST['prenom_mere'],
        'profession_mere' => $_POST['profession_mere'],
        'date_mariage' => $_POST['date_mariage'] ?: null,
        'lieu_mariage' => $_POST['lieu_mariage'] ?: null,
        'statut_mariage' => $_POST['statut_mariage'] ?: null,
        'date_deces' => $_POST['date_deces'] ?: null,
        'lieu_deces' => $_POST['lieu_deces'] ?: null,
    ];

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);
        switch ($acte_suivant) {
            case 'mariage':
                header('Location: demand_marriage.php');
                exit;
            case 'deces':
                header('Location: demand_death_certificate.php');
                exit;
        }
    }
    header('Location: traitement_final_demande.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Demande - Acte de Naissance</title>
    <link rel="stylesheet" href="../assets/css/styleEtape.css">


</head>

<body>

    <?php
       require_once './partials/header.php'
    ?>

      <div class="stepper-container container mt-4">
        <form method="post" novalidate>
            <div class="header-etape">
                <a href="demande_etape2.php" class="btn btn-retour">← Retour</a>
                <h2>Étape 3 : Acte de Naissance</h2>
            </div>

            <div class="form-grid">
                <h3>Informations du bénéficiaire</h3>
                <div class="form-row">
                    <div class="form-item">
                        <label for="nom_beneficiaire" class="form-label">Nom :</label>
                        <input type="text" name="nom_beneficiaire" id="nom_beneficiaire" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un nom valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="prenom_beneficiaire" class="form-label">Prénom :</label>
                        <input type="text" name="prenom_beneficiaire" id="prenom_beneficiaire" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un prénom valide.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-item">
                        <label for="date_naissance" class="form-label">Date de naissance :</label>
                        <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer une date de naissance valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="heure_naissance" class="form-label">Heure de naissance :</label>
                        <input type="time" name="heure_naissance" id="heure_naissance" class="form-control" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer une heure de naissance valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="genre" class="form-label">Genre :</label>
                        <select name="genre" id="genre" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                            <option value="Autre">Autre</option>
                        </select>
                        <div class="invalid-feedback" style="display: none;">Veuillez sélectionner un genre.</div>
                    </div>
                </div>

                <div class="form-item">
                    <label for="lieu_naissance" class="form-label">Lieu de naissance :</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback" style="display: none;">Veuillez entrer un lieu de naissance valide.</div>
                </div>

                <h3>Informations du père</h3>
                <div class="form-row">
                    <div class="form-item">
                        <label for="nom_pere" class="form-label">Nom :</label>
                        <input type="text" name="nom_pere" id="nom_pere" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un nom valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="prenom_pere" class="form-label">Prénom :</label>
                        <input type="text" name="prenom_pere" id="prenom_pere" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un prénom valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="profession_pere" class="form-label">Profession :</label>
                        <input type="text" name="profession_pere" id="profession_pere" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer une profession valide.</div>
                    </div>
                </div>

                <h3>Informations de la mère</h3>
                <div class="form-row">
                    <div class="form-item">
                        <label for="nom_mere" class="form-label">Nom :</label>
                        <input type="text" name="nom_mere" id="nom_mere" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un nom valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="prenom_mere" class="form-label">Prénom :</label>
                        <input type="text" name="prenom_mere" id="prenom_mere" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un prénom valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="profession_mere" class="form-label">Profession :</label>
                        <input type="text" name="profession_mere" id="profession_mere" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer une profession valide.</div>
                    </div>
                </div>

                <h3>Informations supplémentaires (facultatives)</h3>
                <div class="form-row">
                    <div class="form-item">
                        <label for="date_mariage" class="form-label">Date de mariage :</label>
                        <input type="date" name="date_mariage" id="date_mariage" class="form-control">
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer une date valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="lieu_mariage" class="form-label">Lieu de mariage :</label>
                        <input type="text" name="lieu_mariage" id="lieu_mariage" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]*$">
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un lieu valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="statut_mariage" class="form-label">Statut du mariage :</label>
                        <select> 
                         
                            <option value="celibataire">celibataire</option>
                            <option value="marié(e)">marié(e)</option>
                            <option value="divorcé(e)">divorcé(e)</option>
                        </select>
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un statut valide.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-item">
                        <label for="date_deces" class="form-label">Date de décès :</label>
                        <input type="date" name="date_deces" id="date_deces" class="form-control">
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer une date valide.</div>
                    </div>
                    <div class="form-item">
                        <label for="lieu_deces" class="form-label">Lieu de décès :</label>
                        <input type="text" name="lieu_deces" id="lieu_deces" class="form-control"
                            pattern="^[A-Za-zÀ-ÿ\s\-]*$">
                        <div class="invalid-feedback" style="display: none;">Veuillez entrer un lieu valide.</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-25">Passer à l'acte suivant</button>
            </div>
        </form>
    </div>

    <script>
    // Liste des champs requis
    const requiredFields = [
        'nom_beneficiaire', 'prenom_beneficiaire', 'date_naissance', 
        'heure_naissance', 'genre', 'lieu_naissance',
        'nom_pere', 'prenom_pere', 'profession_pere',
        'nom_mere', 'prenom_mere', 'profession_mere'
    ];

    // Liste des champs optionnels avec patterns
    const optionalFields = [
        'date_mariage', 'lieu_mariage', 'statut_mariage', 
        'date_deces', 'lieu_deces'
    ];

    // Validation lors de la soumission
    document.querySelector("form").addEventListener("submit", function(e) {
        let isValid = true;

        // Validation des champs requis
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!validateField(field, true)) {
                isValid = false;
            }
        });

        // Validation des champs optionnels (seulement s'ils sont remplis)
        optionalFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field.value.trim() && !validateField(field, false)) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Fonction de validation d'un champ
    function validateField(field, isRequired) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Vérification si le champ est requis et vide
        if (isRequired && !value) {
            isValid = false;
            message = getRequiredMessage(field);
        }
        // Vérification du pattern si le champ a une valeur
        else if (value && field.hasAttribute('pattern')) {
            const pattern = new RegExp(field.getAttribute('pattern'));
            if (!pattern.test(value)) {
                isValid = false;
                message = getPatternMessage(field);
            }
        }
        // Validation spéciale pour les dates
        else if (value && field.type === 'date') {
            const date = new Date(value);
            if (isNaN(date.getTime())) {
                isValid = false;
                message = 'Veuillez entrer une date valide.';
            }
            // Vérification que la date de naissance n'est pas dans le futur
            else if (field.id === 'date_naissance' && date > new Date()) {
                isValid = false;
                message = 'La date de naissance ne peut pas être dans le futur.';
            }
        }
        // Validation pour le select
        else if (isRequired && field.tagName === 'SELECT' && !value) {
            isValid = false;
            message = 'Veuillez faire une sélection.';
        }

        if (isValid) {
            hideError(field);
        } else {
            showError(field, message);
        }

        return isValid;
    }

    // Messages d'erreur pour les champs requis
    function getRequiredMessage(field) {
        const messages = {
            'nom_beneficiaire': 'Veuillez entrer le nom du bénéficiaire.',
            'prenom_beneficiaire': 'Veuillez entrer le prénom du bénéficiaire.',
            'date_naissance': 'Veuillez entrer la date de naissance.',
            'heure_naissance': 'Veuillez entrer l\'heure de naissance.',
            'genre': 'Veuillez sélectionner un genre.',
            'lieu_naissance': 'Veuillez entrer le lieu de naissance.',
            'nom_pere': 'Veuillez entrer le nom du père.',
            'prenom_pere': 'Veuillez entrer le prénom du père.',
            'profession_pere': 'Veuillez entrer la profession du père.',
            'nom_mere': 'Veuillez entrer le nom de la mère.',
            'prenom_mere': 'Veuillez entrer le prénom de la mère.',
            'profession_mere': 'Veuillez entrer la profession de la mère.'
        };
        return messages[field.id] || 'Ce champ est requis.';
    }

    // Messages d'erreur pour les patterns
    function getPatternMessage(field) {
        const messages = {
            'nom_beneficiaire': 'Le nom ne doit contenir que des lettres, espaces et tirets.',
            'prenom_beneficiaire': 'Le prénom ne doit contenir que des lettres, espaces et tirets.',
            'lieu_naissance': 'Le lieu ne doit contenir que des lettres, espaces et tirets.',
            'nom_pere': 'Le nom ne doit contenir que des lettres, espaces et tirets.',
            'prenom_pere': 'Le prénom ne doit contenir que des lettres, espaces et tirets.',
            'profession_pere': 'La profession ne doit contenir que des lettres, espaces et tirets.',
            'nom_mere': 'Le nom ne doit contenir que des lettres, espaces et tirets.',
            'prenom_mere': 'Le prénom ne doit contenir que des lettres, espaces et tirets.',
            'profession_mere': 'La profession ne doit contenir que des lettres, espaces et tirets.',
            'lieu_mariage': 'Le lieu ne doit contenir que des lettres, espaces et tirets.',
            'statut_mariage': 'Le statut ne doit contenir que des lettres, espaces et tirets.',
            'lieu_deces': 'Le lieu ne doit contenir que des lettres, espaces et tirets.'
        };
        return messages[field.id] || 'Format invalide.';
    }

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
    const allFields = [...requiredFields, ...optionalFields];
    allFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
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
        }
    });
    </script>

    <?php
       require_once './partials/footer.php'
    ?>
</body>

</html>
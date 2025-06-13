<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation serveur simple (exemple : nom ou prénom vide malgré le required HTML)
    if (empty($_POST['nom_defunt']) || empty($_POST['prenom_defunt'])) {
        $_SESSION['error'] = "Le nom et le prénom du défunt sont requis.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $_SESSION['donnees_actes']['deces'] = [
        'nom' => $_POST['nom_defunt'],
        'prenom' => $_POST['prenom_defunt'],
        'date_naissance' => $_POST['date_naissance'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'genre' => $_POST['genre'],
        'date_deces' => $_POST['date_deces'],
        'lieu_deces' => $_POST['lieu_deces'],
        'cause' => $_POST['cause'],
        'profession' => $_POST['profession']
    ];

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);
        switch ($acte_suivant) {
            case 'naissance':
                header('Location: demand_birth_certificate.php');
                exit;
            case 'mariage':
                header('Location: demand_marriage.php');
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
    
    <title>Demande - Acte de Décès</title>
    <link rel="stylesheet" href="../assets/css/styleEtape.css">
   
</head>

<body>
    
    <?php
       require_once './partials/header.php'
     ?> 

     <div class="stepper-container container">
    <form method="post" action="" novalidate>
        <div class="header-etape">
            <a href="demande_etape3.php" class="btn btn-retour">← Retour</a>
            <h2>Étape 4 : Acte de Décès</h2>
        </div>

        <div class="form-grid">
            <h3>Informations sur le défunt</h3>
            <div class="form-row">
                <div class="form-item">
                    <label for="nom_defunt" class="form-label">Nom :</label>
                    <input type="text" name="nom_defunt" id="nom_defunt" class="form-control" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un nom valide.</div>
                </div>
                <div class="form-item">
                    <label for="prenom_defunt" class="form-label">Prénom :</label>
                    <input type="text" name="prenom_defunt" id="prenom_defunt" class="form-control" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un prénom valide.</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-item">
                    <label for="date_naissance" class="form-label">Date de naissance :</label>
                    <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
                    <div class="invalid-feedback">Veuillez entrer une date de naissance valide.</div>
                </div>
                <div class="form-item">
                    <label for="lieu_naissance" class="form-label">Lieu de naissance :</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un lieu de naissance valide.</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-item">
                    <label for="genre" class="form-label">Genre :</label>
                    <select name="genre" id="genre" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Masculin">Masculin</option>
                        <option value="Féminin">Féminin</option>
                        <option value="Autre">Autre</option>
                    </select>
                    <div class="invalid-feedback">Veuillez sélectionner un genre.</div>
                </div>
                <div class="form-item">
                    <label for="profession" class="form-label">Profession :</label>
                    <input type="text" name="profession" id="profession" class="form-control" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer une profession valide.</div>
                </div>
            </div>

            <h3>Détails du décès</h3>
            <div class="form-row">
                <div class="form-item">
                    <label for="date_deces" class="form-label">Date de décès :</label>
                    <input type="date" name="date_deces" id="date_deces" class="form-control" required>
                    <div class="invalid-feedback">Veuillez entrer une date de décès valide.</div>
                </div>
                <div class="form-item">
                    <label for="lieu_deces" class="form-label">Lieu de décès :</label>
                    <input type="text" name="lieu_deces" id="lieu_deces" class="form-control" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un lieu de décès valide.</div>
                </div>
                <div class="form-item">
                    <label for="cause" class="form-label">Cause du décès :</label>
                    <input type="text" name="cause" id="cause" class="form-control" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer une cause valide.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-25">Soumettre la demande</button>
        </div>
    </form>
    
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        // Récupérer tous les champs
        const nomDefunt = document.querySelector('input[name="nom_defunt"]');
        const prenomDefunt = document.querySelector('input[name="prenom_defunt"]');
        const dateNaissance = document.querySelector('input[name="date_naissance"]');
        const lieuNaissance = document.querySelector('input[name="lieu_naissance"]');
        const genre = document.querySelector('select[name="genre"]');
        const profession = document.querySelector('input[name="profession"]');
        const dateDeces = document.querySelector('input[name="date_deces"]');
        const lieuDeces = document.querySelector('input[name="lieu_deces"]');
        const cause = document.querySelector('input[name="cause"]');

        // Vérifier si TOUS les champs sont vides
        const allFieldsEmpty = (
            (!nomDefunt.value || nomDefunt.value.trim() === '') &&
            (!prenomDefunt.value || prenomDefunt.value.trim() === '') &&
            (!dateNaissance.value || dateNaissance.value.trim() === '') &&
            (!lieuNaissance.value || lieuNaissance.value.trim() === '') &&
            (!genre.value || genre.value.trim() === '') &&
            (!profession.value || profession.value.trim() === '') &&
            (!dateDeces.value || dateDeces.value.trim() === '') &&
            (!lieuDeces.value || lieuDeces.value.trim() === '') &&
            (!cause.value || cause.value.trim() === '')
        );

        // Afficher le message d'erreur seulement si TOUS les champs sont vides
        if (allFieldsEmpty) {
            e.preventDefault();
            alert("Veuillez remplir au moins un champ avant de soumettre le formulaire.");
        }
    });
    </script>
</div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION['error'])): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: <?= json_encode($_SESSION['error']) ?>,
        confirmButtonColor: '#ff8008'
    });
    </script>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php
       require_once './partials/footer.php'
      ?>
</body>

</html>
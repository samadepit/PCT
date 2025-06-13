<?php
session_start();

require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';

$naissanceController = new NaissanceController();
$demandeController = new DemandeController();
$traitementController = new ActeDemandeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $husband_info = [
        'nom' => $_POST['husband_lastname'],
        'prenom' => $_POST['husband_firstname'],
        'lieu_naissance' => $_POST['husband_birth_place'],
        'date_naissance' => $_POST['husband_birth_date'],
        'genre' => 'Masculin'
    ];

    $wife_info = [
        'nom' => $_POST['wife_lastname'],
        'prenom' => $_POST['wife_firstname'],
        'lieu_naissance' => $_POST['wife_birth_place'],
        'date_naissance' => $_POST['wife_birth_date'],
        'genre' => 'Féminin'
    ];

    $husband_birth_id = $naissanceController->get_existing_birth_id($husband_info);
    $wife_birth_id = $naissanceController->get_existing_birth_id($wife_info);

    if ($husband_birth_id && $wife_birth_id) {
        $_SESSION['donnees_actes']['mariage'] = [
            'husband_birth_id' => $husband_birth_id,
            'wife_birth_id' => $wife_birth_id,
            'marriage_date' => $_POST['marriage_date'],
            'marriage_place' => $_POST['marriage_place'],
            'number_children' => $_POST['number_children'],
            'statut_marriage' => $_POST['statut_marriage']
        ];
    } else {
        if (!$husband_birth_id || !$wife_birth_id) {
            $_SESSION['error'] = !$husband_birth_id 
                ? "Acte de naissance du mari introuvable" 
                : "Acte de naissance de la femme introuvable";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if (!empty($_SESSION['actes_restants'])) {
        $acte_suivant = array_shift($_SESSION['actes_restants']);
        switch ($acte_suivant) {
            case 'naissance':
                header('Location: demand_birth_certificate.php');
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Demande - Acte de Mariage</title>
    <link rel="stylesheet" href="../assets/css/styleEtape.css">

</head>

<body>
      
     <?php
       require_once './partials/header.php'
     ?>
    
    <div class="stepper-container container">
    <form method="post" action="" novalidate>
        <div class="header-etape">
            <a href="demande_etape4.php" class="btn btn-retour">← Retour</a>
            <h2>Étape 5 : Acte de Mariage</h2>
        </div>

        <div class="form-grid">
            <h3>Informations sur le mari</h3>
            <div class="form-row">
                <div class="form-item">
                    <label for="husband_lastname" class="form-label">Nom :</label>
                    <input type="text" name="husband_lastname" id="husband_lastname" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un nom valide.</div>
                </div>
                <div class="form-item">
                    <label for="husband_firstname" class="form-label">Prénom :</label>
                    <input type="text" name="husband_firstname" id="husband_firstname" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un prénom valide.</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-item">
                    <label for="husband_birth_date" class="form-label">Date de naissance :</label>
                    <input type="date" name="husband_birth_date" id="husband_birth_date" class="form-control"
                        required>
                    <div class="invalid-feedback">Veuillez entrer une date de naissance valide.</div>
                </div>
                <div class="form-item">
                    <label for="husband_birth_place" class="form-label">Lieu de naissance :</label>
                    <input type="text" name="husband_birth_place" id="husband_birth_place" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un lieu de naissance valide.</div>
                </div>
            </div>

            <h3>Informations sur la femme</h3>
            <div class="form-row">
                <div class="form-item">
                    <label for="wife_lastname" class="form-label">Nom :</label>
                    <input type="text" name="wife_lastname" id="wife_lastname" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un nom valide.</div>
                </div>
                <div class="form-item">
                    <label for="wife_firstname" class="form-label">Prénom :</label>
                    <input type="text" name="wife_firstname" id="wife_firstname" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un prénom valide.</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-item">
                    <label for="wife_birth_date" class="form-label">Date de naissance :</label>
                    <input type="date" name="wife_birth_date" id="wife_birth_date" class="form-control" required>
                    <div class="invalid-feedback">Veuillez entrer une date de naissance valide.</div>
                </div>
                <div class="form-item">
                    <label for="wife_birth_place" class="form-label">Lieu de naissance :</label>
                    <input type="text" name="wife_birth_place" id="wife_birth_place" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un lieu de naissance valide.</div>
                </div>
            </div>

            <h3>Détails du mariage</h3>
            <div class="form-row">
                <div class="form-item">
                    <label for="marriage_date" class="form-label">Date du mariage :</label>
                    <input type="date" name="marriage_date" id="marriage_date" class="form-control" required>
                    <div class="invalid-feedback">Veuillez entrer une date de mariage valide.</div>
                </div>
                <div class="form-item">
                    <label for="marriage_place" class="form-label">Lieu du mariage :</label>
                    <input type="text" name="marriage_place" id="marriage_place" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un lieu de mariage valide.</div>
                </div>
                <div class="form-item">
                    <label for="statut_marriage" class="form-label">Statut du mariage :</label>
                    <input type="text" name="statut_marriage" id="statut_marriage" class="form-control"
                        pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                    <div class="invalid-feedback">Veuillez entrer un statut de mariage valide.</div>
                </div>
                <div class="form-item">
                    <label for="number_children" class="form-label">Nombre d'enfants :</label>
                    <input type="number" name="number_children" id="number_children" class="form-control" min="1"
                        required>
                    <div class="invalid-feedback">Veuillez entrer un nombre d'enfants supérieur à 0.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-25">Soumettre la demande</button>
        </div>
    </form>
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        // Récupérer tous les champs
        const husbandLastname = document.querySelector('input[name="husband_lastname"]');
        const husbandFirstname = document.querySelector('input[name="husband_firstname"]');
        const husbandBirthDate = document.querySelector('input[name="husband_birth_date"]');
        const husbandBirthPlace = document.querySelector('input[name="husband_birth_place"]');
        const wifeLastname = document.querySelector('input[name="wife_lastname"]');
        const wifeFirstname = document.querySelector('input[name="wife_firstname"]');
        const wifeBirthDate = document.querySelector('input[name="wife_birth_date"]');
        const wifeBirthPlace = document.querySelector('input[name="wife_birth_place"]');
        const marriageDate = document.querySelector('input[name="marriage_date"]');
        const marriagePlace = document.querySelector('input[name="marriage_place"]');
        const statutMarriage = document.querySelector('input[name="statut_marriage"]');
        const numberChildren = document.querySelector('input[name="number_children"]');

        // Vérifier si TOUS les champs sont vides
        const allFieldsEmpty = (
            (!husbandLastname.value || husbandLastname.value.trim() === '') &&
            (!husbandFirstname.value || husbandFirstname.value.trim() === '') &&
            (!husbandBirthDate.value || husbandBirthDate.value.trim() === '') &&
            (!husbandBirthPlace.value || husbandBirthPlace.value.trim() === '') &&
            (!wifeLastname.value || wifeLastname.value.trim() === '') &&
            (!wifeFirstname.value || wifeFirstname.value.trim() === '') &&
            (!wifeBirthDate.value || wifeBirthDate.value.trim() === '') &&
            (!wifeBirthPlace.value || wifeBirthPlace.value.trim() === '') &&
            (!marriageDate.value || marriageDate.value.trim() === '') &&
            (!marriagePlace.value || marriagePlace.value.trim() === '') &&
            (!statutMarriage.value || statutMarriage.value.trim() === '') &&
            (!numberChildren.value || numberChildren.value.trim() === '')
        );

        // Afficher le message d'erreur seulement si TOUS les champs sont vides
        if (allFieldsEmpty) {
            e.preventDefault();
            alert("Veuillez remplir au moins un champ avant de soumettre le formulaire.");
        }
    });
    </script>
</div>
    <?php if (!empty($_SESSION['error'])): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '<?= $_SESSION["error"] ?>',
        confirmButtonColor: '#ff8008'
    });
    </script>
    <?php unset($_SESSION['error']); endif; ?>

    <?php
       require_once './partials/footer.php'
    ?>
</body>

</html>
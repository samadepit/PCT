<?php
session_start();

require_once __DIR__ . '/../Controller/birthController.php';
require_once __DIR__ . '/../Controller/demandController.php';
require_once __DIR__ . '/../Controller/certificatedemandController.php';

$naissanceController = new NaissanceController();
$demandeController = new DemandeController();
$traitementController = new ActeDemandeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['donnees_actes']['mariage'] = [
        'nom_epoux' => $_POST['nom_epoux'] ?? '',
        'prenom_epoux' => $_POST['prenom_epoux'] ?? '',
        'date_naissance_epoux' => $_POST['date_naissance_epoux'] ?? '',
        'lieu_naissance_epoux' => $_POST['lieu_naissance_epoux'] ?? '',
        'nationalite_epoux' => $_POST['nationalite_epoux'] ?? '',
        'situation_matrimoniale_epoux' => $_POST['situation_matrimoniale_epoux'] ?? '',
        'temoin_epoux' => $_POST['temoin_epoux'] ?? '',
        'profession_epoux' => $_POST['profession_epoux'] ?? '',
        'piece_identite_epoux' => $_POST['piece_identite_epoux'] ?? '',
        'certificat_residence_epoux' => $_POST['certificat_residence_epoux'] ?? '',

        'nom_epouse' => $_POST['nom_epouse'] ?? '',
        'prenom_epouse' => $_POST['prenom_epouse'] ?? '',
        'date_naissance_epouse' => $_POST['date_naissance_epouse'] ?? '',
        'lieu_naissance_epouse' => $_POST['lieu_naissance_epouse'] ?? '',
        'nationalite_epouse' => $_POST['nationalite_epouse'] ?? '',
        'situation_matrimoniale_epouse' => $_POST['situation_matrimoniale_epouse'] ?? '',
        'temoin_epouse' => $_POST['temoin_epouse'] ?? '',
        'profession_epouse' => $_POST['profession_epouse'] ?? '',
        'piece_identite_epouse'=> $_POST['piece_identite_epouse'] ?? '',
        'certificat_residence_epouse'=> $_POST['certificat_residence_epouse'] ?? '',

        'date_mariage' => $_POST['date_mariage'] ?? '',
        'lieu_mariage' => $_POST['lieu_mariage'] ?? ''
    ];


    function saveTempFile($file, $folder = 'uploads/tmp') {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('temp_') . '.' . $ext;
            if (!is_dir($folder)) mkdir($folder, 0755, true);
            $destination = $folder . '/' . $filename;
            move_uploaded_file($file['tmp_name'], $destination);
            return $destination;
        }
        return null;
    }

    $_SESSION['donnees_actes']['mariage']['piece_identite_epoux'] = saveTempFile($_FILES['piece_identite_epoux']);
    $_SESSION['donnees_actes']['mariage']['certificat_residence_epoux'] = saveTempFile($_FILES['certificat_residence_epoux']);
    $_SESSION['donnees_actes']['mariage']['piece_identite_epouse'] = saveTempFile($_FILES['piece_identite_epouse']);
    $_SESSION['donnees_actes']['mariage']['certificat_residence_epouse'] = saveTempFile($_FILES['certificat_residence_epouse']);


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
     <link rel="stylesheet" href="../assets/css/mariage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Demande - Acte de Mariage</title>
   
</head>

<body>
    
     <?php
       require_once './partials/header.php'
     ?>

    <div class="kep">
        <div class="container">
            <div class="header">
                <h2>Acte de Mariage</h2>
            </div>
            
            <div class="form-container">
                <form method="post" enctype="multipart/form-data">
                    
                    <!-- Informations sur le conjoint -->
                    <h3 class="section-title">Informations sur le conjoint</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom_epoux">Nom *</label>
                            <input type="text" id="nom_epoux" name="nom_epoux" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom_epoux">Prénom *</label>
                            <input type="text" id="prenom_epoux" name="prenom_epoux" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_naissance_epoux">Date de naissance *</label>
                            <input type="date" id="date_naissance_epoux" name="date_naissance_epoux" required>
                        </div>
                        <div class="form-group">
                            <label for="lieu_naissance_epoux">Lieu de naissance *</label>
                            <input type="text" id="lieu_naissance_epoux" name="lieu_naissance_epoux" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nationalite_epoux">Nationalité *</label>
                            <input type="text" id="nationalite_epoux" name="nationalite_epoux" required>
                        </div>
                        <div class="form-group">
                            <label for="situation_matrimoniale_epoux">Situation matrimoniale *</label>
                            <select id="situation_matrimoniale_epoux" name="situation_matrimoniale_epoux" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="celibataire">Célibataire</option>
                                <option value="veuf">Veuf</option>
                                <option value="divorcé">Divorcé</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profession_epoux">Profession *</label>
                            <input type="text" id="profession_epoux" name="profession_epoux" required>
                        </div>
                        <div class="form-group">
                            <label for="temoin_epoux">Témoin *</label>
                            <input type="text" id="temoin_epoux" name="temoin_epoux" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="piece_identite_epoux">Pièce d'identité de l'époux * ( JPEG/PNG max 5MB)</label>
                            <input type="file" id="piece_identite_epoux" name="piece_identite_epoux" accept="application/pdf,image/jpeg,image/png" required>
                        </div>
                        <div class="form-group">
                            <label for="certificat_residence_epoux">Certificat de résidence de l'époux * ( JPEG/PNG max 5MB)</label>
                            <input type="file" id="certificat_residence_epoux" name="certificat_residence_epoux" accept="application/pdf,image/jpeg,image/png" required>
                        </div>
                    </div>

                    <!-- Informations sur la conjointe -->
                    <h3 class="section-title">Informations sur la conjointe</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom_epouse">Nom *</label>
                            <input type="text" id="nom_epouse" name="nom_epouse" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom_epouse">Prénom *</label>
                            <input type="text" id="prenom_epouse" name="prenom_epouse" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_naissance_epouse">Date de naissance *</label>
                            <input type="date" id="date_naissance_epouse" name="date_naissance_epouse" required>
                        </div>
                        <div class="form-group">
                            <label for="lieu_naissance_epouse">Lieu de naissance *</label>
                            <input type="text" id="lieu_naissance_epouse" name="lieu_naissance_epouse" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nationalite_epouse">Nationalité *</label>
                            <input type="text" id="nationalite_epouse" name="nationalite_epouse" required>
                        </div>
                        <div class="form-group">
                            <label for="situation_matrimoniale_epouse">Situation matrimoniale *</label>
                            <select id="situation_matrimoniale_epouse" name="situation_matrimoniale_epouse" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="celibataire">Célibataire</option>
                                <option value="veuf">Veuve</option>
                                <option value="divorcé">Divorcé</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profession_epouse">Profession *</label>
                            <input type="text" id="profession_epouse" name="profession_epouse" required>
                        </div>
                        <div class="form-group">
                            <label for="temoin_epouse">Témoin *</label>
                            <input type="text" id="temoin_epouse" name="temoin_epouse" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="piece_identite_epouse">Pièce d'identité de l'épouse * ( JPEG/PNG max 5MB)</label>
                            <input type="file" id="piece_identite_epouse" name="piece_identite_epouse" accept="application/pdf,image/jpeg,image/png" required>
                        </div>
                        <div class="form-group">
                            <label for="certificat_residence_epouse">Certificat de résidence de l'épouse * ( JPEG/PNG max 5MB)</label>
                            <input type="file" id="certificat_residence_epouse" name="certificat_residence_epouse" accept="application/pdf,image/jpeg,image/png" required>
                        </div>
                    </div>

                    <!-- Détails du mariage -->
                    <h3 class="section-title">Détails du mariage</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_mariage">Date du mariage *</label>
                            <input type="date" id="date_mariage" name="date_mariage" required>
                        </div>
                        <div class="form-group">
                            <label for="lieu_mariage">Lieu du mariage *</label>
                            <input type="text" id="lieu_mariage" name="lieu_mariage" value="Ouangolodougou" readonly required>
                        </div>
                    </div>

                    <div class="button-group">
                        <a href="demande_etape2.php" class="back-btn">← Retour</a>
                        <button type="submit" class="submit-btn">Soumettre la demande</button>
                        
                    </div>
                </form>
            </div>
        </div>
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
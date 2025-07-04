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

    $_SESSION['donnees_actes']['naissance']['piece_identite_pere'] = saveTempFile($_FILES['piece_identite_pere']);
    $_SESSION['donnees_actes']['naissance']['piece_identite_mere'] = saveTempFile($_FILES['piece_identite_mere']);
    $_SESSION['donnees_actes']['naissance']['certificat_de_naissance'] = saveTempFile($_FILES['certificat_de_naissance']);

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

    <link rel="stylesheet" href="../assets/css/birth.css">

</head>

<body>

    <?php
       require_once './partials/header.php'
     ?>

    <div class="kep">
        <div class="container">
            <div class="header">
                <h2>Acte de Naissance</h2>
            </div>

            <div class="form-container">
                <form method="post" enctype="multipart/form-data">
                    <div class="color-head">
                        <h3 class="section-title">Informations du bénéficiaire</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom_beneficiaire">Nom *</label>
                                <input type="text" id="nom_beneficiaire" name="nom_beneficiaire"
                                    pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom_beneficiaire">Prénom *</label>
                                <input type="text" id="prenom_beneficiaire" name="prenom_beneficiaire"
                                    pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_naissance">Date de naissance *</label>
                                <input type="date" id="date_naissance" name="date_naissance" required>
                            </div>
                            <div class="form-group">
                                <label for="heure_naissance">Heure de naissance *</label>
                                <input type="time" id="heure_naissance" name="heure_naissance" required>
                            </div>
                            <div class="form-group">
                                <label for="genre">Genre *</label>
                                <select id="genre" name="genre" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Masculin">Masculin</option>
                                    <option value="Féminin">Féminin</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="lieu_naissance">Lieu de naissance *</label>
                                <input type="text" id="lieu_naissance" name="lieu_naissance"
                                    pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="certificat_de_naissance">Certificat de naissance * (PDF/JPEG/PNG max
                                    5MB)</label>
                                <input type="file" id="certificat_de_naissance" name="certificat_de_naissance"
                                    accept="application/pdf,image/jpeg,image/png" required>
                            </div>
                        </div>

                        <!-- Informations du père -->
                        <h3 class="section-title">Informations du père</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom_pere">Nom *</label>
                                <input type="text" id="nom_pere" name="nom_pere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom_pere">Prénom *</label>
                                <input type="text" id="prenom_pere" name="prenom_pere" pattern="^[A-Za-zÀ-ÿ\s\-]+$"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="profession_pere">Profession *</label>
                                <input type="text" id="profession_pere" name="profession_pere"
                                    pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="piece_identite_pere">Pièce d'identité du père * (PDF/JPEG/PNG max
                                    5MB)</label>
                                <input type="file" id="piece_identite_pere" name="piece_identite_pere"
                                    accept="application/pdf,image/jpeg,image/png" required>
                            </div>
                        </div>



                    </div>

                    <div class="color-head">

                        <!-- Informations de la mère -->
                        <h3 class="section-title">Informations de la mère</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom_mere">Nom *</label>
                                <input type="text" id="nom_mere" name="nom_mere" pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom_mere">Prénom *</label>
                                <input type="text" id="prenom_mere" name="prenom_mere" pattern="^[A-Za-zÀ-ÿ\s\-]+$"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="profession_mere">Profession *</label>
                                <input type="text" id="profession_mere" name="profession_mere"
                                    pattern="^[A-Za-zÀ-ÿ\s\-]+$" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="piece_identite_mere">Pièce d'identité de la mère * (PDF/JPEG/PNG max
                                    5MB)</label>
                                <input type="file" id="piece_identite_mere" name="piece_identite_mere"
                                    accept="application/pdf,image/jpeg,image/png" required>
                            </div>
                        </div>

                    </div>

                    <div class="color-footer">
                        <!-- Informations supplémentaires -->
                        <h3 class="section-title">Informations supplémentaires (facultatives)</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_mariage">Date de mariage</label>
                                <input type="date" id="date_mariage" name="date_mariage">
                            </div>
                            <div class="form-group">
                                <label for="lieu_mariage">Lieu de mariage</label>
                                <input type="text" id="lieu_mariage" name="lieu_mariage" pattern="^[A-Za-zÀ-ÿ\s\-]*$">
                            </div>
                            <div class="form-group">
                                <label for="statut_mariage">Statut du mariage</label>
                                <input type="text" id="statut_mariage" name="statut_mariage"
                                    pattern="^[A-Za-zÀ-ÿ\s\-]*$">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_deces">Date de décès</label>
                                <input type="date" id="date_deces" name="date_deces">
                            </div>
                            <div class="form-group">
                                <label for="lieu_deces">Lieu de décès</label>
                                <input type="text" id="lieu_deces" name="lieu_deces" pattern="^[A-Za-zÀ-ÿ\s\-]*$">
                            </div>
                        </div>

                    </div>


                    <div class="button-group">
                        <a href="demande_etape2.php" class="back-btn">← Retour</a>
                        <button type="submit" class="submit-btn">Soumettre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
       require_once './partials/footer.php'
         ?>
</body>

</html>
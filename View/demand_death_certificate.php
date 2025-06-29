<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['nom_defunt']) || empty($_POST['prenom_defunt'])) {
        $_SESSION['error'] = "Le nom et le prénom du défunt sont requis.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $_SESSION['donnees_actes']['deces'] = [
        'nom_defunt' => $_POST['nom_defunt'],
        'prenom_defunt' => $_POST['prenom_defunt'],
        'date_naissance' => $_POST['date_naissance'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'genre' => $_POST['genre'],
        'profession' => $_POST['profession'],
        'date_deces' => $_POST['date_deces'],
        'lieu_deces' => $_POST['lieu_deces'],
        'cause' => $_POST['cause']
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

    $_SESSION['donnees_actes']['deces']['certificat_medical_deces'] = saveTempFile($_FILES['certificat_medical_deces']);
    $_SESSION['donnees_actes']['deces']['piece_identite_defunt'] = saveTempFile($_FILES['piece_identite_defunt']);

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

// echo "<pre>Données à insérer :";
// print_r([
//     'date_naissance' => $_POST['date_naissance'],
//     'lieu_naissance' => $_POST['lieu_naissance']
// ]);
// echo "</pre>";
// exit;
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Demande - Acte de Décès</title>
    <link rel="stylesheet" href="../assets/css/deces.css">
    
    
  
</head>

<body>

    <?php
       require_once './partials/header.php'
     ?>

     <div class="kep">
        <div class="container">
        <div class="header">
            <h2>Acte de Décès</h2>
        </div>
        
        <div class="form-container">
            <form method="post" enctype="multipart/form-data">
                <h3 class="section-title">Informations sur le défunt</h3>

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom_defunt" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="prenom_defunt" required>
                    </div>
                </div>

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label>Date de naissance</label>
                        <input type="date" name="date_naissance" required>
                    </div>
                    <div class="form-group">
                        <label>Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" required>
                    </div>
                </div>

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label>Genre</label>
                        <select name="genre" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Profession</label>
                        <input type="text" name="profession" required>
                    </div>
                </div>

                <h3 class="section-title">Détails du décès</h3>
                
                <div class="form-row form-row-3">
                    <div class="form-group">
                        <label>Date de décès</label>
                        <input type="date" name="date_deces" required>
                    </div>
                    <div class="form-group">
                        <label>Lieu de décès</label>
                        <input type="text" name="lieu_deces" value="Ouangolodougou" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Cause du décès</label>
                        <input type="text" name="cause" required>
                    </div>
                </div>

                <div class="file-section">
                    <div class="form-group">
                        <label>Certificat médical de décès (PDF/JPEG/PNG max 5MB)</label>
                        <input type="file" name="certificat_medical_deces" accept="application/pdf,image/jpeg,image/png">
                    </div>
                    <div class="form-group">
                        <label>Pièce d'identité du défunt (PDF/JPEG/PNG max 5MB)</label>
                        <input type="file" name="piece_identite_defunt" accept="application/pdf,image/jpeg,image/png">
                    </div>
                </div>

                <div class="button-group">
                    <a href="demande_etape2.php" class="back-btn">← Retour</a>
                    <button type="submit" class="submit-btn">Soumettre</button>
                </div>
            </form>
        </div>
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
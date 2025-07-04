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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/demendeur.css">
</head>

<body>

    <?php
       require_once './partials/header.php'
     ?>

     <div class="kep">
        <div class="container">
            <!-- Header avec bouton retour -->
            <div class="header-etape">
                
                <h2>Étape 2 : Informations sur le demandeur</h2>
            </div>

            <div class="form-container">
                <form method="post" action="demande_etape3.php" enctype="multipart/form-data">
                    
                    <!-- Section Informations personnelles -->
                    <div class="section-title">Informations personnelles</div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" name="nom" id="nom" pattern="[A-Za-zÀ-ÿ\s\-']{2,50}" required
                                placeholder="Entrez votre nom">
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom :</label>
                            <input type="text" name="prenom" id="prenom" pattern="[A-Za-zÀ-ÿ\s\-']{2,50}" required
                                placeholder="Entrez votre prénom">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="relation">Relation avec le bénéficiaire :</label>
                            <select name="relation_avec_beneficiaire" id="relation" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="parent">Parent</option>
                                <option value="conjoint">Conjoint</option>
                                <option value="tuteur">Tuteur</option>
                                <option value="demandeur">Moi-même</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lieu_residence">Lieu de résidence :</label>
                            <input type="text" name="lieu_residence" id="lieu_residence" pattern="[A-Za-zÀ-ÿ0-9\s\-']{2,100}"
                                required placeholder="Votre lieu de résidence">
                        </div>
                    </div>

                    <!-- Section Contact -->
                    <div class="section-title">Informations de contact</div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label for="numero_telephone">Téléphone :</label>
                            <input type="tel" name="numero_telephone" id="numero_telephone" pattern="^\d{10,15}$"
                                title="Entrez un numéro de téléphone valide (10 à 15 chiffres)" required
                                placeholder="Ex: 0123456789">
                        </div>

                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" name="email" id="email" placeholder="votre.email@exemple.com" required>
                        </div>
                    </div>

                    <!-- Section Documents -->
                    <div class="section-title">Documents requis</div>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="file">Pièce d'identité ( JPEG/PNG max 5MB) :</label>
                            <input type="file" name="piece_identite_demandeur" id="file" 
                                accept="application/pdf,image/jpeg,image/png" required>
                        </div>
                    </div>

                    <!-- Exemple avec plusieurs sections pour un formulaire plus complexe -->
                    <div class="section-title">Informations complémentaires (optionnel)</div>
                    
                    <div class="file-section">
                        <div class="form-group">
                            <label for="justificatif_domicile">Justificatif de domicile :</label>
                            <input type="file" name="justificatif_domicile" id="justificatif_domicile" 
                                accept="application/pdf,image/jpeg,image/png">
                        </div>

                        <div class="form-group">
                            <label for="autre_document">Autre document :</label>
                            <input type="file" name="autre_document" id="autre_document" 
                                accept="application/pdf,image/jpeg,image/png">
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="button-group">
                        <a href="demande_etape1.php" class="back-btn">Précédent</a>
                        <button type="submit" class="submit-btn">Suivant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    // Animation pour le stepper
    document.addEventListener('DOMContentLoaded', function() {
        const steps = document.querySelectorAll('.step');
        const connectors = document.querySelectorAll('.step-connector');

        // Animation d'entrée progressive
        steps.forEach((step, index) => {
            step.style.animation = `fadeInUp 0.6s ease-out ${index * 0.2}s both`;
        });
    });

    // Validation en temps réel
    const inputs = document.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.style.borderColor = '#10b981';
            } else {
                this.style.borderColor = '#ef4444';
            }
        });
    });
    </script>

    <?php
       require_once './partials/footer.php'
    ?>
</body>

</html>
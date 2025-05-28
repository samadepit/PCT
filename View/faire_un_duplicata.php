<?php
session_start();
require_once __DIR__ . '/../Controller/certificate_duplicateController.php';

$controller = new ActeDuplicataController();
$actes = [];
$erreur = '';
$type='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type_acte']);
    $numero = trim($_POST['numero_registre']);
    $date = trim($_POST['evenement_date']);
    // var_dump([
    //     'type' => $type,
    //     'numero' => $numero,
    //     'date' => $date
    // ]);
    // die();
    if (!empty($type) && !empty($numero) && !empty($date)) {
        try {
            $actes = $controller->getActeByRegistreAndDate($type, $numero, $date);
            if (empty($actes)) {
                $_SESSION['erreur'] = "Aucun acte trouvé avec ces informations.";
            } else {
                $_SESSION['actes'] = $actes;
                $_SESSION['type_acte'] = $type;
                $_SESSION['code_demande_duplicate']=$actes['code_demande'];
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = "Erreur : " . $e->getMessage();
        }
    } else {
        $_SESSION['erreur'] = "Veuillez remplir tous les champs.";
    }
    // header("Location: " . $_SERVER['PHP_SELF']);
    // exit();
}

if (isset($_SESSION['actes'])) {
    $actes = $_SESSION['actes'];
    // unset($_SESSION['actes']);
}

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];  
    unset($_SESSION['erreur']);
}
?>

<form method="POST">
    <label for="type_acte">Type d'acte :</label>
    <select id="type_acte" name="type_acte" required>
        <option value="">-- Sélectionner --</option>
        <option value="naissance">Naissance</option>
        <option value="mariage">Mariage</option>
        <option value="deces">Décès</option>
    </select>

    <label for="numero_registre">Numéro de registre :</label>
    <input type="text" id="numero_registre" name="numero_registre" required>

    <label for="evenement_date">Date de l'evenement(date_naissanse/data_mariage/date_deces):</label>
    <input type="date" id="evenement_date" name="evenement_date" required>

    <button type="submit">🔍 Rechercher</button>
</form>

<?php if (!empty($actes) && is_array($actes)): ?>
    <div class="acte-detail">
        <?php if ($type === 'naissance'): ?>
            <h3>Acte de naissance</h3>
            <p><strong>Nom :</strong> <?= htmlspecialchars($actes['nom_beneficiaire']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($actes['prenom_beneficiaire']) ?></p>
            <p><strong>Lieu de naissance :</strong> <?= htmlspecialchars($actes['lieu_naissance']) ?></p>
            <p><strong>Date de naissance :</strong> <?= htmlspecialchars($actes['date_naissance']) ?></p>
            <button type="submit"><a href="paiement.php" class="button">Passez au paiement</a>Passez au paiement</button>
            
        <?php elseif ($type === 'deces'): ?>
            <h3>Acte de décès</h3>
            <p><strong>Nom du défunt :</strong> <?= htmlspecialchars($actes['nom_beneficiaire']) ?></p>
            <p><strong>Prénom du défunt :</strong> <?= htmlspecialchars($actes['prenom_beneficiaire']) ?></p>
            <p><strong>Lieu de décès :</strong> <?= htmlspecialchars($actes['lieu_deces']) ?></p>
            <p><strong>Date de décès :</strong> <?= htmlspecialchars($actes['date_deces']) ?></p>
            <button type="submit"><a href="paiement.php" class="button">Passez au paiement</a>Passez au paiement</button>
        <?php elseif ($type === 'mariage'): ?>
            <h3>Acte de mariage</h3>
            <p><strong>Époux :</strong> <?= htmlspecialchars($actes['nom_mari']) ?> <?= htmlspecialchars($actes['prenom_mari']) ?></p>
            <p><strong>Épouse :</strong> <?= htmlspecialchars($actes['nom_femme']) ?> <?= htmlspecialchars($actes['prenom_femme']) ?></p>
            <p><strong>Lieu de mariage :</strong> <?= htmlspecialchars($actes['lieu_mariage']) ?></p>
            <p><strong>Date de mariage :</strong> <?= htmlspecialchars($actes['date_mariage']) ?></p>
            <button type="submit"><a href="paiement.php" class="button">Passez au paiement</a>Passez au paiement</button>
            
        <?php endif; ?>
    </div>
<?php endif; ?>




<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f7fa;
        padding: 30px;
        text-align: center;
    }

    form {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: inline-block;
        text-align: left;
        max-width: 450px;
        width: 100%;
        margin-bottom: 30px;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color: #2563eb;
    }

    .acte {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        max-width: 700px;
        margin: auto;
        margin-bottom: 25px;
        text-align: left;
    }

    .erreur {
        background: #fee2e2;
        color: #b91c1c;
        padding: 12px;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        margin-bottom: 20px;
        max-width: 450px;
        margin: auto;
    }

    h3 {
        color: #1f2937;
        margin-bottom: 20px;
    }

    summary {
        font-weight: bold;
        margin-top: 15px;
        cursor: pointer;
    }

    details {
        margin-top: 10px;
    }

    .acte-detail {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px 30px;
    max-width: 600px;
    margin: 30px auto;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    text-align: left;
    line-height: 1.6;
}

.acte-detail h3 {
    font-size: 20px;
    color: #111827;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.acte-detail p {
    margin: 8px 0;
    color: #374151;
}

.acte-detail strong {
    color: #1f2937;
}
</style>

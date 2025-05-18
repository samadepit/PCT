<?php
session_start();
require_once __DIR__ . '/../Controller/certificate_duplicateController.php';

$controller = new ActeDuplicataController();
$actes = [];
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type_acte']);
    $numero = trim($_POST['numero_registre']);
    $date = trim($_POST['date_enregistrement']);

    if (!empty($type) && !empty($numero) && !empty($date)) {
        try {
            $actes = $controller->getActeByRegistreAndDate($type, $numero, $date);
            if (empty($actes)) {
                $_SESSION['erreur'] = "Aucun acte trouvé avec ces informations.";
            } else {
                $_SESSION['actes'] = $actes;
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = "Erreur : " . $e->getMessage();
        }
    } else {
        $_SESSION['erreur'] = "Veuillez remplir tous les champs.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_SESSION['actes'])) {
    $actes = $_SESSION['actes'];
    unset($_SESSION['actes']);
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

    <label for="date_enregistrement">Date d'enregistrement :</label>
    <input type="date" id="date_enregistrement" name="date_enregistrement" required>

    <button type="submit">🔍 Rechercher</button>
</form>

<?php if (!empty($erreur)): ?>
    <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<?php if (!empty($actes)): ?>
    <h3>Acte trouvé :</h3>
    <?php foreach ($actes as $acte): ?>
        <div class="acte">
            <strong>Type :</strong> <?= htmlspecialchars($acte['type']) ?><br>
            <strong>Numéro :</strong> <?= htmlspecialchars($acte['numero_registre']) ?><br>
            <strong>Date :</strong> <?= htmlspecialchars($acte['date_enregistrement']) ?><br>
            <details>
                <summary>Voir les détails</summary>
                <div><?= nl2br(htmlspecialchars($acte['details'])) ?></div>
            </details>
        </div>
    <?php endforeach; ?>
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
</style>

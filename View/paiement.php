<?php 
session_start();

$type = $_SESSION['actes'] ?? null;
$montant = 0;
$code_demande=$_GET["code_demande"];
if (count($type) == 3) {
    $montant = 15000;
} elseif (count($type) == 2) {
    $montant = 10000;
} else {
    $montant = 5000;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['numero'])) {
        $_SESSION['numero_telephone'] = $_POST['numero'];
        $_SESSION['code_paiement'] = 'TRC' . random_int(100, 999);
        // var_dump($_SESSION['code_paiement']);
        header("Location: verify_code.php?code_demande=" . urlencode($code_demande));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f3f3;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .price {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .agregateurs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .agregateurs input[type="radio"] {
            display: none;
        }

        .agregateurs label {
            border: 3px solid transparent;
            border-radius: 10px;
            padding: 5px;
            transition: border 0.3s ease;
        }

        .agregateurs img {
            width: 80px;
            height: auto;
            transition: transform 0.3s ease;
            border-radius: 8px;
        }

        .agregateurs label:hover img {
            transform: scale(1.05);
        }

        /* Bordure personnalisée selon l'agrégateur */
        input[type="radio"][value="wave"]:checked + img {
            border: 3px solid #007BFF; /* Bleu */
        }

        input[type="radio"][value="orange"]:checked + img {
            border: 3px solid #FFA500; /* Orange */
        }

        input[type="tel"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #00aaff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #008ecc;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Choisissez votre moyen de paiement</h2>
    <div class="price">Montant à payer : <?= $montant ?> F CFA</div>
    <form method="POST" action="paiement.php?code_demande=<?= urlencode($code_demande) ?>">
    <div class="agregateurs">
            <label>
                <input type="radio" name="agregateur" value="wave" required>
                <img src="data:image/jpeg;base64,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" alt="Wave">
            </label>
            <label>
                <input type="radio" name="agregateur" value="orange" required>
                <img src="data:image/png;base64,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" alt="Orange Money">
            </label>
        </div>
        <input type="tel" name="numero" placeholder="Numéro de téléphone" required>
        <button type="submit">Payez</button>
    </form>
</div>

</body>
</html>

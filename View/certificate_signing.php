<?php 
require_once __DIR__ . '/../Controller/signingController.php';

if (empty($_GET['code_demande'])) {
    die("Erreur : Code de demande requis");
}
$id = $_GET['id'] ?? null;

$codeDemande = htmlspecialchars($_GET['code_demande']);
$sSigningController = new SigningController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['signature'])) {
    $sSigningController->handleRequest();
    exit; 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Signature</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .top-header {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .top-header img {
            height: 60px;
        }

        .top-header h1 {
            font-size: 22px;
            color: #1f2937;
            font-weight: bold;
            flex: 1;
            text-align: center;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #f97316;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #ea580c;
        }

        .btn-effacer {
        background-color: #ef4444;
        }

        .btn-effacer:hover {
            background-color: #dc2626;
        }

        .btn-enregistrer {
            background-color: #10b981;
        }

        .btn-enregistrer:hover {
            background-color: #059669;
        }

        .btn-retour {
            margin-top: 20px;
            background-color: #3b82f6;
        }

        .btn-retour:hover {
            background-color: #2563eb;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            padding: 0 15px;
        }

        h2 {
            text-align: center;
            color: #1f2937;
            font-size: 22px;
            margin-bottom: 20px;
        }

        #signature-pad {
            width: 100%;
            max-width: 100%;
            height: 200px;
            border: 2px solid #d1d5db;
            background: #FFFFFF;
            border-radius: 8px;
        }

        .signature-actions {
        text-align: center;
        margin-top: 20px;
        }

        .top-buttons {
            display: flex;
            justify-content: center;
            gap: 20px; /* espace horizontal entre les deux premiers boutons */
            margin-bottom: 20px; /* espace vertical avec le bouton retour */
        }

        .btn-effacer {
            background-color: #ef4444;
        }

        .btn-effacer:hover {
            background-color: #dc2626;
        }

        .btn-enregistrer {
            background-color: #10b981;
        }

        .btn-enregistrer:hover {
            background-color: #059669;
        }

        .btn-retour {
            background-color: #3b82f6;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-retour:hover {
            background-color: #2563eb;
        }


        form {
            display: none;
        }

        button, .btn {
            padding: 10px 24px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Petit effet de survol agréable */
        button:hover, .btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        @media screen and (max-width: 600px) {
            .top-header {
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }

        .top-header h1 {
            font-size: 18px;
        }

        .logout-btn {
            margin-top: 10px;
        }
            .btn {
                padding: 8px 14px;
                font-size: 14px;
                margin: 5px;
                width: 100%;
            }

            .top-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .btn-retour {
                width: 100%;
            }

            h2 {
                font-size: 18px;
            }

            .top-header h1 {
                font-size: 18px;
            }

            .container {
                padding: 0 10px;
            }
        }

    </style>
</head>
<body>
    <div class="top-header">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCPIRahRkX8w3AK0ahlZKqhkZi22eMtSf6qg&s" alt="Logo CI">
        <h1>Portail des Officiers de l'état civil</h1>
        <a href="index.php?page=logout" class="logout-btn">Déconnexion</a>
    </div>

    <!-- <div class="top-header">
        <img src="../Public/img/logo.png" alt="Logo">
        <h1>Plateforme des Demandes</h1>
        <a href="javascript:history.back()" class="back-btn">← Retour</a>
    </div> -->

    <div class="container">
        <h2>Veuillez signer pour valider la demande</h2>

        <canvas id="signature-pad"></canvas>

        <div class="signature-actions">
            <div class="top-buttons">
                <button id="clear-btn" class="btn btn-effacer">Effacer</button>
                <button id="save-btn" class="btn btn-enregistrer">Enregistrer</button>
            </div>
            <a href="officier_page.php?id=<?= urlencode($id) ?>" class="btn btn-retour">← Retour</a>
        </div>


        <form id="signature-form" method="post">
            <input type="hidden" name="code_demande" value="<?= $codeDemande ?>">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="signature" id="signature-data">
        </form>
    </div>

<script>
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;

    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';

    function getCursorPosition(e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: (e.clientX || e.touches[0].clientX) - rect.left,
            y: (e.clientY || e.touches[0].clientY) - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        const { x, y } = getCursorPosition(e);
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        const { x, y } = getCursorPosition(e);
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function stopDrawing() {
        isDrawing = false;
        ctx.beginPath();
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    document.getElementById('clear-btn').addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    });

    document.getElementById('save-btn').addEventListener('click', () => {
        if (isCanvasEmpty()) {
            alert('Veuillez ajouter votre signature avant de valider.');
            return;
        }
        document.getElementById('signature-data').value = canvas.toDataURL();
        document.getElementById('signature-form').submit();
    });

    function isCanvasEmpty() {
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.fillStyle = '#f8f8f8';
        tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        return canvas.toDataURL() === tempCanvas.toDataURL();
    }
</script>

</body>
</html>

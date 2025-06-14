<?php 
session_start();

require_once __DIR__ . '/../Controller/signingController.php';
require_once __DIR__ . '/../service/mail_functions.php';
require_once __DIR__ . '/../Controller/requestroController.php';

$id = $_POST['id'] ?? $_GET['id'] ?? null;
$code_demande = $_POST['code_demande'] ?? $_GET['code_demande'] ?? null;

if (empty($id) || empty($code_demande)) {
    $_SESSION['erreur'] = "Accès invalide. Vous avez été redirigé vers la page de connexion.";
    header("Location: login.php");
    exit;
}

$codeDemande = htmlspecialchars($_GET['code_demande']);
$id = htmlspecialchars($id);
$sSigningController = new SigningController();
$requestroController = new DemandeurController();
$emailRequestro = $requestroController->get_requestor_mail($codeDemande);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['signature'])) {
    notifierDemandeur($emailRequestro, $codeDemande, 'signe');
    $sSigningController->handleRequest($id);
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
let lastX = 0;
let lastY = 0;

function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.style.width = canvas.offsetWidth + 'px';
    canvas.style.height = canvas.offsetHeight + 'px';
    ctx.scale(ratio, ratio);
    clearCanvas();
}
window.addEventListener('resize', resizeCanvas);
resizeCanvas();

function clearCanvas() {
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
}

function getCursorPosition(e) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    
    const clientX = e.clientX || (e.touches ? e.touches[0].clientX : 0);
    const clientY = e.clientY || (e.touches ? e.touches[0].clientY : 0);
    
    return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY
    };
}

function startDrawing(e) {
    isDrawing = true;
    const pos = getCursorPosition(e);
    lastX = pos.x;
    lastY = pos.y;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
}

function draw(e) {
    if (!isDrawing) return;
    
    e.preventDefault();
    const pos = getCursorPosition(e);
    
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
    
    lastX = pos.x;
    lastY = pos.y;
}

function stopDrawing() {
    isDrawing = false;
}

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseout', stopDrawing);

canvas.addEventListener('touchstart', function(e) {
    e.preventDefault();
    startDrawing(e.touches[0]);
});
canvas.addEventListener('touchmove', function(e) {
    e.preventDefault();
    draw(e.touches[0]);
}, { passive: false });
canvas.addEventListener('touchend', stopDrawing);

document.getElementById('clear-btn').addEventListener('click', clearCanvas);
document.getElementById('save-btn').addEventListener('click', function() {
    if (isCanvasEmpty()) {
        alert('Veuillez ajouter votre signature avant de valider.');
        return;
    }
    document.getElementById('signature-data').value = canvas.toDataURL();
    document.getElementById('signature-form').submit();
});

function isCanvasEmpty() {
    const blank = document.createElement('canvas');
    blank.width = canvas.width;
    blank.height = canvas.height;

    const bctx = blank.getContext('2d');
    bctx.fillStyle = '#FFFFFF';
    bctx.fillRect(0, 0, blank.width, blank.height);

    return canvas.toDataURL() === blank.toDataURL();
}
document.getElementById('save-btn').addEventListener('click', function() {
    const saveBtn = this;

    if (isCanvasEmpty()) {
        alert('Veuillez ajouter votre signature avant de valider.');
        return;
    }

    saveBtn.disabled = true;
    saveBtn.textContent = 'Enregistrement...';

    document.getElementById('signature-data').value = canvas.toDataURL();
    document.getElementById('signature-form').submit();
});

</script>

</body>
</html>

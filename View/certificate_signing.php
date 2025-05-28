<?php
require_once __DIR__ . '/../Controller/signingController.php';

if (empty($_GET['code_demande'])) {
    die("Erreur : Code de demande requis");
}

$codeDemande = htmlspecialchars($_GET['code_demande']);
$sSigningController = new SigningController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['signature'])) {
    $sSigningController->handleRequest();
    exit; 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signature</title>
    <style>
        #signature-pad {
            border: 1px solid #000;
            background: #f8f8f8;
            margin: 20px 0;
        }
        .signature-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .btn {
            padding: 8px 15px;
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="signature-container">
        <h2>Signature requise</h2>
        
        <canvas id="signature-pad" width="500" height="200"></canvas>
        
        <div>
            <button id="clear-btn" class="btn">Effacer</button>
            <button id="save-btn" class="btn">Enregistrer</button>
        </div>
        
        <form id="signature-form" method="post">
            <input type="hidden" name="code_demande" value="<?= $codeDemande ?>">
            <input type="hidden" name="signature" id="signature-data">
        </form>
    </div>

<script>
    
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    
    ctx.fillStyle = '#f8f8f8';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    
    function startDrawing(e) {
        isDrawing = true;
        ctx.beginPath();
        draw(e); 
    }
    
    function stopDrawing() {
        isDrawing = false;
    }
    
    function draw(e) {
        if (!isDrawing) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }
    
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    document.getElementById('clear-btn').addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#f8f8f8';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    });
    
    document.getElementById('save-btn').addEventListener('click', () => {
        if (isCanvasEmpty()) {
            alert('Veuillez ajouter votre signature avant de valider');
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
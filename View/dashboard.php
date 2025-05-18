<?php
?>
<body>
<title>Accueil - État Civil</title>
    <h1>Bienvenue sur le Portail de l'État Civil</h1>
    <div class="button-container">
        <a href="demande_etape1.php" class="button">Faire une demande</a>
        <a href="consulter_demande.php" class="button">Suivre une demande</a>
        <a href="faire_duplicata.php" class="button">Faire un duplicata</a>
    </div>
    <footer>
        &copy; 2025 Portail de l'État Civil - Tous droits réservés
    </footer>
</body>
<style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .button {
            background-color: #ffffff;
            color: #2980b9;
            border: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            text-decoration: none;
        }

        .button:hover {
            background-color: #2980b9;
            color: #fff;
        }

        footer {
            position: absolute;
            bottom: 20px;
            font-size: 0.9rem;
            color: #eee;
        }
    </style>
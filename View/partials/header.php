<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <header>
        <div class="bg-orange  justify-content-center p-2 mb-2" style="height: 70px;">
            <div class="container text-start text-white d-flex align-items-center  justify-content-between">
                <p class="m-0  fancy-text">Votre Mairie a votre porte</p>
                <div class="social-icons ">
                    <a title='facebook CVAN' href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a title='X CVAN' href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                    <a title='Compte Linkedin' href="#" class="text-white me-2"><i class="bi bi-linkedin"></i></a>
                    <a title='Compte Whatsapp' href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

        </div>
        <div class="bg-dark text-dark">
            <nav class="navbar navbar-expand-lg navbar-dark bg-light">
                <div class="container">
                    <a class="navbar-brand text-color fw-bold"
                        href="index.php?controller=index&action=index">E-Justice</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse " id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item mx-2">
                                <a class="nav-link text-dark hover:text-primary"
                                    href="index.php?controller=index&action=index">Accueil</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link text-dark hover:text-primary"
                                    href="index.php?controller=index&action=index">Demande</a>
                            </li>
                             <li class="nav-item mx-2">
                                <a class="nav-link text-dark hover:text-primary"
                                    href="index.php?controller=index&action=index">Suivie</a>
                            </li>
                             <li class="nav-item mx-2">
                                <a class="nav-link text-dark hover:text-primary"
                                    href="index.php?controller=index&action=index">Payement</a>
                            </li>
                              <li class="nav-item mx-2">
                                <a class="nav-link text-dark hover:text-primary"
                                    href="index.php?controller=index&action=index">Contact</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link text-dark hover:text-danger"
                                    href="index.php?controller=demande&action=logout">Déconnexion</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <script src="../assets/js/main.js"></script>
</body>

</html>
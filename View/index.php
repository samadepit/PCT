<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>

    <section class="hero-section">
        <div class="container ">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 class="text-center  fw-bold display-4 mb-4">Bienvenue sur le site officiel de notre mairie</h1>
                <p class="lead mb-5"> Au service des citoyens et du développement de notre territoire</p>
                <div class="d-flex gap-3 mb-4">
                    <a href="index.php?controller=demande&action=index" class="btn btn-primary">Faire une demande</a>
                    <a href="index.php?controller=demandeur&action=index" class="btn btn-secondary">Suivre une demande</a>
                       
                </div>
            </div>
        </div>
    </section>

    <section class="quick-links">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h4>État Civil</h4>
                        <p>Actes de naissance, mariage, décès</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4>Agenda</h4>
                        <p>Événements et manifestations</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Équipements</h4>
                        <p>Services publics et infrastructures</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h4>Signalements</h4>
                        <p>Informer la mairie d'un problème</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="news-section">
        <div class="container">
            <h2 class="section-title">Actualités</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100 ">
                        <img src="/api/placeholder/400/200" class="card-img-top" alt="Actualité 1">
                        <div class="card-body">
                            <h5 class="card-title">Rénovation du centre-ville</h5>
                            <p class="card-text">Les travaux de rénovation du centre-ville débuteront le 15 juin
                                prochain. Une réunion d'information aura lieu...</p>
                            <a href="#" class="btn btn-primary">Lire la suite</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>Publié le 12 mai 2025</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 ">
                        <img src="/api/placeholder/400/200" class="card-img-top" alt="Actualité 2">
                        <div class="card-body">
                            <h5 class="card-title">Fête communale</h5>
                            <p class="card-text">Notre traditionnelle fête communale se tiendra du 18 au 20 juillet. Au
                                programme : concerts, animations...</p>
                            <a href="#" class="btn btn-primary">Lire la suite</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>Publié le 5 mai 2025</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 ">
                        <img src="/api/placeholder/400/200" class="card-img-top" alt="Actualité 3">
                        <div class="card-body">
                            <h5 class="card-title">Nouveaux ateliers culturels</h5>
                            <p class="card-text">La médiathèque municipale propose de nouveaux ateliers culturels à
                                partir du mois de juin...</p>
                            <a href="#" class="btn btn-primary">Lire la suite</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>Publié le 1 mai 2025</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mt-4">
                <a href="#" class="btn btn-outline-primary btn-lg">Toutes les actualités</a>
            </div>
        </div>
    </section>
      <section class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="section-title text-center">Restez informés</h2>
                    <p class="lead mb-4">Inscrivez-vous à notre newsletter pour recevoir les dernières actualités de la commune</p>
                    <form class="row g-3 justify-content-center">
                        <div class="col-md-8">
                            <input type="email" class="form-control form-control-lg" placeholder="Votre adresse email">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


</body>

</html>
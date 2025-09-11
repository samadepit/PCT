<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__.'/../Controller/certificatedemandController.php';
require_once __DIR__.'/../service/date_convert.php';
$id = $_GET['id'] ?? null;
if (empty($id)) {
    $_SESSION['erreur'] = 'Accès invalide. Vous avez été redirigé vers la page de connexion.';
    header('Location: login.php');
    exit;
}
$actedemandeController = new ActeDemandeController();

$demandes = $actedemandeController->getAllPending();

$stats = $actedemandeController->getStatistics();

?>
<?php if (isset($_SESSION['alert'])) { ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    <?php if ($_SESSION['alert'] === 'valide') { ?>
    Swal.fire({
        icon: 'success',
        title: 'Demande validée',
        text: 'La demande a été validée avec succès !',
        confirmButtonColor: '#10b981'
    });
    <?php } elseif ($_SESSION['alert'] === 'rejete') { ?>
    Swal.fire({
        icon: 'error',
        title: 'Demande rejetée',
        text: 'La demande a été rejetée avec succès !',
        confirmButtonColor: '#ef4444'
    });
    <?php } ?>
});
</script>
<?php unset($_SESSION['alert']);
} ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demandes en attente</title>
     <link rel="stylesheet" href="../assets/css/styleOfficiers.css">
     
</head>

<body>

    <?php
       require_once './partials/header.php';
    ?>

     <div class="container">

        <div class="top-header fade-in">
            <h1>Tableau de Bord - de Gestion d'Actes des agents</h1>
            <a href="./login.php" class="logout-btn">Déconnexion</a>
        </div>

        <!-- KPI -->
        <div class="kpi-container">
            <div class="kpi">
                <h3>Actes</h3>
                <p><?php echo $stats['total_certificate']; ?></p>
            </div>
            <div class="kpi">
                <h3>Naissances</h3>
                <p><?php echo $stats['birth']; ?></p>
            </div>
            <div class="kpi">
                <h3>Décès</h3>
                <p><?php echo $stats['death']; ?></p>
            </div>
            <div class="kpi">
                <h3>Mariages</h3>
                <p><?php echo $stats['marriage']; ?></p>
            </div>
            <div class="kpi">
                <h3>En attente</h3>
                <p><?php echo $stats['pending']; ?></p>
            </div>
            <div class="kpi">
                <h3>Validés</h3>
                <p><?php echo $stats['validated']; ?></p>
            </div>
            <div class="kpi">
                <h3>Rejetés</h3>
                <p><?php echo $stats['rejeted']; ?></p>
            </div>
        </div>

        <div class="card-content slide-up">
            <div class="table-header">
                <h2>Demandes en attente</h2>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Demandeur</th>
                            <th>Type d'acte</th>
                            <th>Personne concernée</th>
                            <th>Lien</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($demandes as $demande) { ?>
                        <tr>
                            <td><?php echo $dateConvertie = convertirDateEnFrancais(htmlspecialchars($demande['demande_date_creation'])); ?></td>
                            <td><?php echo htmlspecialchars($demande['nom_demandeur']); ?> <?php echo htmlspecialchars($demande['prenom_demandeur']); ?></td>
                            <td><?php echo htmlspecialchars($demande['type_acte']); ?></td>
                            <td>
                                <?php if ($demande['type_acte'] === 'naissance') { ?>
                                <?php echo htmlspecialchars($demande['nom_beneficiaire']); ?> <?php echo htmlspecialchars($demande['prenom_beneficiaire']); ?>
                                <?php } elseif ($demande['type_acte'] === 'mariage') { ?>
                                <?php echo htmlspecialchars($demande['nom_epoux']); ?> <?php echo htmlspecialchars($demande['prenom_epoux']); ?> & <?php echo htmlspecialchars($demande['nom_epouse']); ?> <?php echo htmlspecialchars($demande['prenom_epouse']); ?>
                                <?php } elseif ($demande['type_acte'] === 'deces') { ?>
                                <?php echo htmlspecialchars($demande['nom_defunt']); ?> <?php echo htmlspecialchars($demande['prenom_defunt']); ?>
                                <?php } else { ?>
                                -
                                <?php } ?>
                            </td>
                            <td><?php echo htmlspecialchars($demande['relation_avec_beneficiaire']); ?></td>
                            <td>
                                <a href="details_demand.php?code_demande=<?php echo urlencode($demande['code_demande']); ?>&id=<?php echo urlencode($id); ?>" class="btn btn-dark text-white">Voir</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        // Add smooth scrolling and enhanced interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 4px 15px rgba(249, 115, 22, 0.2)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'none';
                });
            });

            // Add click animation to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        left: ${x}px;
                        top: ${y}px;
                        width: ${size}px;
                        height: ${size}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;

                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
        </script>

    </div>
        <?php
      require_once './partials/footer.php';
       ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="stylesheet" href="./assets/css/etape.css">
</head>
<body>
   <div class="container  stepper-container mt-4">
    <!-- Stepper -->
    <ul class="list-group list-group-horizontal mb-4 justify-content-center">
        <li class="list-group-item active bg-primary text-white">Étape 1 : Choix des Actes</li>
        <li class="list-group-item">Étape 2 : Demandeur</li>
        <li class="list-group-item">Étape 3 : Confirmation</li>
    </ul>

    <h1 class="text-center mb-4">Étape 1 : Choix des Actes et Localité</h1>
    <form method="POST" action="index.php?controller=demande&action=create_step2" class="needs-validation" novalidate>
        <div class="row g-3">
            <div class="col-12">
                <label for="localiter" class="form-label">Localité</label>
                <input type="text" class="form-control" id="localiter" name="localiter" required>
                <div class="invalid-feedback">Veuillez entrer une localité.</div>
            </div>
            <div class="col-12">
                <label class="form-label">Choisissez les actes à demander :</label>
                <div class="form-check">
                    <input class="form-check-input acte-checkbox" type="checkbox" name="actes[]" value="naissance" id="acteNaissance">
                    <label class="form-check-label" for="acteNaissance">Acte de naissance</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input acte-checkbox" type="checkbox" name="actes[]" value="mariage" id="acteMariage">
                    <label class="form-check-label" for="acteMariage">Acte de mariage</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input acte-checkbox" type="checkbox" name="actes[]" value="deces" id="acteDeces">
                    <label class="form-check-label" for="acteDeces">Acte de décès</label>
                </div>
                <div class="invalid-feedback" id="actes-error" style="display:none;">Veuillez sélectionner au moins un acte.</div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Suivant</button>
                <a href="index.php?controller=demande&action=demander" class="btn btn-secondary ms-2">Annuler</a>
            </div>
        </div>
    </form>

    <script>
        (function () {
            'use strict';
            var form = document.querySelector('.needs-validation');
            var checkboxes = document.querySelectorAll('.acte-checkbox');
            var actesError = document.getElementById('actes-error');

            form.addEventListener('submit', function (event) {
                var checked = document.querySelectorAll('.acte-checkbox:checked').length;
                if (checked === 0) {
                    actesError.style.display = 'block';
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    actesError.style.display = 'none';
                }
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var checked = document.querySelectorAll('.acte-checkbox:checked').length;
                    actesError.style.display = checked === 0 ? 'block' : 'none';
                });
            });
        })();
    </script>
</div>

</body>
</html>
<?php

require_once __DIR__ . '/../Model/demand.php';
require_once __DIR__ . '/../Model/birth.php';
require_once __DIR__ . '/../Model/deces.php';
require_once __DIR__ . '/../Model/marriage.php';

require_once __DIR__ . '/birthController.php';
require_once __DIR__ . '/marriageController.php';
require_once __DIR__ . '/deathController.php';

class ActeDuplicataController {
    public function getActeByRegistreAndDate($type, $number_registre, $date) {
        switch ($type) {
            case 'naissance':
                $ctrl = new NaissanceController();
                return $ctrl->getCertificateBirthDuplicate($number_registre,$date);
            case 'mariage':
                $ctrl = new MarriageController();
                return $ctrl->getCertificateMarriageDuplicate($number_registre, $date);
            case 'deces':
                $ctrl = new DecesController();
                return $ctrl->getCertificateDeathDuplicate($number_registre, $date);
            default:
                throw new Exception("Type d'acte inconnu.");
        }
    }
}

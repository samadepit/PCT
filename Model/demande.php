<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Demande
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function creer($type_acte, $localiter, $statut = "en_attente") {
        $code_demande = uniqid('DEM-');

        $stmt = $this->con->prepare("
            INSERT INTO demande (code_demande, type_acte, statut, localiter, date_creation)
            VALUES (:code_demande, :type_acte, :statut, :localiter, NOW())
        ");

        $stmt->execute([
            ':code_demande' => $code_demande,
            ':type_acte' => $type_acte,
            ':statut' => $statut,
            ':localiter' => $localiter
        ]);

        return $code_demande;
    }
}

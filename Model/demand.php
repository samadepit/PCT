<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Demande
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function insert_data_demand($localization) {
        $code_demand = uniqid('DEM-');

        $stmt = $this->con->prepare("
            INSERT INTO demande (code_demande, statut, localiter, date_creation)
            VALUES (:code_demande, 'en_attente', :localiter, NOW())
        ");

        $stmt->execute([
            ':code_demande' => $code_demand,
            ':localiter' => $localization
        ]);
        return $code_demand;
    }

    public function updateStatut($code_demand, $statut, $motif = null) {
        $query = "
        UPDATE demande SET statut = ?, motif_rejet = ? WHERE code_demande = ?";
        $stmt = $this->con->prepare($query);
        return $stmt->execute([$statut, $motif, $code_demand]);
    }
}

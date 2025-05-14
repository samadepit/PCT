<?php

require_once __DIR__ . '/../config/dbconnect.php';

class Acte_Demande
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }
    public function traitement_demande($code_demande, $type_acte, $id_acte) {
        $stmt = $this->con->prepare("
            INSERT INTO actes_demande (code_demande, type_acte, id_acte, est_signer, date_creation)
            VALUES (:code_demande, :type_acte, :id_acte, false, NOW())
        ");
        $stmt->execute([
            ':code_demande' => $code_demande,
            ':type_acte'  => $type_acte,
            ':id_acte'    => $id_acte,
        ]);
        return true;
    }

    public function marquer_comme_signe($id_acte_demande) {
        $stmt = $this->con->prepare("
        UPDATE actes_demande SET signer = 1 WHERE id = :id
        ");
        $stmt->execute([':id' => $id_acte_demande]);
    }

    public function recuperer_par_demande($code_demande) {
        $stmt = $this->con->prepare("
        SELECT * FROM actes_demande WHERE code_demande = :code_demande
        ");
        $stmt->execute([':code_demande' => $code_demande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

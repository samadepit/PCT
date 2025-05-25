<?php

require_once __DIR__ . '/../config/dbconnect.php';

class Payment
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function insert_paiement($code_demande, $numero, $code_paiement)
    {
        $stmt = $this->con->prepare("
            INSERT INTO paiement (
                code_demande, numero, code_paiement, date_creation
            ) VALUES (
                :code_demande, :numero, :code_paiement, NOW()
            )
        ");
        
        return $stmt->execute([
            'code_demande' => $code_demande,
            'numero' => $numero,
            'code_paiement' => $code_paiement
        ]);
    }


    public function verify_paiement($numero, $code_paiement)
    {
        $stmt = $this->con->prepare("
            SELECT * FROM paiement 
            WHERE numero = :numero AND code_paiement = :code_paiement
        ");
        $stmt->execute([
            'numero' => $numero,
            'code_paiement' => $code_paiement
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public function get_paiement_par_demand($code_demande)
    {
        $stmt = $this->con->prepare("
            SELECT * FROM paiement 
            WHERE code_demande = :code_demande
        ");
        $stmt->execute(['code_demande' => $code_demande]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Deces
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function insert_data_death_certificate(array $data,$birth_id) {
        $stmt = $this->con->prepare("
            INSERT INTO deces (
                id_naissance,
                date_deces,
                lieu_deces,
                cause,
                genre,
                profession,
                date_creation
            ) VALUES (
                :id_naissance,
                :date_deces,
                :lieu_deces,
                :cause,
                :genre,
                :profession,
                NOW()
            )
        ");
        $params = [
            'id_naissance' => $birth_id ,
            'date_deces' => $data['date_deces'] ,
            'lieu_deces' => $data['lieu_deces'] ,
            'cause' => $data['cause'] ?? null,
            'genre' => $data['genre'],
            'profession' => $data['profession']
        ];

        try {
            $stmt->execute($params);
            return  $this->con->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur insertion dans deces : " . $e->getMessage());
            return false;
        }
    }
    public function getCertificateDeathDuplicate($number_registre,$death_date){
        $stmt = $this->con->prepare("
        SELECT 
            n.nom_beneficiaire,
            n.prenom_beneficiaire,
            n.date_naissance,
            d.date_deces,
            d.lieu_deces,
            d.numero_registre,
            ad.code_demande
        FROM naissance n
        INNER JOIN deces d ON n.id = d.id_naissance
        INNER JOIN actes_demande ad ON d.id = ad.id_acte
        WHERE 
            ad.est_signer = 1 AND ad.payer =1
            AND d.numero_registre = :numero_registre  
            AND d.date_deces = :evenement_date;
        ");
        $params=[
            'numero_registre'=>$number_registre,
            'evenement_date'=>$death_date
        ];
        try {
            $stmt->execute($params);
            return  $stmt->fetch(PDO::FETCH_ASSOC);;
        } catch (Exception $e) {
            error_log("Erreur de duplicata dans deces : " . $e->getMessage());
            return false;
        }
    }
}

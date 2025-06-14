<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Deces
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function insert_data_death_certificate(array $data) {
        $stmt = $this->con->prepare("
            INSERT INTO deces (
                nom_defunt,
                prenom_defunt,
                date_naissance,
                lieu_naissance,
                date_deces,
                lieu_deces,
                cause,
                genre,
                profession,
                date_creation,
                certificat_medical_deces,
                piece_identite_defunt
            ) VALUES (
                :nom_defunt,
                :prenom_defunt,
                :date_naissance,
                :lieu_naissance,
                :date_deces,
                :lieu_deces,
                :cause,
                :genre,
                :profession,
                NOW(),
                :certificat_medical_deces,
                :piece_identite_defunt
            )
        ");
    
        $params = [
            'nom_defunt'     => $data['nom_defunt'],
            'prenom_defunt'  => $data['prenom_defunt'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'date_deces'     => $data['date_deces'],
            'lieu_deces'     => $data['lieu_deces'],
            'cause'          => $data['cause'] ?? null,
            // 'nom_pere'       => $data['nom_pere'],
            // 'prenom_pere'    => $data['prenom_pere'],
            'genre'          => $data['genre'],
            'profession'     => $data['profession'],
            'certificat_medical_deces'=> $data['certificat_medical_deces'] ?? null,
            'piece_identite_defunt' => $data['piece_identite_defunt']  ?? null
        ];
    
        try {
            $stmt->execute($params);
            return $this->con->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur insertion dans deces : " . $e->getMessage());
            return false;
        }
    }
    
    public function getCertificateDeathDuplicate($number_registre,$death_date){
        $stmt = $this->con->prepare("
        SELECT 
        d.nom_defunt,
        d.prenom_defunt,
        d.date_naissance,
        d.date_deces,
        d.lieu_deces,
        ad.code_demande
    FROM deces d
    INNER JOIN actes_demande ad ON ad.id_acte = d.id AND ad.type_acte = 'deces'
    WHERE 
        ad.est_signer = 1 
        AND ad.payer = 1
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

    function get_deathcertificate_byId(array $data) {
        $stmt = $this->con->prepare("
            SELECT id FROM deces
            WHERE 
                nom_defunt = :nom
                AND prenom_defunt = :prenom
                AND date_naissance = :date_naissance
                AND lieu_naissance = :lieu_naissance
                AND date_deces = :date_deces
                AND lieu_deces = :lieu_deces
                AND genre = :genre
            LIMIT 1
        ");
    
        $params = [
            ':nom' => $data['nom_defunt'],
            ':prenom' => $data['prenom_defunt'],
            ':date_naissance' => $data['date_naissance'],
            ':lieu_naissance' => $data['lieu_naissance'],
            ':date_deces' => $data['date_deces'],
            ':lieu_deces' => $data['lieu_deces'],
            ':genre' => $data['genre']
        ];
    
        try {
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['id'] : null;
        } catch (Exception $e) {
            error_log("Erreur récupération id_birth_existing : " . $e->getMessage());
            return false;
        }
    }
    
}

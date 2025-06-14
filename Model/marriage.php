<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Marriage
{
    private  $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function insert_data_marriage_certificate(array $data) {
        $stmt = $this->con->prepare("
            INSERT INTO mariage (
                nom_epoux,
                prenom_epoux,
                date_naissance_epoux,
                lieu_naissance_epoux,
                nationalite_epoux,
                situation_matrimoniale_epoux,
                temoin_epoux,
                profession_epoux,
    
                nom_epouse,
                prenom_epouse,
                date_naissance_epouse,
                lieu_naissance_epouse,
                situation_matrimoniale_epouse,
                temoin_epouse,
                nationalite_epouse,
                profession_epouse,
    
                date_mariage,
                lieu_mariage,
                date_creation,
                piece_identite_epouse,
                certificat_residence_epouse,
                piece_identite_epoux,
                certificat_residence_epoux
            )
            VALUES (
                :nom_epoux,
                :prenom_epoux,
                :date_naissance_epoux,
                :lieu_naissance_epoux,
                :nationalite_epoux,
                :situation_matrimoniale_epoux,
                :temoin_epoux,
                :profession_epoux,
    
                :nom_epouse,
                :prenom_epouse,
                :date_naissance_epouse,
                :lieu_naissance_epouse,
                :situation_matrimoniale_epouse,
                :temoin_epouse,
                :nationalite_epouse,
                :profession_epouse,
    
                :date_mariage,
                :lieu_mariage,
                NOW(),
                :piece_identite_epouse,
                :certificat_residence_epouse,
                :piece_identite_epoux,
                :certificat_residence_epoux
            )
        ");
    
        $params = [
            ':nom_epoux' => $data['nom_epoux'],
            ':prenom_epoux' => $data['prenom_epoux'],
            ':date_naissance_epoux' => $data['date_naissance_epoux'],
            ':lieu_naissance_epoux' => $data['lieu_naissance_epoux'],
            ':nationalite_epoux' => $data['nationalite_epoux'],
            ':situation_matrimoniale_epoux' => $data['situation_matrimoniale_epoux'],
            ':temoin_epoux' => $data['temoin_epoux'],
            ':profession_epoux' => $data['profession_epoux'],
    
            ':nom_epouse' => $data['nom_epouse'],
            ':prenom_epouse' => $data['prenom_epouse'],
            ':date_naissance_epouse' => $data['date_naissance_epouse'],
            ':lieu_naissance_epouse' => $data['lieu_naissance_epouse'],
            ':situation_matrimoniale_epouse' => $data['situation_matrimoniale_epouse'],
            ':temoin_epouse' => $data['temoin_epouse'],
            ':nationalite_epouse' => $data['nationalite_epouse'],
            ':profession_epouse' => $data['profession_epouse'],
    
            ':date_mariage' => $data['date_mariage'],
            ':lieu_mariage' => $data['lieu_mariage'],
            ':piece_identite_epouse'=> $data['piece_identite_epouse'],
            ':certificat_residence_epouse'=> $data['certificat_residence_epouse'],
            ':piece_identite_epoux'=> $data['piece_identite_epoux'],
            ':certificat_residence_epoux'=> $data['certificat_residence_epoux']
        ];
    
        try {
            $stmt->execute($params);
            return $this->con->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur insertion dans mariage : " . $e->getMessage());
            return false;
        }
    }
    

    public function getCertificateMarriageDuplicate($number_registre,$marriage_date){
        $stmt = $this->con->prepare("
        SELECT 
        m.date_mariage, 
        m.lieu_mariage, 
        m.date_creation AS mariage_date_creation,
        m.numero_registre,
        m.nom_epoux AS nom_mari, 
        m.prenom_epoux AS prenom_mari,
        m.nom_epouse AS nom_femme, 
        m.prenom_epouse AS prenom_femme,
        ad.code_demande
    FROM mariage m
    INNER JOIN actes_demande ad ON m.id = ad.id_acte
    WHERE 
        ad.est_signer = 1 
        AND ad.payer = 1
        AND m.numero_registre = :numero_registre 
        AND m.date_mariage = :evenement_date;

        ");
        $params=[
            'numero_registre'=>$number_registre,
            'evenement_date'=>$marriage_date
        ];
        try {
            $stmt->execute($params);
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur duplicata dans mariage : " . $e->getMessage());
            return false;
        }
    }

    function get_marriagecertificate_byId(array $data) {
        $stmt = $this->con->prepare("
            SELECT id FROM mariage
            WHERE 
                nom_epoux = :nom_epoux
                AND prenom_epoux = :prenom_epoux
                AND date_naissance_epoux = :date_naissance_epoux
                AND lieu_naissance_epoux = :lieu_naissance_epoux
                AND nom_epouse = :nom_epouse
                AND prenom_epouse = :prenom_epouse
                AND date_naissance_epouse = :date_naissance_epouse
                AND lieu_naissance_epouse = :lieu_naissance_epouse
                AND date_mariage = :date_mariage
                AND lieu_mariage = :lieu_mariage
            LIMIT 1
        ");
    
        $params = [
            ':nom_epoux' => $data['nom_epoux'],
            ':prenom_epoux' => $data['prenom_epoux'],
            ':date_naissance_epoux' => $data['date_naissance_epoux'],
            ':lieu_naissance_epoux' => $data['lieu_naissance_epoux'],
            ':nom_epouse' => $data['nom_epouse'],
            ':prenom_epouse' => $data['prenom_epouse'],
            ':date_naissance_epouse' => $data['date_naissance_epouse'],
            ':lieu_naissance_epouse' => $data['lieu_naissance_epouse'],
            ':date_mariage' => $data['date_mariage'],
            ':lieu_mariage' => $data['lieu_mariage']
        ];
    
        try {
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null;
        } catch (Exception $e) {
            error_log("Erreur récupération id_mariage : " . $e->getMessage());
            return false;
        }
    }
    
}

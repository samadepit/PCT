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
                id_naissance_mari,
                id_naissance_femme,
                date_mariage,
                lieu_mariage,
                nombre_enfant,
                date_creation
            )
            VALUES (
                :id_naissance_mari,
                :id_naissance_femme,
                :date_mariage,
                :lieu_mariage,
                :nombre_enfant,
                NOW()
            )
        ");
        $params=[
            ':id_naissance_mari' => $data['husband_birth_id'] ,
            ':id_naissance_femme' => $data['wife_birth_id'] ,
            ':date_mariage' => $data['marriage_date'] ,
            ':lieu_mariage' => $data['marriage_place'] ,
            ':nombre_enfant' => $data['number_children']
        ];
        try {
            $stmt->execute($params);
            return  $this->con->lastInsertId();
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
            homme.nom_beneficiaire AS nom_mari, 
            homme.prenom_beneficiaire AS prenom_mari,
            femme.nom_beneficiaire AS nom_femme, 
            femme.prenom_beneficiaire AS prenom_femme
        FROM mariage m
        INNER JOIN naissance homme ON m.id_naissance_mari = homme.id
        INNER JOIN naissance femme ON m.id_naissance_femme = femme.id
        INNER JOIN actes_demande ad ON m.id = ad.id_acte
        WHERE ad.est_signer=FALSE and m.numero_registre = :numero_registre AND m.date_mariage = :evenement_date
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
}

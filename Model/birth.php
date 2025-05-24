<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Naissance
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert_data_birth_certificate(array $data) {
        $required = ['nom', 'prenom', 'date_naissance', 'lieu_naissance'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("le champ $field est obligatoire");
            }
        }

        $optionalFields = ['date_mariage', 'lieu_mariage', 'statut_mariage', 'date_deces', 
        'lieu_deces', 'genre', 'heure_naissance','nom_pere','prenom_pere','nom_mere','prenom_mere'];
        foreach ($optionalFields as $field) {
            $data[$field] = empty($data[$field]) ? null : $data[$field];
        }

        if (!DateTime::createFromFormat('Y-m-d', $data['date_naissance'])) {
            throw new InvalidArgumentException("format de date invalide (YYYY-MM-DD attendu)");
        }

        $stmt = $this->con->prepare("
            INSERT INTO naissance (
                nom_beneficiaire, prenom_beneficiaire, date_naissance,
                lieu_naissance,heure_naissance,genre,
                nom_pere, prenom_pere, profession_pere,
                nom_mere, prenom_mere, profession_mere,
                date_mariage, lieu_mariage, statut_mariage,
                date_deces, lieu_deces, date_creation
            ) VALUES (
                :nom_beneficiaire, :prenom_beneficiaire, :date_naissance,
                :lieu_naissance,:heure_naissance,:genre,
                :nom_pere, :prenom_pere, :profession_pere,
                :nom_mere, :prenom_mere, :profession_mere,
                :date_mariage, :lieu_mariage, :statut_mariage,
                :date_deces, :lieu_deces, NOW()
            )
        ");
        $params = [
            'nom_beneficiaire' => $data['nom'] ,
            'prenom_beneficiaire' => $data['prenom'] ,
            'date_naissance' => $data['date_naissance'] ,
            'heure_naissance' => $data['heure_naissance'] ,
            'genre' => $data['genre'] ,
            'lieu_naissance' => $data['lieu_naissance'] ,
            'nom_pere' => $data['nom_pere'] ,
            'prenom_pere' => $data['prenom_pere'] ,
            'profession_pere' => $data['profession_pere'] ,
            'nom_mere' => $data['nom_mere'] ,
            'prenom_mere' => $data['prenom_mere'] ,
            'profession_mere' => $data['profession_mere'] ,
            'date_mariage' => $data['date_mariage'] ?? null,
            'lieu_mariage' => $data['lieu_mariage'] ?? null,
            'statut_mariage' => $data['statut_mariage'] ?? null,
            'date_deces' => $data['date_deces'] ?? null,
            'lieu_deces' => $data['lieu_deces'] ?? null
        ];

        try {
            $stmt->execute($params);
            return $this->con->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur insertion naissance : " . $e->getMessage());
            return false;
        }
    }

    function get_birthcertificate_byId(array $data) {
        $stmt = $this->con->prepare("
        SELECT id FROM naissance
                WHERE nom_beneficiaire = :nom
                AND prenom_beneficiaire = :prenom
                AND date_naissance = :date_naissance
                AND lieu_naissance = :lieu_naissance
                AND genre =:genre
                LIMIT 1");
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':date_naissance' => $data['date_naissance'],
            ':lieu_naissance' => $data['lieu_naissance'],
            ':genre' => $data['genre']
                ];
            
        try {
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null;
        } catch (Exception $e) {
            error_log("Erreur récupération id_naissance : " . $e->getMessage());
            return false;
        }

    }

    public function updatemarriage_inbirthcertificate(array $data){

        $stmt=$this->con->prepare("
         UPDATE naissance 
         SET date_mariage = :date_mariage, lieu_mariage = :lieu_mariage, statut_mariage= :statut_mariage
         WHERE id = :id_naissance_mari or id = :id_naissance_femme;
                ");
        $params=[
            'date_mariage' => $data['marriage_date'],
            'lieu_mariage' => $data['marriage_place'],
            'statut_mariage' => $data['statut_marriage'],
            ':id_naissance_mari' => $data['husband_birth_id'] ,
            ':id_naissance_femme' => $data['wife_birth_id'] ,
        ];

        try {
            $stmt->execute($params);
            return  'succes';
        } catch (Exception $e) {
            error_log("Erreur de mise à jour des données de mariage dans l'acte de naissance: " . $e->getMessage());
            return false;
        }
    }

    public function updatedeath_inbirthcertificate(array $data,$id_acte){

        $stmt = $this->con->prepare("
        UPDATE naissance 
        SET date_deces = :date_deces, 
            lieu_deces = :lieu_deces
        WHERE id = :id_acte
    ");
        $params=[
            'date_deces' => $data['date_deces'],
            'lieu_deces' => $data['lieu_deces'],
            'id_acte' => $id_acte,
        ];
        try {
            $stmt->execute($params);
            return  'succes';
        } catch (Exception $e) {
            error_log("Erreur de mise à jour des données de deces dans l'acte de naissance: " . $e->getMessage());
            return false;
        }
        
    }

    public function getCertificateBirthDuplicate($number_registre,$birth_date){
        $stmt = $this->con->prepare("
        SELECT 
            n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance,
            n.lieu_naissance, n.heure_naissance, n.genre,
            n.nom_pere, n.prenom_pere, n.profession_pere,
            n.nom_mere, n.prenom_mere, n.profession_mere,
            n.date_mariage, n.lieu_mariage, n.statut_mariage,
            n.date_deces, n.lieu_deces, n.date_creation,
            n.numero_registre
        FROM naissance n
        INNER JOIN actes_demande ad ON n.id = ad.id_acte
        WHERE 
            ad.est_signer = FALSE  
            AND n.numero_registre = :numero_registre  
            AND n.date_naissance = :evenement_date;
        ");
        $params=[
            'numero_registre'=>$number_registre,
            'evenement_date'=>$birth_date
        ];
        try {
            $stmt->execute($params);
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur dans le duplicata naissance : " . $e->getMessage());
            return false;
        }
    }

}

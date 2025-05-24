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

    public function demande_acte_naissance(array $data) {
        $required = ['nom_beneficiaire', 'prenom_beneficiaire', 'date_naissance', 'lieu_naissance'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("le champ $field est obligatoire");
            }
        }

        $optionalFields = ['date_mariage', 'lieu_mariage', 'statut_mariage', 'date_deces', 'lieu_deces'];
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
            'nom_beneficiaire' => $data['nom_beneficiaire'] ,
            'prenom_beneficiaire' => $data['prenom_beneficiaire'] ,
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

    function recuperation_idNaissance(array $data) {
        $stmt = $pdo->prepare("
        SELECT id_naissance FROM acte_naissance
                WHERE nom_beneficiaire = :nom
                AND prenom_beneficiaire = :prenom
                AND date_naissance = :date_naissance
                AND lieu_naissance = :lieu_naissance
                AND genre =:genre
                LIMIT 1");
        $params = [
            ':nom' => $data['nom_beneficiaire'],
            ':prenom' => $data['prenom_beneficiaire'],
            ':date_naissance' => $data['date_naissance'],
            ':lieu_naissance' => $data['lieu_naissance'],
            ':genre' => $data['genre']
                ];
            
        try {
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id_naissance'] : null;
        } catch (Exception $e) {
            error_log("Erreur récupération id_naissance : " . $e->getMessage());
            return false;
        }

    }
}

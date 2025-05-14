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

    public function demande_acte_naissance($code_demande,array $data) {
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
                code_demande, nom_beneficiaire, prenom_beneficiaire, date_naissance, lieu_naissance,
                nom_pere, prenom_pere, profession_pere,
                nom_mere, prenom_mere, profession_mere,
                date_mariage, lieu_mariage, statut_mariage,
                date_deces, lieu_deces, date_creation
            ) VALUES (
                :code_demande, :nom_beneficiaire, :prenom_beneficiaire, :date_naissance, :lieu_naissance,
                :nom_pere, :prenom_pere, :profession_pere,
                :nom_mere, :prenom_mere, :profession_mere,
                :date_mariage, :lieu_mariage, :statut_mariage,
                :date_deces, :lieu_deces, NOW()
            )
        ");
        $params = [
            'code_demande' => $code_demande,
            'nom_beneficiaire' => $data['nom_beneficiaire'] ,
            'prenom_beneficiaire' => $data['prenom_beneficiaire'] ,
            'date_naissance' => $data['date_naissance'] ,
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
}


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
        $required = ['code_demande', 'nom_beneficiaire', 'prenom_beneficiaire', 'date_naissance', 'lieu_naissance'];
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

        return $stmt->execute($data);
    }
}
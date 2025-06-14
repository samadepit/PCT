<?php

require_once __DIR__ . '/../Model/marriage.php';
require_once __DIR__ . '/../Model/demand.php';

class MarriageController
{
    private $marriageModel;

    public function __construct()
    {
        $this->marriageModel = new Marriage();
    }

    public function create_marriage_certificate(array $data)
    {
        try {
            $marriage_id = $this->marriageModel->insert_data_marriage_certificate($data);
            return $marriage_id;
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            return false;
        }
    } 

    public function getCertificateMarriageDuplicate($number_registre,$birth_date){
        try {
            $birth_id = $this->marriageModel->getCertificateMarriageDuplicate($number_registre,$birth_date);
            if (!$birth_id) {
                throw new Exception("Erreur du duplicata mariage");
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            return false;
        }
    }
    
    public function get_existing_marriage_id(array $data) {
        try {
            $birth_id = $this->marriageModel->get_marriagecertificate_byId($data);
            error_log("Acte de mariage déjà existant pour ce couple.");
            return $birth_id ?: null;
        } catch (Exception $e) {
            error_log("Erreur get_existing_death_id : " . $e->getMessage());
            return null;
        }
    }

}

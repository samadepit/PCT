<?php

require_once __DIR__ . '/../Model/birth.php';
require_once __DIR__ . '/../Model/demand.php';

class NaissanceController
{

    private $naissanceModel;

    public function __construct()
    {
        $this->naissanceModel = new Naissance();
    }

    public function create_birth_certificate(array $data)
    {
        try {
            $birth_id = $this->naissanceModel->insert_data_birth_certificate($data);
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur by sam:" . $e->getMessage());
            return false;
        }

    }

    public function get_existing_birth_id(array $data) {
        try {
            $birth_id = $this->naissanceModel->get_birthcertificate_byId($data);
            if (!$birth_id) {
                return null; 
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur get_existing_birth_id : " . $e->getMessage());
            return []; 
        }
    }

    public function addMarriageInbirthcertificate(array $data){
        try {
            $birth_id = $this->naissanceModel->updatemarriage_inbirthcertificate($data);
            if (!$birth_id) {
                throw new Exception("Erreur de mise à jour des informations de naissance dans naissance");
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur  : " . $e->getMessage());
            return false;
        }
    }
    
    public function addDeathInbirthcertificate(array $data,$id_acte){
        try {
            $birth_id = $this->naissanceModel->updatedeath_inbirthcertificate($data,$id_acte);
            if (!$birth_id) {
                throw new Exception("Erreur de mise à jour des informations de deces dans naissance");
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            return false;
        }
    }

    public function getCertificateBirthDuplicate($number_registre,$birth_date){
        try {
            $birth_id = $this->naissanceModel->getCertificateBirthDuplicate($number_registre,$birth_date);
            if (!$birth_id) {
                throw new Exception("Erreur du duplicata naissance");
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            return false;
        }
    }

}
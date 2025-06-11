<?php

require_once __DIR__ . '/../Model/deces.php';
require_once __DIR__ . '/../Model/demand.php';

class DecesController
{
    private $deathModel;

    public function __construct()
    {
        $this->deathModel = new Deces();
    }

    public function create_death_certificate(array $data)
    {
        try {
            $death_id = $this->deathModel->insert_data_death_certificate($data);
            return $death_id;
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            return false;
        }
    } 

    public function getCertificateDeathDuplicate($number_registre,$birth_date){
        try {
            $birth_id = $this->deathModel->getCertificateDeathDuplicate($number_registre,$birth_date);
            if (!$birth_id) {
                throw new Exception("Erreur du duplicata deces");
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            return false;
        }
    }

    public function get_existing_death_id(array $data) {
        try {
            $birth_id = $this->deathModel->get_deathcertificate_byId($data);
            if ($birth_id) {
                throw new Exception(" acte de deces déjà existant trouvé pour le défunt");
            }
            return $birth_id;
        } catch (Exception $e) {
            error_log("Erreur get_existing_death_id : " . $e->getMessage());
            return false;
        }
    }
    
}

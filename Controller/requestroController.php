<?php

require_once __DIR__ . '/../Model/requestor.php';

class DemandeurController
{
    private $requestorModel;

    public function __construct()
    {
        $this->requestorModel = new Demandeur();
    }

    public function create_requestor($code_demand,array $data)
    {
        try {
            return $this->requestorModel->insert_data_requestor($code_demand,$data);
        } catch (Exception $e) {
            error_log("erreur dans la création d'un demandeur : " . $e->getMessage());
            return false;
        }
    }
    public function get_requestor_ById($id)
    {
        try {
            return $this->requestorModel->get_requestor_ById($id);
        } catch (Exception $e) {
            error_log("erreur lors de la récupération du demandeur : " . $e->getMessage());
            return null;
        }
    }
}

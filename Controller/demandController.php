<?php

require_once __DIR__ . '/../Model/demand.php';

class DemandeController
{
    private $demandModel;

    public function __construct()
    {
        $this->demandModel = new Demande();
    }

    public function create_demand($localization)
    {
        $code = $this->demandModel->insert_data_demand($localization);
        return $code;
    }

    public function get_demand()
    {
        return $this->demandModel->getAll();
    }

    public function updateStatut($code_demand, $status, $motif = null) {
        return $this->demandModel->updateStatut($code_demand, $status, $motif);
    }
}

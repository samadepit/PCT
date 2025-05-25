<?php

require_once __DIR__ . '/../Model/demand.php';
require_once __DIR__ . '/../Model/certificate_demand.php';

class ActeDemandeController
{
    private $actedemandeModel;

    public function __construct()
    {
        $this->actedemandeModel = new certificate_demand();
    }

    public function certificate_demand($code_demand,$type_certificate,$certificate_id)
    {
        $code = $this->actedemandeModel->insert_data_certificate_demand($code_demand,$type_certificate,$certificate_id);
        return "succes";
    }

    public function get_certificateby_Demande($code_demand)
    {
        return $this->actedemandeModel->get_Alldemand($code_demand);
    }

    public function getAllPending() {
        return $this->actedemandeModel->getAllPendingActeDemandes();
    }

    public function getCertificateById($id_certificate) {
        return $this->actedemandeModel->getOneCertificateById($id_certificate);
    }
}

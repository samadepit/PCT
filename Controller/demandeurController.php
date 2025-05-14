<?php

require_once __DIR__ . '/../Model/demandeur.php';

class DemandeurController
{
    private $demandeurModel;

    public function __construct()
    {
        $this->demandeurModel = new Demandeur();
    }

    public function creerDemandeur($code_demande,array $data)
    {
        try {
            return $this->demandeurModel->creerDemandeur($code_demande,$data);
        } catch (Exception $e) {
            error_log("erreur dans la création d'un demandeur : " . $e->getMessage());
            return false;
        }
    }
    public function getDemandeurById($id)
    {
        try {
            return $this->demandeurModel->getDemandeurById($id);
        } catch (Exception $e) {
            error_log("erreur lors de la récupération du demandeur : " . $e->getMessage());
            return null;
        }
    }
}

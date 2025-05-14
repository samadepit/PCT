<?php

require_once __DIR__ . '/../Model/demande.php';
require_once __DIR__ . '/../Model/acte_demande.php';

class ActeDemandeController
{
    private $actedemandeModel;

    public function __construct()
    {
        $this->actedemandeModel = new Acte_Demande();
    }

    public function acte_demande($code_demande,$type_acte,$id_acte)
    {
        $code = $this->actedemandeModel->traitement_demande($code_demande,$type_acte,$id_acte);
        return "succes";
    }

    public function lister_demandes()
    {
        return $this->actedemandeModel->getAll();
    }
}

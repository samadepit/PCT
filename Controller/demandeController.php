<?php

require_once __DIR__ . '/../Model/demande.php';

class DemandeController
{
    private $demandeModel;

    public function __construct()
    {
        $this->demandeModel = new Demande();
    }

    public function creer_demande($type_acte,$localiter)
    {
        $code = $this->demandeModel->creer($type_acte, $localiter);
        return $code;
    }

    public function lister_demandes()
    {
        return $this->demandeModel->getAll();
    }
}

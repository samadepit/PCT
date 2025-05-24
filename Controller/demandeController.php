<?php

require_once __DIR__ . '/../Model/demande.php';

class DemandeController
{
    private $demandeModel;

    public function __construct()
    {
        $this->demandeModel = new Demande();
    }

    public function creer_demande($localiter)
    {
        $code = $this->demandeModel->creer($localiter);
        return $code;
    }

    public function lister_demandes()
    {
        return $this->demandeModel->getAll();
    }
}

<?php

require_once __DIR__ . '/../Model/deces.php';
require_once __DIR__ . '/../Model/demande.php';

class DecesController
{
    private $decesModel;

    public function __construct()
    {
        $this->decesModel = new Deces();
    }

    public function creerActeDeces(array $data,$id_naissance)
    {
        try {
            $success = $this->decesModel->acte_deces($data,$id_naissance);
            return $success;
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            return false;
        }
    } 
}

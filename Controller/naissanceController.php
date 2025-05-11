<?php

require_once __DIR__ . '/../Model/naissance.php';
require_once __DIR__ . '/../Model/demande.php';

class NaissanceController
{

    private $naissanceModel;

    public function __construct()
    {
        $this->naissanceModel = new Naissance();
    }

    public function creerActeNaissance(array $data)
    {
        try {
            $success = $this->naissanceModel->demande_acte_naissance($data);
            return $success ? true : false;
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            return false;
        }

    } 

    
}

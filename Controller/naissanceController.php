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

    public function creerActeNaissance($code_demande,array $data)
    {
        try {
            $success = $this->naissanceModel->demande_acte_naissance($code_demande,$data);
            return $success;
        } catch (Exception $e) {
            error_log("Erreur by sam: " . $e->getMessage());
            return false;
        }

    } 

    
}
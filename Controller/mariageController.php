<?php

require_once __DIR__ . '/../Model/mariage.php';
require_once __DIR__ . '/../Model/demande.php';

class MariageController
{
    private $naissanceModel;

    public function __construct()
    {
        $this->naissanceModel = new Naissance();
    }

    public function creerActeMariage(array $data)
    {
        try {
            $success = $this->naissanceModel->acte_mariage($data);
            return $success;
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            return false;
        }
    } 
}

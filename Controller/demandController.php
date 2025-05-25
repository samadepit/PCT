<?php

require_once __DIR__ . '/../Model/demand.php';
require_once __DIR__ . '/birthController.php';
require_once __DIR__ . '/deathController.php';
require_once __DIR__ . '/marriagecontroller.php';

class DemandeController
{
    private $demandeModel;
    private $naissanceController;
    private $decesController;
    private $mariageController;


    public function __construct()
    {
        $this->demandeModel = new Demande();
        $this->naissanceController = new NaissanceController();
        $this->decesController = new DecesController();
        $this->mariageController = new MarriageController();
        
     session_start();
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

    public function index()
    {
        $title = "Page d'accueil des demandes";
        require_once __DIR__ . '/../View/index.php'; // Créez une vue index.php si nécessaire
    }

    public function demander()
    {
        $title = "Section Demande";
        require_once __DIR__ . '/../View/demande.php';
    }

    public function create()
    {
        $title = "Faire une Demande - Étape 1 : Choix des Actes et Localité";
        require_once __DIR__ . '/../View/demande_etape1.php';
    }


    public function create_step2()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['localiter'] = $_POST['localiter'] ?? '';
            $_SESSION['actes'] = $_POST['actes'] ?? [];
            $actes_selectionnes = $_SESSION['actes'];
            $donnees_existantes = $_SESSION['donnees_actes'] ?? [];
            foreach ($donnees_existantes as $type => $data) {
                if (!in_array($type, $actes_selectionnes)) {
                    unset($_SESSION['donnees_actes'][$type]);
                }
            }
        }
        $title = "Faire une Demande - Étape 2 : Informations sur le Demandeur";
        require_once __DIR__ . '/../View/demande_etape2.php';
    }


    public function create_step3()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['demandeur'] = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'relation_avec_beneficiaire' => $_POST['relation_avec_beneficiaire'] ?? '',
                'numero_telephone' => $_POST['numero_telephone'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
        }
        $title = "Faire une Demande - Étape 3 : Confirmation";
        require_once __DIR__ . '/../View/demande_etape3.php';
    }

    public function create_submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['actes_restants'] = $_SESSION['actes'] ?? [];
            $premier_acte = array_shift($_SESSION['actes_restants']);
            switch ($premier_acte) {
                case 'naissance':
                    header('Location: index.php?controller=demande&action=birth_certificate');
                    break;
                case 'mariage':
                    header('Location: index.php?controller=demande&action=marriage');
                    break;
                case 'deces':
                    header('Location: index.php?controller=demande&action=death_certificate');
                    break;
                default:
                    header('Location: index.php?controller=demande&action=index');
                    break;
            }
            exit;
        }
    }

    public function birth_certificate()
    {
        $title = "Demande d'Acte de Naissance";
        require_once __DIR__ . '/../View/demand_birth_certificate.php';
    }

    public function submit_birth_certificate()
    {
        // La logique est maintenant dans demand_birth_certificate.php
        $this->birth_certificate();
    }

    public function death_certificate()
    {
        $title = "Demande d'Acte de Décès";
        require_once __DIR__ . '/../View/demand_death_certificate.php';
    }

    public function marriage()
    {
        $title = "Demande d'Acte de Mariage";
        require_once __DIR__ . '/../View/demand_marriage.php';
    }

     public function final_process()
    {
        $title = "Traitement Final de la Demande";
        require_once __DIR__ . '/../View/traitement_final_demande.php';
    }

    
}

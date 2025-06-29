<?php
require_once __DIR__ . '/../Model/certificate_demand.php';

class SigningController {
    private $demandeModel;
    
    public function __construct() {
        $this->demandeModel = new certificate_demand();
    }
    
    public function handleRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processSignature($id);
        }
    }
    
    private function processSignature($id) {
        try {
            $codeDemande = $_POST['code_demande'] ?? '';
            $signatureData = $_POST['signature'] ?? '';
            $id=$_POST['id'] ??'';
            
            if (empty($codeDemande) || empty($signatureData)) {
                throw new Exception("Données manquantes");
            }
            
            $filePath = $this->saveSignatureImage($signatureData);
            $success = $this->demandeModel->AddSigning($codeDemande, $filePath);
            $result= $this->demandeModel->SigningByOfficer($id,$codeDemande);
            
            if (!$success) {
                throw new Exception("Échec de l'enregistrement");
            }
            
            header('Location: officier_page.php?id=' . urlencode($id));
            exit;
            
        } catch (Exception $e) {
            die("Erreur : ".$e->getMessage());
        }
    }
    
    private function saveSignatureImage($base64Data) {
        $base64Data = str_replace('data:image/png;base64,', '', $base64Data);
        $imageData = base64_decode($base64Data);
        
        $uploadDir = __DIR__.'/../signatures/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = 'signature_'.time().'.png';
        file_put_contents($uploadDir.$filename, $imageData);
        
        return '/signatures/'.$filename;
    }
}
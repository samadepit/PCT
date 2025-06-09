<?php
session_start();
require_once __DIR__ . '/../Model/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function getStatisticsAdministration(){
        return [
            'agent'=>$this->userModel->getNumberAgent(),
            'officer' => $this->userModel->getNumberOfficer(),
        ];
    }
    public function getAllAdministration(){
        return $this->userModel->getAllAdministration();
    }

    public function UpdateStatutAdministration($id) {
        $result = $this->userModel->UpdateStatutAdministration($id);
        
        return $result;
    }
    public function UpdateRoleAdministration($id) {
        $result = $this->userModel->UpdateRoleAdministration($id);
        return $result;
    }

    public function createAdministrationUser($data) {
        $result=$this->userModel->InsertAdministrationUser($data);
        return $result;
    }
    
    

}
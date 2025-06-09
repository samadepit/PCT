<?php
require_once __DIR__ . '/../config/dbconnect.php';

class User
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function findByEmail($email)
    {
        $stmt = $this->con->prepare("SELECT * FROM administration WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllAdministration() {
        $query = "
            SELECT *
            FROM administration ad
        ";

        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function UpdateStatutAdministration($id) {
        $query = "SELECT statut FROM administration WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->execute([':id' => $id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$current) return 0;
    
        $newStatut = ($current['statut'] === 'actif') ? 'inactif' : 'actif';
    
        $updateQuery = "UPDATE administration SET statut = :newStatut WHERE id = :id";
        $updateStmt = $this->con->prepare($updateQuery);
        $updateStmt->execute([
            ':newStatut' => $newStatut,
            ':id' => $id,
        ]);
    
        return $updateStmt->rowCount();
    }


    public function UpdateRoleAdministration($id) {
        $query = "SELECT role FROM administration WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->execute([':id' => $id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$current) return 0;
        $newRole = ($current['role'] === 'agent') ? 'officier' : 'agent';
        $updateQuery = "UPDATE administration SET role = :newRole WHERE id = :id";
        $updateStmt = $this->con->prepare($updateQuery);
        $updateStmt->execute([
            ':newRole' => $newRole,
            ':id' => $id,
        ]);
    
        return $updateStmt->rowCount();
    }



    public function getNumberOfficer() {
            $stmt = $this->con->prepare("
                SELECT COUNT(*) AS total_Officer
                FROM administration
                WHERE  role='officier'
            ");
            $stmt->execute();
            return (int)$stmt->fetchColumn();
    }
    public function getNumberAgent() {
            $stmt = $this->con->prepare("
                SELECT COUNT(*) AS total_Agent
                FROM administration
                WHERE  role='agent'
            ");
            $stmt->execute();
            return (int)$stmt->fetchColumn();
    }

    public function InsertAdministrationUser($data) {
        $query = "INSERT INTO administration (nom, prenom, numero_telephone, profession, email, password, role, statut, date_creation) 
                  VALUES (:nom, :prenom, :numero_telephone, :profession, :email, :password, :role, :statut, NOW())";
    
        $stmt = $this->con->prepare($query);
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':numero_telephone' => $data['numero_telephone'],
            ':profession' => $data['profession'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'],
            ':statut' => $data['statut'],
        ];

        try {
            $stmt->execute($params);
            return $this->con->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur insertion naissance : " . $e->getMessage());
            return false;
        }
    }
}

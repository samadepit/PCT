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
}

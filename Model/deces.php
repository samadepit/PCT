<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Deces
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function acte_deces(array $data,$id_naissance) {
        $stmt = $this->con->prepare("
            INSERT INTO deces (
                id_naissance,
                date_deces,
                lieu_deces,
                cause,
                genre,
                profession,
                date_creation
            ) VALUES (
                :id_naissance,
                :date_deces,
                :lieu_deces,
                :cause,
                :genre,
                :profession
                NOW()
            )
        ");
        $params = [
            'id_naissance' => $id_naissance ,
            'date_deces' => $data['date_deces'] ,
            'lieu_deces' => $data['lieu_deces'] ,
            'lieu_naissance' => $data['lieu_naissance'] ,
            'cause' => $data['cause'] ?? null,
            'genre' => $data['genre'],
            'profession' => $data['profession']

        ];

        try {
            $stmt->execute($params);
            return "succes";
        } catch (Exception $e) {
            error_log("Erreur insertion naissance : " . $e->getMessage());
            return false;
        }
    }
}

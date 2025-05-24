<?php

require_once __DIR__ . '/../config/dbconnect.php'; 

class Mariage
{
    private  $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function acte_mariage($id_naissance_mari, $id_naissance_femme, $date_mariage, $lieu_mariage, $nombre_enfant, $numero_registre) {
        $stmt = $this->con->prepare("
            INSERT INTO mariage (
                id_naissance_mari,
                id_naissance_femme,
                date_mariage,
                lieu_mariage,
                nombre_enfant,
                numero_registre,
                date_creation
            )
            VALUES (
                :id_naissance_mari,
                :id_naissance_femme,
                :date_mariage,
                :lieu_mariage,
                :nombre_enfant,
                :numero_registre,
                NOW()
            )
        ");

        $stmt->execute([
            ':id_naissance_mari' => $id_naissance_mari,
            ':id_naissance_femme' => $id_naissance_femme,
            ':date_mariage' => $date_mariage,
            ':lieu_mariage' => $lieu_mariage,
            ':nombre_enfant' => $nombre_enfant,
            ':numero_registre' => $numero_registre
        ]);

        return true;
    }
}

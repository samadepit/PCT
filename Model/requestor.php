<?php

require_once __DIR__ . '/../config/dbconnect.php';

class Demandeur
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert_data_requestor($code_demande,array $data)
    {
        $required = ['nom', 'prenom','relation_avec_beneficiaire'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Le champ $field est obligatoire.");
            }
        }

        $stmt = $this->con->prepare("
            INSERT INTO demandeur (
                code_demande,nom, prenom, relation_avec_beneficiaire, numero_telephone, email,lieu_residence, date_creation
            ) VALUES (
                :code_demande,:nom, :prenom, :relation_avec_beneficiaire, :numero_telephone, :email,:lieu_residence, NOW()
            )
        ");
        $params = [
            'code_demande' => $code_demande,
            'nom' => $data['nom'] ?? null,
            'prenom' => $data['prenom'] ?? null,
            'relation_avec_beneficiaire' => $data['relation_avec_beneficiaire'] ?? null,
            'numero_telephone' => $data['numero_telephone'] ?? null,
            'email' => $data['email'] ?? null,
            'lieu_residence' => $data['lieu_residence'] ?? null
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function get_requestor_ById($id)
    {
        $stmt = $this->con->prepare("
        SELECT * FROM demandeur WHERE id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

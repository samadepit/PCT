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

    public function creerDemandeur($code_demande,array $data)
    {
        $required = ['nom', 'prenom','relation_avec_beneficiaire'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Le champ $field est obligatoire.");
            }
        }

        $stmt = $this->con->prepare("
            INSERT INTO demandeur (
                code_demande,nom, prenom, relation_avec_beneficiaire, numero_telephone, email, date_creation
            ) VALUES (
                :code_demande,:nom, :prenom, :relation_avec_beneficiaire, :numero_telephone, :email, NOW()
            )
        ");
        $params = [
            'code_demande' => $code_demande,
            'nom' => $data['nom'] ?? null,
            'prenom' => $data['prenom'] ?? null,
            'relation_avec_beneficiaire' => $data['relation_avec_beneficiaire'] ?? null,
            'numero_telephone' => $data['numero_telephone'] ?? null,
            'email' => $data['email'] ?? null
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function getDemandeurById($id)
    {
        $stmt = $this->con->prepare("
        SELECT * FROM demandeur WHERE id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

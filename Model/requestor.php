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
                code_demande,nom, prenom, relation_avec_beneficiaire, numero_telephone, email,lieu_residence, date_creation,piece_identite_demandeur
            ) VALUES (
                :code_demande,:nom, :prenom, :relation_avec_beneficiaire, :numero_telephone, :email,:lieu_residence, NOW() ,:piece_identite_demandeur
            )
        ");
        $params = [
            'code_demande' => $code_demande,
            'nom' => $data['nom'] ?? null,
            'prenom' => $data['prenom'] ?? null,
            'relation_avec_beneficiaire' => $data['relation_avec_beneficiaire'] ?? null,
            'numero_telephone' => $data['numero_telephone'] ?? null,
            'email' => $data['email'] ?? null,
            'lieu_residence' => $data['lieu_residence'] ?? null,
            'piece_identite_demandeur' => $data['piece_identite_demandeur'] ?? null
        ];

        if ($stmt->execute($params)) {
            return $data['email']; 
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

    public function get_requestorMail_ByCodeDemande($code_demande)
    {
        $stmt = $this->con->prepare("
            SELECT email FROM demandeur WHERE code_demande = :code_demande
        ");
        $stmt->bindValue(':code_demande', $code_demande, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : null;
    }
}

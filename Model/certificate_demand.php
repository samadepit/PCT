<?php

require_once __DIR__ . '/../config/dbconnect.php';

class certificate_demand
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }
    public function insert_data_certificate_demand($code_demand, $type_certificate, $id_certificate) {
        $stmt = $this->con->prepare("
            INSERT INTO actes_demande (code_demande, type_acte, id_acte, est_signer, date_creation)
            VALUES (:code_demande, :type_acte, :id_acte, false, NOW())
        ");
        $stmt->execute([
            ':code_demande' => $code_demand,
            ':type_acte'  => $type_certificate,
            ':id_acte'    => $id_certificate,
        ]);
        return true;
    }


    public function get_Alldemand($code_demand) {
        $stmt = $this->con->prepare("
            SELECT 
                -- Données générales de la demande
                ad.type_acte,
                ad.payer,
                ad.est_signer,
                ad.signature,
                dm.statut,
                dm.motif_rejet,
                dm.localiter,
                dm.date_creation AS demande_date_creation,
                ad.code_demande,

                 -- Information de l'officier d'état civil
                adm.nom AS officier_nom,
                adm.prenom AS officier_prenom,
    
                -- Infos Naissance (si applicable)
                n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.heure_naissance,
                n.lieu_naissance, n.nom_pere, n.prenom_pere, n.profession_pere,
                n.nom_mere, n.prenom_mere, n.profession_mere,
                n.date_mariage AS naissance_date_mariage, n.lieu_mariage AS naissance_lieu_mariage,
                n.statut_mariage AS naissance_statut_mariage, n.date_deces AS naissance_date_deces,
                n.lieu_deces AS naissance_lieu_deces, n.genre AS naissance_genre, n.numero_registre,
                n.piece_identite_pere, n.piece_identite_mere, n.certificat_de_naissance,
                n.date_creation AS naissance_date_creation,
    
                -- Infos Mariage (si applicable)
                mari.nom_epoux, mari.prenom_epoux, mari.date_naissance_epoux, mari.lieu_naissance_epoux,
                mari.nationalite_epoux, mari.situation_matrimoniale_epoux, mari.temoin_epoux, mari.profession_epoux,
                mari.nom_epouse, mari.prenom_epouse, mari.date_naissance_epouse, mari.lieu_naissance_epouse,
                mari.situation_matrimoniale_epouse, mari.temoin_epouse, mari.nationalite_epouse, mari.profession_epouse,
                mari.date_mariage, mari.lieu_mariage,
                mari.piece_identite_epoux, mari.certificat_residence_epoux,
                mari.piece_identite_epouse, mari.certificat_residence_epouse,
                mari.date_creation AS mariage_date_creation,
    
                -- Infos Décès (si applicable)
                d.nom_defunt, d.prenom_defunt, d.date_naissance AS defunt_date_naissance,
                d.lieu_naissance AS defunt_lieu_naissance, d.date_deces, d.lieu_deces, d.cause,
                d.genre AS defunt_genre, d.profession AS defunt_profession,
                d.certificat_medical_deces, d.piece_identite_defunt,
                d.date_creation AS deces_date_creation,
                d.genre,d.profession
    
            FROM actes_demande ad
            INNER JOIN demande dm ON ad.code_demande = dm.code_demande
            LEFT JOIN administration adm ON ad.id_officier = adm.id
            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN deces d ON ad.type_acte = 'deces' AND ad.id_acte = d.id
    
            WHERE ad.code_demande = :code_demande
        ");
    
        $stmt->execute([':code_demande' => $code_demand]);
        return [$stmt->fetch(PDO::FETCH_ASSOC)];
    }
    

    public function getAllPendingActeDemandes()
    {
        $query = "
            SELECT 
                -- Données générales
                ad.id, ad.type_acte, ad.id_acte, ad.code_demande, ad.payer, ad.est_signer, ad.signature,
                d.statut, d.motif_rejet, d.localiter, d.date_creation AS demande_date_creation,
    
                -- Demandeur
                dm.nom AS nom_demandeur, dm.prenom AS prenom_demandeur, dm.relation_avec_beneficiaire,
    
                -- Infos Naissance
                n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.heure_naissance,
                n.lieu_naissance, n.nom_pere, n.prenom_pere, n.profession_pere,
                n.nom_mere, n.prenom_mere, n.profession_mere,
                n.date_mariage AS naissance_date_mariage, n.lieu_mariage AS naissance_lieu_mariage,
                n.statut_mariage AS naissance_statut_mariage, n.date_deces AS naissance_date_deces,
                n.lieu_deces AS naissance_lieu_deces, n.genre AS naissance_genre, n.numero_registre,
                n.piece_identite_pere, n.piece_identite_mere, n.certificat_de_naissance,
                n.date_creation AS naissance_date_creation,
    
                -- Infos Mariage
                mari.nom_epoux, mari.prenom_epoux, mari.date_naissance_epoux, mari.lieu_naissance_epoux,
                mari.nationalite_epoux, mari.situation_matrimoniale_epoux, mari.temoin_epoux, mari.profession_epoux,
                mari.nom_epouse, mari.prenom_epouse, mari.date_naissance_epouse, mari.lieu_naissance_epouse,
                mari.situation_matrimoniale_epouse, mari.temoin_epouse, mari.nationalite_epouse, mari.profession_epouse,
                mari.date_mariage, mari.lieu_mariage,
                mari.piece_identite_epoux, mari.certificat_residence_epoux,
                mari.piece_identite_epouse, mari.certificat_residence_epouse,
                mari.date_creation AS mariage_date_creation,
    
                -- Infos Décès
                dc.nom_defunt, dc.prenom_defunt, dc.date_naissance AS defunt_date_naissance,
                dc.lieu_naissance AS defunt_lieu_naissance, dc.date_deces, dc.lieu_deces, dc.cause,
                dc.genre AS defunt_genre, dc.profession AS defunt_profession,
                dc.certificat_medical_deces, dc.piece_identite_defunt,
                dc.date_creation AS deces_date_creation
    
            FROM actes_demande ad
            INNER JOIN demande d ON ad.code_demande = d.code_demande
            INNER JOIN demandeur dm ON d.code_demande = dm.code_demande
    
            -- Jointures conditionnelles selon type d'acte
            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
    
            WHERE d.statut = 'en_attente';
        ";
    
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getOneCertificateById($id_certificate) {
        $query = "
           SELECT 
            ad.id,
            ad.type_acte,
            ad.id_acte,
            ad.code_demande,
            d.localiter,
            d.date_creation AS date_demande,
            ad.payer,
            ad.est_signer,
            ad.signature,
    
            -- Demandeur
            dm.nom AS nom_demandeur,
            dm.prenom AS prenom_demandeur,
            dm.lieu_residence,
            dm.numero_telephone AS numero_demandeur,
            dm.email AS email_demandeur,
            dm.relation_avec_beneficiaire,
            dm.piece_identite_demandeur,
    
            -- Naissance
            n.nom_beneficiaire,
            n.prenom_beneficiaire,
            n.date_naissance,
            n.lieu_naissance,
            n.heure_naissance,
            n.nom_pere,
            n.prenom_pere,
            n.profession_pere,
            n.nom_mere,
            n.prenom_mere,
            n.profession_mere,
            n.genre AS naissance_genre,
            n.numero_registre,
            n.piece_identite_pere,
            n.piece_identite_mere,
            n.certificat_de_naissance,
    
            -- Mariage
            mari.nom_epoux,
            mari.prenom_epoux,
            mari.date_naissance_epoux,
            mari.lieu_naissance_epoux,
            mari.nationalite_epoux,
            mari.situation_matrimoniale_epoux,
            mari.temoin_epoux,
            mari.profession_epoux,
            mari.nom_epouse,
            mari.prenom_epouse,
            mari.date_naissance_epouse,
            mari.lieu_naissance_epouse,
            mari.nationalite_epouse,
            mari.situation_matrimoniale_epouse,
            mari.temoin_epouse,
            mari.profession_epouse,
            mari.date_mariage,
            mari.lieu_mariage,
            mari.piece_identite_epoux,
            mari.piece_identite_epouse,
            mari.certificat_residence_epoux,
            mari.certificat_residence_epouse,
            mari.date_creation,
    
            -- Décès
            dc.nom_defunt,
            dc.prenom_defunt,
            dc.date_naissance AS date_naissance_defunt,
            dc.lieu_naissance AS lieu_naissance_defunt,
            dc.date_deces,
            dc.lieu_deces,
            dc.cause,
            dc.genre AS defunt_genre,
            dc.profession AS defunt_profession,
            dc.certificat_medical_deces,
            dc.piece_identite_defunt
    
        FROM actes_demande ad
        JOIN demande d ON ad.code_demande = d.code_demande
        LEFT JOIN demandeur dm ON dm.code_demande = d.code_demande
        LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
        LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
        LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
    
        WHERE ad.code_demande = ?;
        ";
    
        $stmt = $this->con->prepare($query);
        $stmt->execute([$id_certificate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    


    public function getAllvalidationCertificateDemandes() {
        $query = "
            SELECT 
            ad.id,
            ad.type_acte,
            ad.id_acte,
            ad.code_demande,
            ad.date_creation AS date_demande,
            d.localiter,
            ad.payer,
            ad.est_signer,
    
            -- Demandeur
            dm.nom AS nom_demandeur,
            dm.prenom AS prenom_demandeur,
            dm.relation_avec_beneficiaire,
            dm.numero_telephone AS numero_demandeur,
            dm.email AS email_demandeur,
    
            -- Naissance
            n.nom_beneficiaire,
            n.prenom_beneficiaire,
            n.date_naissance,
            n.lieu_naissance,
            n.nom_pere,
            n.nom_mere,
    
            -- Mariage
            mari.nom_epoux,
            mari.prenom_epoux,
            mari.nom_epouse,
            mari.prenom_epouse,
            mari.date_mariage,
            mari.lieu_mariage,
    
            -- Décès
            dc.nom_defunt,
            dc.prenom_defunt,
            dc.date_deces,
            dc.lieu_deces
    
        FROM actes_demande ad
        JOIN demande d ON ad.code_demande = d.code_demande
        LEFT JOIN demandeur dm ON d.code_demande = dm.code_demande
        LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
        LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
        LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
    
        WHERE d.statut = 'valider'
        AND ad.est_signer = 0;
        ";
    
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOnevalidationcetificateByID($id_certificate) {
        $query = "
           SELECT 
            ad.id,
            ad.type_acte,
            ad.id_acte,
            ad.code_demande,
            d.date_creation AS date_demande,
            d.localiter,
            ad.payer,
            ad.est_signer,
    
            -- Demandeur
            dm.nom AS nom_demandeur,
            dm.prenom AS prenom_demandeur,
            dm.relation_avec_beneficiaire,
            dm.numero_telephone AS numero_demandeur,
            dm.email AS email_demandeur,
    
            -- Naissance
            n.nom_beneficiaire,
            n.prenom_beneficiaire,
            n.date_naissance,
            n.lieu_naissance,
            n.heure_naissance,
            n.nom_pere,
            n.prenom_pere,
            n.profession_pere,
            n.nom_mere,
            n.prenom_mere,
            n.profession_mere,
    
            -- Mariage
            mari.nom_epoux,
            mari.prenom_epoux,
            mari.date_naissance_epoux,
            mari.lieu_naissance_epoux,
            mari.nom_epouse,
            mari.prenom_epouse,
            mari.date_naissance_epouse,
            mari.lieu_naissance_epouse,
            mari.date_mariage,
            mari.lieu_mariage,
            mari.date_creation AS mariage_date_creation,
    
            -- Décès
            dc.nom_defunt,
            dc.prenom_defunt,
            dc.date_naissance AS date_naissance_defunt,
            dc.lieu_naissance AS lieu_naissance_defunt,
            dc.date_deces,
            dc.lieu_deces,
            dc.cause,
            dc.genre AS defunt_genre,
            dc.profession AS defunt_profession
    
        FROM actes_demande ad
        JOIN demande d ON ad.code_demande = d.code_demande
        LEFT JOIN demandeur dm ON d.code_demande = dm.code_demande
        LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
        LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
        LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
    
        WHERE d.statut = 'valider'
        AND ad.est_signer = 0
        AND ad.code_demande = :code_demande;
        ";
    
        $stmt = $this->con->prepare($query);
        $stmt->execute([':code_demande' => $id_certificate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function AddSigning($code_demande, $cheminSignature) {
        $stmt = $this->con->prepare("
        UPDATE actes_demande SET est_signer = TRUE, signature = :signature WHERE code_demande = :code
        ");
        return $stmt->execute([
            ':signature' => $cheminSignature,
            ':code' => $code_demande
        ]);
    }
    public function updatePayment($code_demande) {
         try {
        $stmt = $this->con->prepare("
        UPDATE actes_demande SET payer = 1 WHERE code_demande = :code_demande");
        $stmt->execute([':code_demande' => $code_demande]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du paiement: " . $e->getMessage());
        return false;
    }
    }

    public function ValidateByAgent($id_agent,$code_demand) {
        $query = "
        UPDATE actes_demande SET id_agent = ? WHERE code_demande = ?";
        $stmt = $this->con->prepare($query);
        return $stmt->execute([$id_agent,$code_demand]);
    }

    public function SigningByOfficer($id_officier,$code_demand) {
        $query = "
        UPDATE actes_demande SET id_officier = ?, date_signature = NOW() WHERE code_demande = ?";
        $stmt = $this->con->prepare($query);
        return $stmt->execute([$id_officier,$code_demand]);
    }

    public function getNumberBirth() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_birth
            FROM actes_demande 
            WHERE type_acte = 'naissance'
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumberDeath() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_death
            FROM actes_demande 
            WHERE type_acte = 'deces'
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumberMarriage() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_marriage
            FROM actes_demande 
            WHERE type_acte = 'mariage'
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    
    public function getNumbercertificatePending() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_pending
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE statut = 'en_attente' 
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumbercertificateValidate() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_validate
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE statut = 'valider'
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumbercertificateRejeted() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_rejeted
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE statut = 'rejeter'
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumbercertificate() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_certificate
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE  payer=1
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumbercertificateSigned() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_certificate_signed
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE  statut = 'valider'  and est_signer=1
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

   

}



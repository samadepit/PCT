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
            ad.type_acte,
            ad.payer,
            ad.est_signer,
            ad.signature,
            dm.statut,
            dm.date_creation,
            dm.localiter,
            ad.code_demande,
            dm.motif_rejet,

            -- Information de l'officier d'état civil
            adm.nom AS officier_nom,
            adm.prenom AS officier_prenom,

            -- Naissance (si acte = naissance)
            n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.lieu_naissance,
            n.nom_pere, n.prenom_pere, n.profession_pere,
            n.nom_mere, n.prenom_mere, n.profession_mere,
            n.date_creation AS naissance_date_creation,n.heure_naissance,n.genre AS naissance_genre,
            n.date_mariage AS naissance_date_mariage,n.lieu_mariage AS naissance_lieu_mariage,
            n.statut_mariage AS naissance_statut_mariage,n.date_deces AS naissance_date_deces ,
            n.lieu_deces AS naissance_lieu_deces,n.numero_registre,

            -- Mariage
            mari.date_mariage, mari.lieu_mariage, mari.date_creation AS mariage_date_creation,
            mari.nombre_enfant,
            homme.nom_beneficiaire AS nom_mari, homme.prenom_beneficiaire AS prenom_mari,
            homme.date_naissance AS date_naissance_mari,femme.date_naissance AS date_naissance_femme,
            femme.nom_beneficiaire AS nom_femme, femme.prenom_beneficiaire AS prenom_femme,
          

            -- Décès
            defunt.nom_beneficiaire AS nom_defunt, defunt.prenom_beneficiaire AS prenom_defunt,
            defunt.date_naissance AS defunt_date_naissance,defunt.lieu_naissance AS defunt_lieu_naissance,
            d.lieu_deces, d.date_deces, d.cause, d.genre, d.profession,
            d.date_creation AS deces_date_creation

            FROM actes_demande ad
            LEFT JOIN demande dm ON ad.code_demande = dm.code_demande

            LEFT JOIN administration adm ON ad.id_officier = adm.id


            -- Naissance uniquement si type_acte = 'naissance'
            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id

            -- Mariage uniquement si type_acte = 'mariage'
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN naissance homme ON mari.id_naissance_mari = homme.id
            LEFT JOIN naissance femme ON mari.id_naissance_femme = femme.id

            -- Décès uniquement si type_acte = 'deces'
            LEFT JOIN deces d ON ad.type_acte = 'deces' AND ad.id_acte = d.id
            LEFT JOIN naissance defunt ON d.id_naissance = defunt.id

            WHERE ad.code_demande = :code_demande;

            ");
        $stmt->execute([':code_demande' => $code_demand]);
        $test_ =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump( $test_);
        return  $test_;

    }

    public function getAllPendingActeDemandes()
    {
        $query = "
            SELECT 
            ad.id, ad.type_acte, ad.id_acte, ad.code_demande, d.statut,
            d.date_creation AS date_demande,
            dm.nom AS nom_demandeur, dm.prenom AS prenom_demandeur, dm.relation_avec_beneficiaire,

            -- Données naissance directe
            n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.lieu_naissance,

            -- Données mariage (via naissance)
            mari.date_mariage, mari.lieu_mariage,
            mari.numero_registre AS registre_mariage,
            mari.date_creation AS mariage_date_creation,
            homme.nom_beneficiaire AS nom_mari,
            homme.prenom_beneficiaire AS prenom_mari,
            femme.nom_beneficiaire AS nom_femme,
            femme.prenom_beneficiaire AS prenom_femme,

            -- Données décès (via naissance)
            dc.date_deces, dc.lieu_deces,
            def.nom_beneficiaire AS nom_defunt,
            def.prenom_beneficiaire AS prenom_defunt

            FROM actes_demande ad

            INNER JOIN demande d ON ad.code_demande = d.code_demande
            INNER JOIN demandeur dm ON d.code_demande = dm.code_demande

            -- Cas naissance direct
            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id

            -- Cas mariage
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN naissance homme ON mari.id_naissance_mari = homme.id
            LEFT JOIN naissance femme ON mari.id_naissance_femme = femme.id

            -- Cas décès
            LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
            LEFT JOIN naissance def ON dc.id_naissance = def.id

            WHERE  d.statut='en_attente' and ad.payer=1;

        ";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOneCertificateById($id_certificate) {
        $query = "
            SELECT 
                ad.id, ad.type_acte, ad.id_acte, ad.code_demande,
                d.date_creation AS date_demande,
                dm.nom AS nom_demandeur, dm.prenom AS prenom_demandeur, dm.relation_avec_beneficiaire,
                dm.numero_telephone AS numero_demandeur, dm.email AS email_demandeur,

                n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.lieu_naissance,
                n.heure_naissance, n.nom_pere, n.prenom_pere, n.profession_pere,
                n.nom_mere, n.prenom_mere, n.profession_mere,
                mari.date_mariage, mari.lieu_mariage,
                mari.numero_registre AS registre_mariage,
                mari.date_creation AS mariage_date_creation,
                homme.nom_beneficiaire AS nom_mari,
                homme.prenom_beneficiaire AS prenom_mari,
                homme.date_naissance as age_homme,
                femme.date_naissance as age_femme,
                femme.nom_beneficiaire AS nom_femme,
                femme.prenom_beneficiaire AS prenom_femme,

                dc.date_deces, dc.lieu_deces,
                def.nom_beneficiaire AS nom_defunt,
                def.prenom_beneficiaire AS prenom_defunt

            FROM actes_demande ad

            INNER JOIN demande d ON ad.code_demande = d.code_demande
            INNER JOIN demandeur dm ON d.code_demande = dm.code_demande

            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN naissance homme ON mari.id_naissance_mari = homme.id
            LEFT JOIN naissance femme ON mari.id_naissance_femme = femme.id
            LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
            LEFT JOIN naissance def ON dc.id_naissance = def.id

            WHERE ad.code_demande = ?
        ";

        $stmt = $this->con->prepare($query);
        $stmt->execute([$id_certificate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllvalidationCertificateDemandes() {
        $query = "
            SELECT 
                ad.id, ad.type_acte, ad.id_acte, ad.code_demande,
                d.date_creation AS date_demande,
                dm.nom AS nom_demandeur, dm.prenom AS prenom_demandeur, dm.relation_avec_beneficiaire,
                dm.numero_telephone AS numero_demandeur, dm.email AS email_demandeur,

                n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.lieu_naissance,
                n.heure_naissance, n.nom_pere, n.prenom_pere, n.profession_pere,
                n.nom_mere, n.prenom_mere, n.profession_mere,
                mari.date_mariage, mari.lieu_mariage,
                mari.numero_registre AS registre_mariage,
                mari.date_creation AS mariage_date_creation,
                homme.nom_beneficiaire AS nom_mari,
                homme.prenom_beneficiaire AS prenom_mari,
                homme.date_naissance as age_homme,
                femme.date_naissance as age_femme,
                femme.nom_beneficiaire AS nom_femme,
                femme.prenom_beneficiaire AS prenom_femme,

                dc.date_deces, dc.lieu_deces,
                def.nom_beneficiaire AS nom_defunt,
                def.prenom_beneficiaire AS prenom_defunt

            FROM actes_demande ad

            INNER JOIN demande d ON ad.code_demande = d.code_demande
            INNER JOIN demandeur dm ON d.code_demande = dm.code_demande

            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN naissance homme ON mari.id_naissance_mari = homme.id
            LEFT JOIN naissance femme ON mari.id_naissance_femme = femme.id
            LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
            LEFT JOIN naissance def ON dc.id_naissance = def.id
            WHERE d.statut='valider'  and est_signer= FALSE;
        ";

        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOnevalidationcetificateByID($id_certificate) {
        $query = "
            SELECT 
                ad.id, ad.type_acte, ad.id_acte, ad.code_demande,
                d.date_creation AS date_demande,
                dm.nom AS nom_demandeur, dm.prenom AS prenom_demandeur, dm.relation_avec_beneficiaire,
                dm.numero_telephone AS numero_demandeur, dm.email AS email_demandeur,

                n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.lieu_naissance,
                n.heure_naissance, n.nom_pere, n.prenom_pere, n.profession_pere,
                n.nom_mere, n.prenom_mere, n.profession_mere,
                mari.date_mariage, mari.lieu_mariage,
                mari.numero_registre AS registre_mariage,
                mari.date_creation AS mariage_date_creation,
                homme.nom_beneficiaire AS nom_mari,
                homme.prenom_beneficiaire AS prenom_mari,
                homme.date_naissance as age_homme,
                femme.date_naissance as age_femme,
                femme.nom_beneficiaire AS nom_femme,
                femme.prenom_beneficiaire AS prenom_femme,

                dc.date_deces, dc.lieu_deces,
                def.nom_beneficiaire AS nom_defunt,
                def.prenom_beneficiaire AS prenom_defunt

            FROM actes_demande ad

            INNER JOIN demande d ON ad.code_demande = d.code_demande
            INNER JOIN demandeur dm ON d.code_demande = dm.code_demande

            LEFT JOIN naissance n ON ad.type_acte = 'naissance' AND ad.id_acte = n.id
            LEFT JOIN mariage mari ON ad.type_acte = 'mariage' AND ad.id_acte = mari.id
            LEFT JOIN naissance homme ON mari.id_naissance_mari = homme.id
            LEFT JOIN naissance femme ON mari.id_naissance_femme = femme.id
            LEFT JOIN deces dc ON ad.type_acte = 'deces' AND ad.id_acte = dc.id
            LEFT JOIN naissance def ON dc.id_naissance = def.id

            WHERE d.statut='valider' and ad.est_signer=0 and   ad.code_demande = :code_demande;
        ";

        $stmt = $this->con->prepare($query);
        $stmt->execute([$id_certificate]);
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
            WHERE statut = 'en_attente' and payer=1
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumbercertificateValidate() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_validate
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE statut = 'valider' and payer=1
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNumbercertificateRejeted() {
        $stmt = $this->con->prepare("
            SELECT COUNT(*) AS total_rejeted
            FROM demande d
            INNER JOIN actes_demande ad on d.code_demande = ad.code_demande
            WHERE statut = 'rejeter' and payer=1
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
            WHERE  statut = 'valider' and payer=1 and est_signer=1
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

}



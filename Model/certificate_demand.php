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

    public function mark_demand_signed($id_certificate_demande) {
        $stmt = $this->con->prepare("
        UPDATE actes_demande SET signer = 1 WHERE id = :id
        ");
        $stmt->execute([':id' => $id_certificate_demande]);
    }

    public function get_Alldemand($code_demand) {
        $stmt = $this->con->prepare("
            SELECT 
            ad.type_acte,
            dm.statut,

            -- Naissance (si acte = naissance)
            n.nom_beneficiaire, n.prenom_beneficiaire, n.date_naissance, n.lieu_naissance,
            n.nom_pere, n.prenom_pere, n.profession_pere,
            n.nom_mere, n.prenom_mere, n.profession_mere,
            n.date_creation AS naissance_date_creation,

            -- Mariage
            mari.date_mariage, mari.lieu_mariage, mari.date_creation AS mariage_date_creation,
            homme.nom_beneficiaire AS nom_mari, homme.prenom_beneficiaire AS prenom_mari,
            femme.nom_beneficiaire AS nom_femme, femme.prenom_beneficiaire AS prenom_femme,

            -- Décès
            defunt.nom_beneficiaire AS nom_defunt, defunt.prenom_beneficiaire AS prenom_defunt,
            d.lieu_deces, d.date_deces, d.cause, d.genre, d.profession,
            d.date_creation AS deces_date_creation

FROM actes_demande ad
LEFT JOIN demande dm ON ad.code_demande = dm.code_demande

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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

            WHERE d.statut = 'en_attente';

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
    public function getAllvalidationActeDemandes($id_certificate) {
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

            WHERE d.statut=valider and  ad.code_demande = :code_demande;
        ";

        $stmt = $this->con->prepare($query);
        $stmt->execute([$id_certificate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}

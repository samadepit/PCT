-- Base de données
CREATE DATABASE IF NOT EXISTS etatcivil;
USE etatcivil;

CREATE TABLE IF NOT EXISTS demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50) UNIQUE NOT NULL,
    statut ENUM('en_attente','valider','rejeter','signer') DEFAULT 'en_attente' NOT NULL,
    localiter VARCHAR(100) NOT NULL,
    motif_rejet VARCHAR(255) DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS naissance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_beneficiaire VARCHAR(100) NOT NULL,
    prenom_beneficiaire VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    heure_naissance TIME DEFAULT NULL,
    lieu_naissance VARCHAR(100) NOT NULL,
    nom_pere VARCHAR(100) NOT NULL,
    prenom_pere VARCHAR(100) NOT NULL,
    profession_pere VARCHAR(100) NOT NULL,
    nom_mere VARCHAR(100) NOT NULL,
    prenom_mere VARCHAR(100) NOT NULL,
    profession_mere VARCHAR(100) NOT NULL,
    date_mariage DATE DEFAULT NULL,
    lieu_mariage VARCHAR(100) DEFAULT NULL,
    statut_mariage VARCHAR(50) DEFAULT NULL,
    date_deces DATE DEFAULT NULL,
    lieu_deces VARCHAR(100) DEFAULT NULL,
    genre VARCHAR(10) DEFAULT NULL,
    numero_registre INT DEFAULT NULL,
    piece_identite_pere VARCHAR(255) DEFAULT NULL,
    piece_identite_mere VARCHAR(255) DEFAULT NULL,
    certificat_de_naissance VARCHAR(255) DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mariage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_epoux VARCHAR(100) NOT NULL,
    prenom_epoux VARCHAR(100) NOT NULL,
    date_naissance_epoux DATE NOT NULL,
    lieu_naissance_epoux VARCHAR(100) NOT NULL,
    nationalite_epoux VARCHAR(100) NOT NULL,
    situation_matrimoniale_epoux ENUM('celibataire', 'veuf', 'divorcé') DEFAULT 'celibataire',
    temoin_epoux VARCHAR(100) NOT NULL,
    profession_epoux VARCHAR(100) NOT NULL,
    nom_epouse VARCHAR(100) NOT NULL,
    prenom_epouse VARCHAR(100) NOT NULL,
    date_naissance_epouse DATE NOT NULL,
    lieu_naissance_epouse VARCHAR(100) NOT NULL,
    situation_matrimoniale_epouse ENUM('celibataire', 'veuf', 'divorcé') DEFAULT 'celibataire',
    temoin_epouse VARCHAR(100) NOT NULL,
    nationalite_epouse VARCHAR(100) NOT NULL,
    profession_epouse VARCHAR(100) NOT NULL,
    date_mariage DATE NOT NULL,
    lieu_mariage VARCHAR(100) NOT NULL,
    piece_identite_epoux VARCHAR(255) DEFAULT NULL,
    certificat_residence_epoux VARCHAR(255) DEFAULT NULL,
    piece_identite_epouse VARCHAR(255) DEFAULT NULL,
    certificat_residence_epouse VARCHAR(255) DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS deces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_defunt VARCHAR(100) NOT NULL,
    prenom_defunt VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    lieu_naissance VARCHAR(100) NOT NULL,
    date_deces DATE NOT NULL,
    lieu_deces VARCHAR(100) NOT NULL,
    cause VARCHAR(255),
    nom_pere VARCHAR(100) NOT NULL,
    prenom_pere VARCHAR(100) NOT NULL,
    genre VARCHAR(10) DEFAULT NULL,
    profession VARCHAR(100) NOT NULL,
    certificat_medical_deces VARCHAR(255) DEFAULT NULL,
    piece_identite_defunt VARCHAR(255) DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS demandeur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50),
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    lieu_residence VARCHAR(255),
    numero_telephone VARCHAR(15),
    email VARCHAR(100),
    relation_avec_beneficiaire VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    piece_identite_demandeur VARCHAR(255) DEFAULT NULL
    CONSTRAINT fk_demandeur_demande FOREIGN KEY (code_demande) REFERENCES demande(code_demande) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS administration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50),
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    numero_telephone VARCHAR(15),
    profession VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('agent', 'officier','admin') NOT NULL,
    password VARCHAR(255) NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_administration_demande FOREIGN KEY (code_demande) REFERENCES demande(code_demande) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS actes_demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50),
    type_acte ENUM('naissance', 'mariage', 'deces') NOT NULL,
    id_acte INT NOT NULL,
    est_signer BOOLEAN DEFAULT FALSE,
    signature VARCHAR(255) DEFAULT NULL,
    payer BOOLEAN DEFAULT FALSE,
    id_agent INT DEFAULT NULL,
    id_officier INT DEFAULT NULL,
    date_signature TIMESTAMP DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_actes_demande FOREIGN KEY (code_demande) REFERENCES demande(code_demande),
    CONSTRAINT fk_agent FOREIGN KEY (id_agent) REFERENCES administration(id) ON DELETE SET NULL,
    CONSTRAINT fk_officier FOREIGN KEY (id_officier) REFERENCES administration(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS paiement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50),
    numero VARCHAR(20),
    code_paiement VARCHAR(8),
    is_duplicate BOOLEAN DEFAULT FALSE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_paiement_demande FOREIGN KEY (code_demande) REFERENCES demande(code_demande)
);

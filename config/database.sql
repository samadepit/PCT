-- Base de données
CREATE DATABASE IF NOT EXISTS PCT;
USE PCT;

CREATE TABLE IF NOT EXISTS demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50) UNIQUE NOT NULL,
    statut ENUM('en_attente','valider','rejeter') DEFAULT 'en_attente' NOT NULL,
    localiter VARCHAR(100) NOT NULL,
    moti_rejet VARCHAR(255) DEFAULT NULL,
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
    date_mariage DATE NULL DEFAULT NULL,
    lieu_mariage VARCHAR(100) DEFAULT NULL,
    statut_mariage VARCHAR(50) DEFAULT NULL,
    date_deces DATE NULL DEFAULT NULL,
    lieu_deces VARCHAR(100) DEFAULT NULL,
    genre VARCHAR(10) DEFAULT NULL,
    numero_registre INT  DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mariage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_naissance_mari INT,
    id_naissance_femme INT,
    date_mariage DATE NOT NULL,
    lieu_mariage VARCHAR(100) NOT NULL,
    nombre_enfant INT,
    numero_registre INT  DEFAULT NULL,
    statut_mariage ENUM('Marier','Celibataire','Divorcé') DEFAULT 'Celibataire'
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_naissance_mari) REFERENCES naissance(id) ON DELETE SET NULL,
    FOREIGN KEY (id_naissance_femme) REFERENCES naissance(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS deces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_naissance INT,
    date_deces DATE NOT NULL,
    lieu_deces VARCHAR(100) NOT NULL,
    cause VARCHAR(255),
    genre VARCHAR(10) DEFAULT NULL,
    profession VARCHAR(100) NOT NULL,
    numero_registre INT  DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_naissance) REFERENCES naissance(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS demandeur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50) UNIQUE,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    lieu_residence VARCHAR(255),
    numero_telephone VARCHAR(15),
    email VARCHAR(100),
    relation_avec_beneficiaire VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (code_demande) REFERENCES demande(code_demande) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS administration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50) UNIQUE,
    localiter VARCHAR(100) NOT NULL,    
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    numero_telephone VARCHAR(15),
    profession VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('agent', 'officier','administration') NOT NULL,
    password VARCHAR(255) NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (code_demande) REFERENCES demande(code_demande) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS actes_demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_demande VARCHAR(50),
    type_acte ENUM('naissance', 'mariage', 'deces') NOT NULL,
    id_acte INT NOT NULL,
    est_signer BOOLEAN DEFAULT FALSE,
    signature VARCHAR(255) DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payer BOOLEAN DEFAULT FALSE,
    id_agent INT DEFAULT NULL,
    id_officier INT DEFAULT NULL,
    date_signature TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (code_demande) REFERENCES demande(code_demande)
    FOREIGN KEY (id_agent) REFERENCES administration(id) ON DELETE SET NULL
    FOREIGN KEY (id_officier) REFERENCES administration(id) ON DELETE SET NULL
);

CREATE TABLE paiement (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code_demande VARCHAR(50),
  numero VARCHAR(20),
  code_paiement VARCHAR(8),
  is_duplicate BOOLEAN DEFAULT FALSE,
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (code_demande) REFERENCES demande(code_demande)
);

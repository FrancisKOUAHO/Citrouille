-- Database export via SQLPro (https://www.sqlprostudio.com/allapps.html)
-- Exported by noariuchiha at 05-04-2021 23:47.
-- WARNING: This file may contain descructive statements such as DROPs.
-- Please ensure that you are running the script at the proper location.


-- BEGIN TABLE doctrine_migration_versions
DROP TABLE IF EXISTS doctrine_migration_versions;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Inserting 1 row into doctrine_migration_versions
-- Insert batch #1
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES
('DoctrineMigrations\\Version20210402180119', '2021-04-02 18:21:31', 277);

-- END TABLE doctrine_migration_versions

-- BEGIN TABLE eleve
DROP TABLE IF EXISTS eleve;
CREATE TABLE `eleve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_de_naissance` bigint(20) NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `classe` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table eleve contains no data. No inserts have been genrated.
-- Inserting 0 rows into eleve


-- END TABLE eleve

-- BEGIN TABLE liste
DROP TABLE IF EXISTS liste;
CREATE TABLE `liste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createur_id` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visibilite` int(11) NOT NULL,
  `date_creation` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FCF22AF473A201E5` (`createur_id`),
  CONSTRAINT `FK_FCF22AF473A201E5` FOREIGN KEY (`createur_id`) REFERENCES `professeur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserting 7 rows into liste
-- Insert batch #1
INSERT INTO liste (id, createur_id, nom, visibilite, date_creation) VALUES
(1, 1, 'Liste1', 1, 12345),
(2, 1, 'Liste1', 0, 1617649753),
(3, 1, 'Liste1', 0, 1617649789),
(9, 1, 'Liste_animaux_Francis', 0, 1617658745),
(10, 1, 'Liste_animaux_Francis_', 0, 1617658870),
(11, 1, 'Liste_animaux_Francis_', 0, 1617658889),
(12, 1, 'Liste_animaux_Francis__', 0, 1617658966);

-- END TABLE liste

-- BEGIN TABLE professeur
DROP TABLE IF EXISTS professeur;
CREATE TABLE `professeur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserting 1 row into professeur
-- Insert batch #1
INSERT INTO professeur (id, nom, prenom, login, mot_de_passe) VALUES
(1, 'KOUAHO', 'Francis', 'K.Francis', 'Francis225');

-- END TABLE professeur

-- BEGIN TABLE question
DROP TABLE IF EXISTS question;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reponse` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_audio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserting 13 rows into question
-- Insert batch #1
INSERT INTO question (id, reponse, url_image, url_audio) VALUES
(1, 'Girafe', 'hyhhh', 'hyhhh'),
(2, 'Chien', 'hyhhh', 'hyhhh'),
(3, 'Chien', 'uploads/images/1617655548.jpg', 'uploads/audios/1617655548.mp3'),
(4, 'Mouton', 'uploads/images/1617656335.jpg', NULL),
(5, 'Chien', 'uploads/images/1617658489.jpg', 'uploads/audios/1617658489.mp3'),
(6, 'Chien', 'uploads/images/1617658553.jpg', 'uploads/audios/1617658553.mp3'),
(7, 'Mouton', 'uploads/images/1617658553.jpg', 'uploads/audios/1617658553.mp3'),
(8, 'Chien', 'uploads/images/1617658745.jpg', 'uploads/audios/1617658745.mp3'),
(9, 'Mouton', 'uploads/images/1617658745.jpg', 'uploads/audios/1617658745.mp3'),
(10, 'Chien', 'uploads/images/1617658870.jpg', 'uploads/audios/1617658870.mp3'),
(11, 'Mouton', 'uploads/images/1617658889.jpg', 'uploads/audios/1617658889.mp3'),
(12, 'Chien', 'uploads/images/1617658966.jpg', 'uploads/audios/1617658966.mp3'),
(13, 'Mouton', 'uploads/images/1617658966.jpg', 'uploads/audios/1617658966.mp3');

-- END TABLE question

-- BEGIN TABLE question_liste
DROP TABLE IF EXISTS question_liste;
CREATE TABLE `question_liste` (
  `question_id` int(11) NOT NULL,
  `liste_id` int(11) NOT NULL,
  PRIMARY KEY (`question_id`,`liste_id`),
  KEY `IDX_CC193BF01E27F6BF` (`question_id`),
  KEY `IDX_CC193BF0E85441D8` (`liste_id`),
  CONSTRAINT `FK_CC193BF01E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CC193BF0E85441D8` FOREIGN KEY (`liste_id`) REFERENCES `liste` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserting 9 rows into question_liste
-- Insert batch #1
INSERT INTO question_liste (question_id, liste_id) VALUES
(1, 1),
(2, 1),
(8, 9),
(9, 9),
(10, 10),
(10, 11),
(11, 11),
(12, 12),
(13, 12);

-- END TABLE question_liste


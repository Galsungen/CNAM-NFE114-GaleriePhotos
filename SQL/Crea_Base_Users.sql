--
-- @author Galsungen - http://blog.galsungen.net
-- Année 2010-11
-- CNAM - NFE114 - Système d'information web
--


-- Création Base de données: `gd_galeriephotos`

CREATE DATABASE `gd_galeriephotos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;

-- Création utilisateur admin pour la base et droits pour ce dernier
GRANT SELECT,INSERT,UPDATE,DELETE ON `gd_galeriephotos`.* TO 'gp-photos'@localhost IDENTIFIED BY 'ToTo-852#963'

-- Flush privileges pour appliquer les droits
FLUSH PRIVILEGES;


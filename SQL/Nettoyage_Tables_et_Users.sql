--
-- @author Galsungen - http://blog.galsungen.net
-- Année 2010-11
-- CNAM - NFE114 - Système d'information web
--

-- Suppression Base de données: `gd_galeriephotos`
DROP DATABASE `gd_galeriephotos`;

-- Suppression utilisateurs admin et lecteur
DROP USER gp-photos@localhost;

-- Flush privileges pour mettre à plat les droits
FLUSH PRIVILEGES;

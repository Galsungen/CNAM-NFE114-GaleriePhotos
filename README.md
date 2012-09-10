# Galerie Photos #

## Matière / UE ##

 * NFE114 : Système d'information web
 * Centre régional de Rhône-Alpes, Lyon
 * Année de réalisation : 2010-11

***

## Avertissement ##

Je met à disposition ces sources et ce sujet pour accompagner de nouveaux auditeurs dans les matières concernées. En aucun cas il ne s'agit de "la" solution au sujet, mais simplement de ma solution. Ne recopiez jamais le code d'un autre sans le comprendre, et surtout pas pour le rendre au formateur. Et même en le comprenant d'ailleurs dans le cadre du CNAM ... Ecrivez votre propre code, votre propre solution, vous verrez que vous en apprendrez bien plus. Ma démarche est juste de vous aider en vous donnant une réprésentation possible d'une solution. Je sais que moi cela m'aide comme démarche, et que j'écris ensuite toujours ma solution, avec ma vision.

Bon courage à tous si vous êtes dans le cursus du CNAM (Conservatoire National des Arts et Métiers).

***

## Sujet ##

### Cahier des charges techniques ###

 * L'application sera réalisée en PHP, et s'appuiera sur une base MySQL.
 * Les interfaces respecteront les standards XHTML 1.0 Strict et CSS.
 * Quelques petites fonctionnalités Javascript sont possibles, mais non obligatoires.

### Cahier des charges fonctionnelles ###

L'application doit gérer un ensemble de photos. Les fonctionnalités minimales (obligatoires) sont :
 * Afficher les photos sous forme d'une liste. Éventuellement page par page s'il y en a beaucoup. La liste pourra afficher une miniature de la photo mais ce n'est pas absolument nécessaire.
 * Après avoir sélectionné une photo dans la liste, afficher une "fiche détaillée" avec au minimum les attributs suivants :
  * une version "miniature" de la photo (par ex. avec 300 ou 400 pixels de dimension max),
  * le nom de la photo (cf. nom de fichier),
  * l'auteur,
  * le lieu,
  * la date,
  * d'autres attributs facultatifs...
  * Pouvoir ajouter une photo (téléchargement du fichier image et renseignement des attributs).
 * Pouvoir supprimer une photo.

Note technique : Ces fonctionnalités peuvent éventuellement être remplies avec une seule table en base de données. Sauf si on veut des tables supplémentaires pour l'auteur ou le lieu...

### Remarque importante ###

L'idée est de développer une application "from scratch", et pas :
 * d'installer un système de gestion de photos trouvé sur internet,
 * de développer en utilisant un framework (cf. Zend, Cake PHP, ou Symfony...)
 * d'utiliser des fonctions Javascript avancées qui nécessitent un framework JavaScript comme jQuery...

### Fonctionnalités additionnelles (non obligatoires) ###

 * Pouvoir modifier une photo existante (pas l'image, mais les attributs : lieu, auteur, ...)
 * Extraire la date, et éventuellement d'autres attributs, des informations EXIF
 * Gérer des tags, ou des catégories. 1 photo peut être taggée plusieurs fois (ou appartenir à plusieurs catégories). Un tag (ou une catégorie) peut servir pour plusieurs photos.

Note technique : il s'agit d'une relation n x n, implémentée en base de données à l'aide d'une table "tag" ou "catégorie", et d'une table de liaison...

Fonctionnalités minimales :
 * Voir les tags d'une photo, tagger, détagger une photo.

Possibilités supplémentaires :
 * Voir les photos auxquelles est associé un tag, créer un tag, supprimer un tag.

### Contraintes techniques additionnelles (non obligatoires) ###

Développer en utilisant des objets, notamment "Photo" et "Tag" (ou "Catégorie").

### FAQ ###

Faut-il gérer les utilisateurs ?

Ce n'est pas dans le cahier des charges. Après, c'est vous qui voyez...

Faut-il réaliser toutes les fonctionnalités additionnelles mentionnées, avant de penser à des fonctionnalités additionnelles non mentionnées ?

Non. Seules les fonctionnalités minimales sont obligatoires.

Quel sera le rendu ?

Un fichier zip avec tous les fichiers html, css, php, sans oublier un dump SQL, ainsi qu'un compte-rendu avec une description de l'application réalisée (cahier des charges, orientations
techniques, ...), et une notice d'installation (comment faire fonctionner l'application à partir des fichiers fournis).

> J'ai fait le choix de ne pas fournir ici mon dossier de rendu car le but était de présenter un exemple fonctionnel du code de l'application pour comprendre et non de donner une solution toute prête. Le but est ici d'aider par l'exemple.

***

## Technologies à utiliser ##

 * XHTML
 * CSS 2.0
 * PHP 5
 * (JavaScript)

***

## Notice d'installation ##

Notice d'installation de gd_Phototheque
### I Création du dossier ###
Décompresser le fichier .zip dans un dossier comme par exemple gd_phototheque.

### II Création de la base de donnée et des tables nécessaires ###
Dans le dossier SQL :
 * Crea_Base_Users.sql
Ce fichier permet de créer la base de donnée et un utilisateur pour fonctionner uniquement avec cette dernière. Cet utilisateur à des droits limités.
 * Crea_Tables.sql 
Ce fichier permet de créer les tables de la base de données avec insertions de données de test en leur sein.
 * Nettoyage_Tables_et_Users.sql
Ce fichier permetre de supprimer la base et l'utilisateur créés par le premier fichier sql.

Dans le cadre de la création d'une base d'un autre nom, ou de l'utilisation d'un autre utilisateur,il faut éditer le fichier abdd.php et adapter les différents champs.
 * $usrbdd = 'gp-photos' ;
 * $pwdusr = 'ToTo-852#963' ;
 * $adrbdd = 'localhost' ;
 * $nombdd = 'gd_galeriephotos' ;

***

### Degré de réalisation par rapport au cahier des charges ###

<table>
  <tr>
  	<td>Fonctionnalités</td>
		<td>Réalisée</td>
		<td>Importance</td>
	</tr>
	<tr>
		<td>Afficher les photos sous la forme d'une liste.</td>
		<td>X</td>
		<td>Obligatoire</td>
	</tr>
	<tr>
		<td>
			Afficher une fiche détaillée suite à la sélection d'une photo avec les informations :<br />
			 - thumbnail<br />
			 - nom de la photo<br />
			 - auteur<br />
			 - lieu<br />
			 - date<br />
			 - autres<br />
		</td>
		<td>X</td>
		<td>Obligatoire</td>
	</tr>
	<tr>
		<td>Pouvoir ajouter une photo</td>
		<td>X</td>
		<td>Obligatoire</td>
	</tr>
	<tr>
		<td>Pouvoir supprimer une photo</td>
		<td>X</td>
		<td>Obligatoire</td>
	</tr>
	<tr>
		<td>Pouvoir éditer les informations une photo</td>
		<td>X</td>
		<td>Optionnel</td>
	</tr>
	<tr>
		<td>
		Gérer des tags ou catégories associés à une photo avec une relation nxn entre les deux tables<br />
 		 - voir les tags d'un photo, tagger, détagger<br />
		 - voir les photos auxquelles est associé un tag, créer un tag, supprimer un tag<br />
		</td>
		<td>X</td>
		<td>Optionnel</td>
	</tr>
	<tr>
		<td>Développer en utilisant des objets, notamment « Photo » et « Tag »</td>
		<td>/</td>
		<td>Optionnel</td>
	</tr>
</table>

Le « / » dans la colonne réalisée pour la ligne sur le développement objet provient du fait que je n'ai utilisé pour le moment qu'un seul objet Date pour la gestion des dates et non des objets Photo et Tag. La création de ces deux objets seraient le déroulement logique dans l'évolution de cette photothèque.

### Evolutions à apporter ###

#### Evolutions du code ####
La principale évolution à apporter serait un refactoring complet du code avec utilisation d'objets. On pourrait ainsi simplifier ce dernier et optimiser son fonctionnement. On pourra ainsi s'orienter vers une plus franche séparation des traitements et de l'affichage. Il faudrait ensuite augmenter le degré de contrôle des informations saisies comme la longueur des champs et leur contenu. La mise en place de contrôle JavaScript sur les formulaires pourrait être une aide car cela limiterait en plus le nombre de requêtes avec le serveur. On pourrait ainsi valider le contenu d'une partie des formulaires avant envoi vers le serveur.

#### Fonctionnalités additionnelles à apporter ####
Une fois le refactoring objet réalisé, je dirais qu'il faudrait orienter cette application vers plus de sécurité et surtout une gestion multi-utilisateurs. Nous pourrions ainsi mettre en place un système d'utilisateurs avec des droits (ajouter une photo, un tag, supprimer une photo, gérer les photos des autres ou juste les siennes, …). Ces utilisateurs seraient stockés dans une table propre, avec une association à une table contenant les rôles ou autorisations. Nous pourrions de plus ajouter la journalisation des modifications, ajouts ou suppression dans une table « journal ».

***

## Références ##

### Icônes ###

Les icônes utilisées dans l'application proviennent du pack famfamfam. Il est possible de télécharger ce pack à l'URL suivante :
 * http://www.famfamfam.com/

### Photographies ###

Les photographies servant d'exemples dans cette application proviennent de divers sites proposant des ressources gratuites ou libres de droits sur Internet. Vous trouverez ci-dessous plusieurs liens vers ces sites :
 * http://openphoto.net/
 * http://imagebase.davidniblack.com/main.php
 * http://www.photo-libre.fr/
 * http://photos-free.net/
 * http://www.freerangestock.com/
 * http://www.everystockphoto.com/
 * http://www.gettyimages.fr/

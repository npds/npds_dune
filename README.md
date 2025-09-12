# npds_dune

[NPDS](http://www.npds.org) CMS ... depuis 2001.

Au delà de la gestion de contenu 'classique', NPDS met en oeuvre un ensemble de fonctions spécifiquement dédiées à la gestion de Communauté et de groupes de travail collaboratif.
Il s'agit d'un Content & Community Management System (CCMS) robuste, sécurisé, complet, performant et parlant vraiment français.

Gérez votre Communauté d'utilisateurs, vos groupes de travail collaboratif, publiez, gérez et organisez
votre contenu dynamique !

Libre (Open-Source) et gratuit, NPDS est personnalisable grâce à de nombreux thèmes et modules et ne requiert que quelques compétences de base - essentiellement - issues des technologies du web. 
Ses points forts : sa richesse fonctionnelle, sa sécurité, sa rapidité, sa facilité de mise en œuvre et la qualité, la stabilité et la fiabilité de son fonctionnement.


## Required  
un serveur (local ou distant : Lamp, Wamp ...) avec
- php >= 7.1  
- mysql >= 5.5.3
- une base de donnée existante 

## Installation

Télécharger [la dernière archive](https://github.com/npds/npds_dune/releases/latest)   
Uploader sur votre serveur (local ou distant)  
Aller /index.php   
Suivre l'installation automatique ...

## Déploiement automatique et Installation (recommandé)

### Méthode rapide en 3 étapes :

1. **Téléchargez** le déployeur à la racine
```
https://raw.githubusercontent.com/npds/npds_dune/master/revolution_16/lib/deployer/npds_deployer.php
```
2. **Accédez** à l'URL dans votre navigateur :
```
https://votre-domaine.com/npds_deployer.php
```
3. **Suivez** l'interface de déploiement et d'installation automatique  
📖 [Documentation détaillée du déployeur](https://raw.githubusercontent.com/npds/npds_dune/master/revolution_16/lib/deployer/README.md)

### 🔒 Sécurité

- Le déployeur sera **auto-détruit** après l'installation réussie
- Une copie de sécurité sera conservée dans `votre-site.com/npds/lib/deployer/npds_deployer.php` pour usage futur
- **Ne renommez pas** le fichier - gardez `npds_deployer.php`
- **Ne fonctionne que pour les nouvelles installations** (bloqué si `IZ-Xinstall.ok` existe)


## Communauté

- Besoin d'aide le [forum Npds](https://www.npds.org/forum.php)
- Besoin de documentation le [dokuwiki de npds](https://bible.npds.org)


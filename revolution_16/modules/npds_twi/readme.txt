###################################################################################
##
## Nom : npds_twi
## Version : beta 1.0
## Date : 20/03/11
## Auteur: Jean Pierre Barbary (jpb) 
## Dev team :  
## Description :
## Ce Module permet :
##   au niveau du site :
##    - d'envoyer automatiquement un tweet quand un article est publié. 
##      Ce tweet contient le titre et l'intro de l'article dans la limite des 140 caractères dont un linkback vers le site.
##
###################################################################################
!!!Attention !!! ceci est une version beta : seule la publication automatique des articles est implémentée et testée

I. Notes techniques :

- ce modules utilise la librairie php twitteroauth de 
    /*
     * Abraham Williams (abraham@abrah.am) http://abrah.am
     *
     * The first PHP Library to support OAuth for Twitter's REST API.
     */

- requis :
    PHP 5.2.x (non testé dans les versions inférieur), cURL, OpenSSL, PHP sessions activées.

- implémentation dans le core NPDS (déjà réalisée dans REvolution 11)
    admin/stories.php
      ligne 537 :
       // RÈseaux sociaux
          if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
          if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
       // RÈseaux sociaux
      ligne 756 :
       // RÈseaux sociaux
          if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
          if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
       // RÈseaux sociaux
    index.php
      ligne 83 :
       // RÈseaux sociaux
          if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
          if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
       // RÈseaux sociaux

- to do : 
    implémentation des posts automatique...
    pas testé avec un site en iso ....
    interface utilisateur 
    
- NB : Don't edit or move any file ... Only if you known what you do ...

Have fun cui cui cui ...

###################################################################################

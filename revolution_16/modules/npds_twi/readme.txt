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
##    - d'envoyer automatiquement un tweet quand un article est publi�. 
##      Ce tweet contient le titre et l'intro de l'article dans la limite des 140 caract�res dont un linkback vers le site.
##
###################################################################################
!!!Attention !!! ceci est une version beta : seule la publication automatique des articles est impl�ment�e et test�e

I. Notes techniques :

- ce modules utilise la librairie php twitteroauth de 
    /*
     * Abraham Williams (abraham@abrah.am) http://abrah.am
     *
     * The first PHP Library to support OAuth for Twitter's REST API.
     */

- requis :
    PHP 5.2.x (non test� dans les versions inf�rieur), cURL, OpenSSL, PHP sessions activ�es.

- impl�mentation dans le core NPDS (d�j� r�alis�e dans REvolution 11)
    admin/stories.php
      ligne 537 :
       // R�seaux sociaux
          if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
          if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
       // R�seaux sociaux
      ligne 756 :
       // R�seaux sociaux
          if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
          if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
       // R�seaux sociaux
    index.php
      ligne 83 :
       // R�seaux sociaux
          if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
          if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
       // R�seaux sociaux

- to do : 
    impl�mentation des posts automatique...
    pas test� avec un site en iso ....
    interface utilisateur 
    
- NB : Don't edit or move any file ... Only if you known what you do ...

Have fun cui cui cui ...

###################################################################################

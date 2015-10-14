-----------------
| NPDS Hub-Blog |
-----------------
Le webmestre de ce site viens de vous autoriser &#xE0; disposer d'un MiniSite.

NPDS vous propose donc un espace personnel qui peut servir :
 - au stockage de fichiers (format d&#xE9;fini par le webmestre / par exemple : Word, Excel, ...),
 - &#xE0; la r&#xE9;alisation de mini-site web en html, 
 - &#xE0; la r&#xE9;alisation de mini-site web utilisant des fonctions de NPDS.

                             --------- :: ---------

------------------
| MINI SITE HTML |
------------------
Pour g&#xE9;rer votre MiniSite :
 - rendez-vous dans la gestion de compte sur ce site et cliquez sur l'option "G&#xE9;rer votre MiniSite"
 - Un gestionnaire de fichier s'ouvre dans une fen&#xEA;tre compl&#xE9;mentaire et vous permet :
   - de t&#xE9;l&#xE9;charger dans votre espace des fichiers issus de votre poste de travail,
   - de supprimer des fichiers,
   - de visualiser les documents ainsi stock&#xE9;s.

Pour r&#xE9;aliser un MiniSite HTML :
--------------------------------
 - la premi&#xE8;re page de votre site doit obligatoirement s'appeler : index.html
 - cette page peut appeler d'autres pages via des liens

 => Les liens et images qui ne sont pas pr&#xE9;fix&#xE9;s par HTTP:// seront consid&#xE9;r&#xE9;s comme internes &#xE0; votre MiniSite.

 => Attention : certaines fonctions sont interdites (javascript, DHTML ...) mais
    certaines fonctions de NPDS sont disponibles : Essayez d'incorporer dans votre page index.html : Bonjour, !whoim! et nous sommes le : !date!

    ==> Le META-LANG de NPDS est actif dans votre Minisite donc :
        - pour incorporer un header (ent&#xEA;te de page) identique pour toute vos pages (un look identique par exemple) :
          - !l_header! / le nom du fichier devant-&#xEA;tre : header.html

        - pour incorporer un footer (pied de page) identique pour toute vos pages (un look identique par exemple) :
          - !l_footer! / le nom du fichier devant-&#xEA;tre : footer.html

        - mais aussi de nombreuses autres possibilit&#xE9; (CF doc en ligne de l'&#xE9;diteur HTML)

                             --------- :: ---------

-------------------------
| MINI SITE HTML + BLOG |
-------------------------
NPDS vous offre une fonction encore plus puissante pour votre MiniSite : un syst&#xE8;me de publication d'articles (un BLOG)

Pour activer cette fonction : rien de plus simple :
 - &#xE0; l'endroit ou vous souhaitez que le BLOG se mette en place saisissez les META-MOTs comme indiqu&#xE9; :
   !blog_page!5
   !blog!
   => soit 5 articles par page par exemple

   ou bien seulement :
   !blog!
   => par d&#xE9;faut 4 articles par page

NPDS fera le reste !

---------------------------------------------------------------------------------------------------
Vous pouvez voir le r&#xE9;sultat (et communiquer cette adresse &#xE0; votre entourage) via le lien suivant :
=> http://www.lesite.org/minisite.php?op=votre-identifiant-NPDS

 Par exemple => http://www.npds.org/minisite.php?op=developpeur
---------------------------------------------------------------------------------------------------

-----------
Attention : MiniSite ne g&#xE8;re pas les sous-r&#xE9;pertoires !
-----------

                             --------- :: ---------

---------------------
E X T E N S I O N S :
---------------------

---------
LES CSS :
---------
Pour am&#xE9;liorer le rendu graphique de votre BLOG, l'utilisation d'une CSS est optimum. NPDS &#xE0; besoin de 4 tags pour ce faire :
 .BLOG_TITRE
 .BLOG_NEW
 .BLOG_TEXTBOX
 A.BLOG_LIEN pour les liens

s'ils existent dans votre CSS (qui peut se trouver dans header.html par exemple), NPDS les utilisera !

---------------------------
META-MOTs COMPLEMENTAIRES :
---------------------------
 !l_compteur!     / permet de g&#xE9;rer un compteur du nombre de pages affich&#xE9;es (le fichier de ce compteur se trouve dans votre espace personnel)
 !l_gestion!      / permet d'ins&#xE9;rer un lien permettant d'appeler le gestionnaire de MiniSite
 !l_blog_ajouter! / SI vous avez activ&#xE9; le BLOG, permet d'ins&#xE9;rer un lien pour ajouter une New
 !l_new_pages!    / affiche les liens vers les pages de News ( 1 2 ... ) / ce Meta-mot peut se trouver aussi dans news-tpl.html

------------------
MODELE pour BLOG :
------------------
Pour am&#xE9;liorer le rendu graphique de vos News dans le BLOG, vous pouvez d&#xE9;cider ne ne pas utiliser la pr&#xE9;sentation propos&#xE9;e en standard :
 - le mod&#xE8;le de pr&#xE9;sentation doit obligatoirement s'appeler : news-tpl.html
 - il doit contenir du code HTML valide et 5 META-MOTs particuliers :
   !l_new_date!   / affiche la date de la New
   !l_new_titre!  / affiche le titre de la New
   !l_new_pages!  / affiche les liens vers les pages de News ( 1 2 ... ) / ce Meta-mot peut se trouver aussi dans index.html
   !l_new_texte!  / affiche le texte de la New
   !l_new_modif!  / affiche le lien permettant de modifier la New
   !l_new_suppr!  / affiche le lien permettant de supprimer la New

----------------
META-MOT FLASH :
----------------
Pour am&#xE9;liorer encore vos MiniSites, NPDS autorise l'utilisation du FLASH mais via un meta-mot particulier :
 !flash!fichier,largeur,hauteur
  - ou fichier est le nom de votre (vos) fichier(s) flash
  - ou largeur est la largeur en pixel de votre animation flash
  - ou hauteur est la hauteur en pixel de votre animation flash
 => CE META-MOT doit OBLIGATOIREMENT SE TERMINER par un ESPACE derri&#xE8;re hauteur !

                             --------- :: ---------

-----------------
E X E M P L E S :
-----------------

index.html :
 !l_header!
 ceci est le corps de ma page en HTML
 ceci est une image locale : <img src=photo.gif>
 ceci est le lien sur ma page num&#xE9;ro 2 : <a href=page2.html>La suite</a>
 !l_footer!

header.html :
 <HTML>
 <head>
 <style type="text/css">
 BODY {
   background: #efefef;
   font-family: Tahoma, Verdana, sans-serif;
   font-size: 11px;
   color: #000000;
   font-weight: normal;
   margin: 0px;
   padding-bottom: 0px;
   padding-left: 0px;
   padding-right: 0px;
   padding-top: 0px;font-weight: normal;
 }
 </style>
 </head>
 <BODY>
  Bonjour, !whoim! et nous sommes le : !date!<br>
  <hr>

footer.html :
 <BR>
 <font color=red>Pied de page de mon site</font>
 </BODY>
 </HTML>


-----------------------------
exemple de CSS pour un BLOG :
-----------------------------

<style type="text/css">
BODY {
background: #efefef;
font-family: Tahoma, Verdana, sans-serif;
font-size: 11px;
color: #000000;
font-weight: normal;
margin: 0px;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;font-weight: normal;
}

A, A:VISITED, A:LINK, A:HOOVER, A:ACTIVE {
        color: #181818;
        font-family: Tahoma, Verdana, sans-serif;
        font-size: 11px;
        text-decoration: bold;
}

.BLOG_TITRE {
        background-color: #BBC9E7;
        color: #282828;
        font-family: Tahoma, Verdana, sans-serif;
        font-size: 13px;
        font-weight: bold;
        text-decoration: none;
}

.BLOG_NEW {
        background-color: #AAC9E7;
        color: #282828;
        font-family: Tahoma, Verdana, sans-serif;
        font-size: 11px;
        text-decoration: none;
}
.BLOG_TEXTBOX {
        font-family: Tahoma, Verdana, sans-serif;
        background-color: #FFFFFF;
        border-bottom: #000000 1px solid;
        border-left: #000000 1px solid;
        border-right: #000000 1px solid;
        border-top: #000000 1px solid;
        color: #45445B;
        font-size: 11px;
        width: 90%;
}
A.BLOG_LIEN, A.BLOG_LIEN:VISITED, A.BLOG_LIEN:LINK, A.BLOG_LIEN:HOOVER, A.BLOG_LIEN:ACTIVE {
        color: #282828;
        font-family: Tahoma, Verdana, sans-serif;
        font-size: 11px;
        text-decoration: bold;
}
</style>
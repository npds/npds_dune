-----------------
| NPDS Hub-Blog |
-----------------
Le webmestre de ce site viens de vous autoriser &#xE0; disposer d'un MiniSite.

NPDS vous propose donc un espace personnel qui peut servir :
 - au stockage de fichiers (format d&#xE9;fini par le webmestre / par exemple : Word, Excel, ...),
 - au stockage de votre BLOG personnel
                             --------- :: ---------
-------------------------
| MINI SITE HTML + BLOG |
-------------------------
NPDS vous offre une fonction encore plus puissante pour votre MiniSite : un syst&#xE8;me de publication d'articles Personnel (un BLOG)
Pour g&#xE9;rer votre MiniSite :
 - rendez-vous dans la gestion de compte sur ce site et cliquez sur l'option "G&#xE9;rer votre MiniSite"
 - Un gestionnaire de fichier s'ouvre dans une fen&#xEA;tre compl&#xE9;mentaire et vous permet :
   - de t&#xE9;l&#xE9;charger dans votre espace des fichiers issus de votre poste de travail,
   - de supprimer des fichiers,
   - de visualiser les documents ainsi stock&#xE9;s.

--------------------------
Les fichiers de votre BLOG
--------------------------
 - la page principale de votre BLOG doit obligatoirement s'appeler : index.html
    - elle ne doit pas contenir de javascript, DHTML ...
    - elle peut contenir des meta-mots : par exemple !whoim! ou !date! ou ...

 - la CSS de votre BLOG doit obligatoirement se trouver dans le fichier style.css

 - la page header (facultative) de votre BLOG doit obligatoirement s'appeler : header.html 
    - pour incorporer ce header (ent&#xEA;te de page) - mettre !l_header! dans la page index.html

 - la page footer (facultative) de votre BLOG doit obligatoirement s'appeler : footer.html 
    - pour incorporer un footer (pied de page) - mettre !l_footer! dans la page index.html

 - compteur.txt contient le compteur de visite 

 - news.txt contient vos articles
 - news.txt.bak contient une sauvegarde de vos articles avant la derni&#xEA;re modification

-----------
Attention : MiniSite ne g&#xE8;re pas les sous-r&#xE9;pertoires !
-----------
                             --------- :: ---------

---------------------------------------------------------------------------------------------------
Vous pouvez voir le r&#xE9;sultat (et communiquer cette adresse &#xE0; votre entourage) via le lien suivant :
=> http://www.lesite.org/minisite.php?op=votre-identifiant-NPDS

 Par exemple => http://www.npds.org/minisite.php?op=developpeur
---------------------------------------------------------------------------------------------------

---------------------
E X T E N S I O N S :
---------------------
---------
LES CSS :
---------
Pour am&#xE9;liorer le rendu graphique de votre BLOG, l'utilisation d'une CSS est optimum. NPDS &#xE0; besoin de 5 tags pour ce faire (fichier style.css) :
 .blog_titre
 .blog_new
 .blog_textbox
 .blog_bouton
 a.blog_lien pour les liens

---------------------------
META-MOTs COMPLEMENTAIRES :
---------------------------
 !blog!           / permet d'ins&#xE9;rer le BLOG
 !l_compteur!     / permet de g&#xE9;rer un compteur du nombre de pages affich&#xE9;es 
 !l_blog_ajouter! / permet d'ins&#xE9;rer un lien pour ajouter une New
 !blog_page!      / permet de modifier la valeur du nombre de news affich&#xE9;e (exemple : !blog_page!5)

 !l_new_date!   / affiche la date de la New
 !l_new_titre!  / affiche le titre de la New
 !l_new_pages!  / affiche les liens vers les pages de News ( 1 2 ... )
 !l_new_texte!  / affiche le texte de la New
 !l_new_modif!  / affiche le lien permettant de modifier la New
 !l_new_suppr!  / affiche le lien permettant de supprimer la New


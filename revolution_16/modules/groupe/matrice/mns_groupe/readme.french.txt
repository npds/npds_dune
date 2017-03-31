-----------------
| NPDS Hub-Blog |
-----------------
Le webmestre de ce site vient de vous autoriser à disposer d'un MiniSite.

NPDS vous propose donc un espace personnel qui peut servir :
 - au stockage de fichiers (format défini par le webmestre / par exemple : Word, Excel, ...),
 - au stockage de votre BLOG personnel
                             --------- :: ---------
-------------------------
| MINI SITE HTML + BLOG |
-------------------------
NPDS vous offre une fonction encore plus puissante pour votre MiniSite : un système de publication d'articles personnels (un BLOG)
Pour gérer votre MiniSite :
 - rendez-vous dans la gestion de votre compte et cliquez sur l'option "Gérer votre MiniSite"
 - Un gestionnaire de fichier s'ouvrira dans une fenêtre complémentaire et vous permettra :
   - de télécharger dans votre espace des fichiers issus de votre poste de travail,
   - de supprimer des fichiers,
   - de visualiser les documents ainsi stockés.

--------------------------
Les fichiers de votre BLOG
--------------------------
 - la page principale de votre BLOG doit obligatoirement s'appeler : index.html
    - elle ne doit pas contenir de javascript, DHTML ...
    - elle peut contenir des meta-mots : par exemple !whoim! ou !date! ou ...

 - la CSS de votre BLOG doit obligatoirement se trouver dans le fichier style.css

 - la page header (facultative) de votre BLOG doit obligatoirement s'appeler : header.html 
    - pour incorporer ce header (entête de page) - mettre !l_header! dans la page index.html

 - la page footer (facultative) de votre BLOG doit obligatoirement s'appeler : footer.html 
    - pour incorporer un footer (pied de page) - mettre !l_footer! dans la page index.html

 - compteur.txt contient le compteur de visites 

 - news.txt contient vos articles
 - news.txt.bak contient une sauvegarde de vos articles avant la dernière modification

-----------
Attention : MiniSite ne gère pas les sous-répertoires !
-----------
                             --------- :: ---------

---------------------------------------------------------------------------------------------------
Vous pouvez voir le résultat (et communiquer cette adresse à votre entourage) via le lien suivant :
=> http://www.lesite.org/minisite.php?op=votre-identifiant-NPDS

 Par exemple => http://www.npds.org/minisite.php?op=developpeur
---------------------------------------------------------------------------------------------------

---------------------
E X T E N S I O N S :
---------------------
---------
LES CSS :
---------
Pour améliorer le rendu graphique de votre BLOG, l'utilisation d'une CSS est optimum. NPDS à besoin de 5 tags pour ce faire (fichier style.css) :
 .blog_titre
 .blog_new
 .blog_textbox
 .blog_bouton
 a.blog_lien pour les liens

---------------------------
META-MOTs COMPLEMENTAIRES :
---------------------------
 !blog!           / permet d'insérer le BLOG
 !l_compteur!     / permet de gérer un compteur du nombre de pages affichées 
 !l_blog_ajouter! / permet d'insérer un lien pour ajouter une New
 !blog_page!      / permet de modifier la valeur du nombre de news affichée (exemple : !blog_page!5)

 !l_new_date!   / affiche la date de la New
 !l_new_titre!  / affiche le titre de la New
 !l_new_pages!  / affiche les liens vers les pages de News ( 1 2 ... )
 !l_new_texte!  / affiche le texte de la New
 !l_new_modif!  / affiche le lien permettant de modifier la New
 !l_new_suppr!  / affiche le lien permettant de supprimer la New
 !avatar!       / affiche l'avatar du membre ou du groupe
 !id_mns!       / affiche le pseudo ou groupe/x


-----------------
| NPDS Hub-Blog |
-----------------
The webmaster of this site allow you to just have a Minisite.

NPDS thus offers a personal space that can be used to:
  - Store files (format defined by the webmaster / eg Word, Excel, ...),
  - Store your personal blog

                             --------- :: ---------
-------------------------
| MINI SITE HTML + BLOG |
-------------------------
NPDS offers an even more powerful feature for your Minisite: publication of personal news (a blog)
To manage your Minisite:
  - Go to Account Management on this site and click the "Manage Your Minisite"
  - A file manager window opens in a complementary and allows you to:
    - Download in your 'space' files from your workstation,
    - Delete files,
    - View the documents stored.

--------------------------
The files on your BLOG
--------------------------
 - The main page of your blog must be called index.html
     - It does not contain javascript, DHTML ...
     - It may contain meta-words: for example !Whoim! or !date! or ...

 - The CSS of your blog must be in the file style.css

 - Page header (optional) of your blog must be called: header.html
     - To incorporate the header (page header) - set !L_header! the page index.html

 - Page footer (optional) of your blog must be called: footer.html
     - To incorporate a footer (footer) - set !L_footer! the page index.html

 - Compteur.txt contains the visit counter

 - News.txt contains your articles
 - News.txt.bak contains a backup of your items before the last change

-----------
Warning: Minisite does not support subdirectories!
-----------
                             --------- :: ---------

---------------------------------------------------------------------------------------------------
You can see the result (and communicate this address to your friends) via the following link:
=> Http://www.lesite.org/minisite.php?op=votre-identifiant-NPDS

  For example => http://www.npds.org/minisite.php?op=developpeur
---------------------------------------------------------------------------------------------------

---------------------
E X T E N S I O N S :
---------------------
---------
CSS :
---------
To improve the graphical rendering of your blog, the use of CSS is optimum. NPDS need only 5 tags to do so (style.css):
 .blog_titre
 .blog_new
 .blog_textbox
 .blog_bouton
 a.blog_lien for links

---------------------------
ADDITIONALS META-WORD :
---------------------------
 !blog!           / insert the BLOG
 !l_compteur!     / manages a counter of the number of pages displayed
 !l_blog_ajouter! / to insert a link to add a New
 !blog_page!      / changes the value of the number of news displayed (eg : !blog_page!5)

 !l_new_date!   / displays the date of the New
 !l_new_titre!  / displays the title of the New
 !l_new_pages!  / shows the links to the pages of News ( 1 2 ... )
 !l_new_texte!  / displays the text of the New
 !l_new_modif!  / displays the link to change New
 !l_new_suppr!  / displays the link to delete New
 !avatar!       / displays member or group 's avatar
 !id_mns!       / displays nickname or groupe/x


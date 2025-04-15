<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2025 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
   $Titlesitename='NPDS';
   if (!function_exists("Mysql_Connexion"))
      include "mainfile.php";
   if (file_exists("meta/meta.php"))
      include ("meta/meta.php");
   echo '
   <link id="bsth" rel="stylesheet" href="/lib/bootstrap/dist/css/bootstrap.min.css" />
   </head>
   <body>
      <div class="contenair-fluid mt-5">
         <div class= "card mx-auto p-3" style="width:380px; text-align:center">
            <span style="font-size: 72px;">🚫</span>
            <span class="text-danger h3 mb-3" style="">
               Acc&egrave;s refus&eacute; ! <br />
               Access denied ! <br />
               Zugriff verweigert ! <br />
               Acceso denegado ! <br />
               &#x901A;&#x5165;&#x88AB;&#x5426;&#x8BA4; ! <br />
            </span>
            <hr />
            <div>
               <span class="text-body-secondary">NPDS - Portal System</span>
               <img width="48px" class="adm_img ms-2" src="/images/admin/message_npds.png" alt="icon_npds">
            </div>
         </div>
      </div>
   </body>
</html>';
   die();
?>
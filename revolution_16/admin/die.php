<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2018 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
   $Titlesitename='NPDS';
   if (file_exists("meta/meta.php"))
   include ("meta/meta.php");
   echo '
   </head>
   <body>
      <br />
      <br />
      <p style="text-align:center">
         <span style="font-size: 24px; font-family: Courier New, Courier, Liberation Mono, monospace; font-weight: bold; color: red;">
            Acc&egrave;s Refus&eacute; ! <br />
            Access Denied ! <br />
            Zugriff verweigert ! <br />
            &#x901A;&#x5165;&#x88AB;&#x5426;&#x8BA4; ! <br />
            Acceso denegado ! <br />
         </span>
         <br />
         <br />
         <span style="font-size: 18px; font-family: Courier New, Courier, Liberation Mono, monospace; font-weight: bold; color: black;">
            NPDS - Portal System
         </span>
      </p>
   </body>
</html>';
   die();
?>
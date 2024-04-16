<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BLOC-NOTES engine for NPDS - Philippe Brunier & Arnaud Latourrette   */
/*                                                                      */
/* NPDS Copyright (c) 2002-2021 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

/* Attention le cas échéant au prefix de cette table si vous avez plusieurs NPDS dans la même DB */

CREATE TABLE blocnotes (
  bnid text COLLATE utf8mb4_unicode_ci NOT NULL,
  texte text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY  (bnid(32))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO metalang VALUES ('!blocnote!', 'function MM_blocnote($arg) {\r\n      global $REQUEST_URI;\r\n      if (!stristr($REQUEST_URI,"admin.php")) {\r\n         return(@oneblock($arg,"RB"));\r\n      } else {\r\n         return("");\r\n      }\r\n}', 'meta', '-', NULL, '[french]Fabrique un blocnote contextuel en lieu et place du meta-mot / syntaxe : !blocnote!ID - ID = Id du bloc de droite dans le gestionnaire de bloc de NPDS[/french]', '0');
?>
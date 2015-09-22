<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BLOC-NOTES engine for NPDS - Philippe Brunier & Arnaud Latourrette   */
/*                                                                      */
/* NPDS Copyright (c) 2002-2009 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/* Attention le cas &chéant au prefix de cette table si vous avez plusieure NPDS dans la même DB */

CREATE TABLE blocnotes (
  bnid tinytext NOT NULL,
  texte text,
  PRIMARY KEY  (bnid(32))
) type=MyISAM;

INSERT INTO metalang VALUES ('!blocnote!', 'function MM_blocnote($arg) {\r\n      global $REQUEST_URI;\r\n      if (!stristr($REQUEST_URI,"admin.php")) {\r\n         return(@oneblock($arg,"RB"));\r\n      } else {\r\n         return("");\r\n      }\r\n}', 'meta', '-', NULL, '[french]Fabrique un blocnote contextuel en lieu et place du meta-mot / syntaxe : !blocnote!ID - ID = Id du bloc de droite dans le gestionnaire de bloc de NPDS[/french]', '0');
?>
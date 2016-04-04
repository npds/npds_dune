<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Toggle_Div Class Manipulation                                        */
/* Copyright (c) Ade (www.ade21.net) 2004 - Mod 2011 by Dev             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
//
// include_once ("lib/togglediv.class.php"); # description de l'objet et de ses methodes
// ToggleDiv = new ToggleDiv(nb de sections total); # Création des sections (y compris les sous-sections)
// ToggleDiv->All(); # Affiche Developper tout | Regrouper Tout de toutes les sections "ToggleDiv"
// ToggleDiv->Img(); # Affiche l'image qui permet de developper.
// ToggleDiv->Begin(); # Specifie le debut de la section associée au Img() précédent.
// ToggleDiv->End(); # Specifie la fin de la section associée au Img() précédent.
// ToggleDiv->Cookies_all(); # Appel la mise à jour de ToggleDiv en utilisant les cookies définis

// - vous pouvez imbriquer une autre sous-section exclusivement entre un Begin() et End().
// - Les images peuvent être surchargées lors de la créations du ToggleDiv.

echo "\n<script type=\"text/javascript\" src=\"lib/cookies.js\"></script>\n";

class ToggleDiv {
   var $id;
   var $count;
   var $max;
   var $imgtmpD;
   var $imgtmpR;

   function ToggleDiv($max) {
      // more stable UniqID function - ALSO cookies enable !
      global $toggleDiv_max;
      global $REQUEST_URI;
      if (!$toggleDiv_max[$max]) {
         $toggleDiv_max[$max]=1;
      } else {
         $toggleDiv_max[$max]=$toggleDiv_max[$max]+1;
      }
      $this->id  = $max.$toggleDiv_max[$max].md5($REQUEST_URI);

      $this->count = 0;
      $this->max = $max;
      if ($ibid=theme_image("replier.gif")) {$this->imgtmpR=$ibid;} else {$this->imgtmpR="images/replier.gif";}
      if ($ibid=theme_image("deplier.gif")) {$this->imgtmpD=$ibid;} else {$this->imgtmpD="images/deplier.gif";}

      echo "<style type=\"text/css\">\n";
      echo ".trigger".$this->id."0 { cursor: default; cursor: pointer; vertical-align: middle;}\n";
      for ($i = 1; $i<= $max; $i++) {
         echo ".trigger$this->id$i { cursor: default; cursor: pointer; vertical-align: middle;}\n";
         echo ".toggle$this->id$i  { display: none; }\n";
      }
      echo "</style>\n";

      echo "<script type=\"text/javascript\">\n";
      echo "//<![CDATA[\n";
      echo "   var image_open = new Image();\n";
      echo "   image_open.src = \"".$this->imgtmpD."\";\n";
      echo "   var image_closed = new Image();\n";
      echo "   image_closed.src = \"".$this->imgtmpR."\";\n";

      echo "   function toggleall$this->id(type) {\n";
      echo "      if (document.all) {\n";
      for ($i=1; $i<=$max; $i++) {
         echo "      document.all[\"toggle_scr$this->id$i\"].style.display = type;\n";
         echo "      if (type==\"none\") {\n";
         echo "         document.all[\"trigger_scr$this->id$i\"].src = image_closed.src;\n";
         echo "         setCookie('toggle_scr$this->id$i', 'none', '');\n";
         echo "      } else {\n";
         echo "         document.all[\"trigger_scr$this->id$i\"].src = image_open.src;\n";
         echo "         setCookie('toggle_scr$this->id$i', 'block', '');\n";
         echo "      }\n";
      }
      echo "      } else {\n";
      for ($i=1; $i<=$max; $i++) {
         echo "      document.getElementById(\"toggle_scr$this->id$i\").style.display = type;\n";
         echo "      if (type==\"none\") {\n";
         echo "         document.getElementById(\"trigger_scr$this->id$i\").src = image_closed.src;\n";
         echo "         setCookie('toggle_scr$this->id$i', 'none', '');\n";
         echo "      } else {\n";
         echo "         document.getElementById(\"trigger_scr$this->id$i\").src = image_open.src;\n";
         echo "         setCookie('toggle_scr$this->id$i', 'block', '');\n";
         echo "      }\n";
      }
      echo "      }\n";
      echo "   }\n";

      echo "   function toggle$this->id(image, section) {\n";
      echo "      if(document.all) {\n";
      echo "        if (document.all[section].style.display == \"block\") {\n";
      echo "           document.all[section].style.display = \"none\";\n";
      echo "           document.all[image].src = image_closed.src;\n";
      echo "           setCookie(section, 'none', '');\n";
      echo "        } else {\n";
      echo "           document.all[section].style.display = \"block\";\n";
      echo "           document.all[image].src = image_open.src;\n";
      echo "           setCookie(section, 'block', '');\n";
      echo "        }\n";
      echo "      } else {\n";
      echo "        if (document.getElementById(section).style.display == \"block\") {\n";
      echo "           document.getElementById(section).style.display = \"none\";\n";
      echo "           document.getElementById(image).src = image_closed.src;\n";
      echo "           setCookie(section, 'none', '');\n";
      echo "        } else {\n";
      echo "           document.getElementById(section).style.display = \"block\";\n";
      echo "           document.getElementById(image).src = image_open.src;\n";
      echo "           setCookie(section, 'block', '');\n";
      echo "        }\n";
      echo "      }\n";
      echo "   }\n";

      echo "   function cookies_toggleall$this->id() {\n";
      echo "     if (document.all) {\n";
      for ($i=1; $i<=$max; $i++) {
         echo "      var tmp = getCookie('toggle_scr$this->id$i');\n";
         echo "      if (tmp) {\n";
         echo "         document.all[\"toggle_scr$this->id$i\"].style.display = tmp;\n";
         echo "         if (document.all[\"toggle_scr$this->id$i\"].style.display == \"block\") {\n";
         echo "            document.all[\"trigger_scr$this->id$i\"].src = image_open.src;\n";
         echo "         } else {\n";
         echo "            document.all[\"trigger_scr$this->id$i\"].src = image_closed.src;\n";
         echo "         }\n";
         echo "      }\n";
      }
      echo "     } else {\n";
      for ($i=1; $i<=$max; $i++) {
         echo "      var tmp = getCookie('toggle_scr$this->id$i');\n";
         echo "      if (tmp) {\n";
         echo "         document.getElementById(\"toggle_scr$this->id$i\").style.display = tmp;\n";
         echo "         if (document.getElementById(\"toggle_scr$this->id$i\").style.display == \"block\") {\n";
         echo "            document.getElementById(\"trigger_scr$this->id$i\").src = image_open.src;\n";
         echo "         } else {\n";
         echo "            document.getElementById(\"trigger_scr$this->id$i\").src = image_closed.src;\n";
         echo "         }\n";
         echo "      }\n";
      }
      echo "     }\n";
      echo "   }\n";
      echo "//]]>\n";
      echo "</script>\n";
   }

   function All() {
       if ($ibid=theme_image("deplier_all.gif")) {$imgtmpDA=$ibid;} else {$imgtmpDA="images/deplier_all.gif";}
       if ($ibid=theme_image("replier_all.gif")) {$imgtmpRA=$ibid;} else {$imgtmpRA="images/replier_all.gif";}
       $ibid="<a href=\"#\" onclick=\"toggleall$this->id('block');\"><img border=\"0\" alt=\"\" class=\"trigger".$this->id."0\" src=\"$imgtmpDA\" />";
       $ibid.=translate("All to develop")."</a>";
       $ibid.="&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
       $ibid.="<a href=\"#\"  onclick=\"toggleall$this->id('none');\"><img border=\"0\" alt=\"\" class=\"trigger".$this->id."0\" src=\"$imgtmpRA\" />";
       $ibid.=translate("All to gather")."</a>\n";
       return($ibid);
   }

   function Img() {
       $this->count++;
       return("<img class=\"trigger$this->id$this->count\" id=\"trigger_scr$this->id$this->count\" src=\"".$this->imgtmpR."\" onclick=\"toggle$this->id('trigger_scr$this->id$this->count', 'toggle_scr$this->id$this->count');\" alt=\"\" />&nbsp;");
   }

   function Begin() {
       return("<div class=\"toggle$this->id$this->count\" id=\"toggle_scr$this->id$this->count\">\n");
   }

   function End() {
       return("</div>\n");
   }

   function Cookies_all() {
       // Call toogle_div cookies update
       $tmp="<script type=\"text/javascript\">\n";
       $tmp.="//<![CDATA[\n";
       $tmp.="cookies_toggleall$this->id();\n";
       $tmp.="//]]>\n";
       $tmp.="</script>\n";
       return ($tmp);
   }
}
?>
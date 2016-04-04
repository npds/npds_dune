<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

global $ModPath, $ModStart, $language, $Default_Theme, $NPDS_Key, $NPDS_Prefix;

if (file_exists("modules/$ModPath/lang/f-manager-$language.php")) {
   include ("modules/$ModPath/lang/f-manager-$language.php");
} else {
   include ("modules/$ModPath/lang/f-manager-english.php");
}
include ("modules/$ModPath/class.navigator.php");

// Gestion Ascii étendue
function extend_ascii($ibid) {
   $tmp=urlencode($ibid);
   $tmp=str_replace("%82","é",$tmp);
   $tmp=str_replace("%85","à",$tmp);
   $tmp=str_replace("%87","ç",$tmp);
   $tmp=str_replace("%88","ê",$tmp);
   $tmp=str_replace("%97","ù",$tmp);
   $tmp=str_replace("%8A","è",$tmp);
   $tmp=urldecode($tmp);
   return ($tmp);
}

// Gestion des fichiers autorisés
function fma_filter($type, $filename, $Extension) {
   $autorise=false;
   $error="";
   if ($type=="f") $filename=removeHack($filename);
   $filename=preg_replace('#[/\\\:\*\?"<>|]#i','', rawurldecode($filename));
   $filename=str_replace("..","",$filename);

   // Liste des extensions autorisées
   $suffix = strtoLower(substr(strrchr( $filename, '.' ), 1 ));
   if (($suffix!="") or ($type=="d")) {
      if ((in_array($suffix,$Extension)) or ($Extension[0]=="*") or $type=="d") {
         // Fichiers interdits en fonction de qui est connecté
         if (fma_autorise($type, $filename)) {
            $autorise=true;
         } else {
            $error=fma_translate("Fichier interdit");
         }
      } else {
         $error=fma_translate("Type de fichier interdit");
      }
   } else {
      $error=fma_translate("Fichier interdit");
   }
   $tab[]=$autorise;
   $tab[]=$error;
   $tab[]=$filename;
   return($tab);
}

// Gestion des autorisations sur les répertoires et les fichiers
function fma_autorise($type, $dir) {
   global $user, $admin, $dirlimit_fma, $ficlimit_fma, $access_fma;
   global $dir_minuscptr, $fic_minuscptr;

   $autorise_arbo=false;

   if ($type=="a") {
      $autorise_arbo=$access_fma;
   }
   if ($type=="d") {
      if (is_array($dirlimit_fma)) {
         if (array_key_exists($dir,$dirlimit_fma))
            $autorise_arbo=$dirlimit_fma[$dir];
      }
   }
   if ($type=="f") {
      if (is_array($ficlimit_fma)) {
         if (array_key_exists($dir,$ficlimit_fma))
            $autorise_arbo=$ficlimit_fma[$dir];
      }
   }

   if ($autorise_arbo) {
      $auto_dir="";
      if (($autorise_arbo=="membre") and ($user)) {
         $auto_dir=true;
      } elseif (($autorise_arbo=="anonyme") and (!$user)) {
         $auto_dir=true;
      } elseif (($autorise_arbo=="admin") and ($admin)) {
         $auto_dir=true;
      } elseif (($autorise_arbo!="membre") and ($autorise_arbo!="anonyme") and ($autorise_arbo!="admin") and ($user)) {
         $tab_groupe=valid_group($user);
         if ($tab_groupe) {
            foreach($tab_groupe as $groupevalue) {
               $tab_auto=explode(",",$autorise_arbo);
               while (list(,$gp)=each($tab_auto)) {
                  if ($gp>0) {
                     if ($groupevalue==$gp) {
                        $auto_dir=true;
                        break;
                     }
                  } else {
                     $auto_dir=true;
                     if (-$groupevalue==$gp) {
                        $auto_dir=false;
                        break;
                     }
                  }
               }
               if ($auto_dir) break;
            }
         }
      }
   } else {
      $auto_dir=true;
   }
   if ($auto_dir!=true) {
      if ($type=="d")
         $dir_minuscptr++;
      if ($type=="f")
         $fic_minuscptr++;
   }
   return($auto_dir);
}

function chmod_pres($ibid, $champ) {
   $sel="";
   if ($ibid[0]==400) $sel="selected=\"selected\""; else $sel="";
   $chmod="<option name=\"$champ\" value=\"400\" $sel> 400 (r--------)</option>";
   if ($ibid[0]==444) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"444\" $sel> 444 (r-x------)</option>";
   if ($ibid[0]==500) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"500\" $sel> 500 (r--------)</option>";
   if ($ibid[0]==544) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"544\" $sel> 544 (r-xr--r--)</option>";
   if ($ibid[0]==600) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"600\" $sel> 600 (rw-------)</option>";
   if ($ibid[0]==644) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"644\" $sel> 644 (rw-r--r--)</option>";
   if ($ibid[0]==655) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"655\" $sel> 655 (rw-r-xr-x)</option>";
   if ($ibid[0]==666) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"666\" $sel> 666 (rw-rw-rw-)</option>";
   if ($ibid[0]==700) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"700\" $sel> 700 (rwx------)</option>";
   if ($ibid[0]==744) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"744\" $sel> 744 (rwxr--r--)</option>";
   if ($ibid[0]==755) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"755\" $sel> 755 (rwxr-xr-x)</option>";
   if ($ibid[0]==766) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"766\" $sel> 766 (rwxrw-rw-)</option>";
   if ($ibid[0]==777) $sel="selected=\"selected\""; else $sel="";
   $chmod.="<option name=\"$champ\" value=\"777\" $sel> 777 (rwxrwxrwx)</option>";
   $chmod.="</select>";
   return ($chmod);
}

// Lancement sur un Repertoire en fonction d'un fichier de conf particulier
if ($FmaRep) {
   if (filtre_module($FmaRep)) {
      // Si je ne trouve pas de fichier - est-ce que l'utilisateur fait partie d'un groupe ?
      if (!file_exists("modules/$ModPath/users/".strtolower($FmaRep).".conf.php")) {
         $tab_groupe=valid_group($user);
         if ($tab_groupe) {
            // si j'ai au moins un groupe est ce que celui-ci dispose d'un fichier de configuration ?  - je m'arrête au premier groupe !
            while (list(,$gp)=each($tab_groupe)) {
               $groupename=Q_select("select groupe_name from ".$NPDS_Prefix."groupes where groupe_id='$gp' ORDER BY `groupe_id` ASC",3600);
               if (file_exists("modules/$ModPath/users/".$groupename[0]['groupe_name'].".conf.php")) {
                  $FmaRep=$groupename[0]['groupe_name'];
                  break;
               }
            }
         }
      }
      if (file_exists("modules/$ModPath/users/".strtolower($FmaRep).".conf.php")) {
         // Est ce que je doit récupérer le theme si un utilisateur est connecté ?
         if (isset($user)) {
             include("themes/list.php");
             $themelist = explode(" ", $themelist);
             $pos=array_search($cookie[9],$themelist);
             if ($pos!==false)
               $Default_Theme=$themelist[$pos];
         }
         include("modules/$ModPath/users/".strtolower($FmaRep).".conf.php");
         if (fma_autorise("a", "")) {
            $theme_fma=$themeG_fma;
            $fic_minuscptr=0;
            $dir_minuscptr=0;
         } else {
            Access_Error();
         }
      } else {
         Access_Error();
      }
   } else {
      Access_Error();
   }
} else {
   Access_Error();
}

if (isset($browse)) {
   $ibid=rawurldecode(decrypt($browse));
   if (substr(@php_uname(),0,7) == "Windows") {
      $ibid=preg_replace('#[\*\?"<>|]#i','', $ibid);
   } else {
      $ibid=preg_replace('#[\:\*\?"<>|]#i','', $ibid);
   }
   $ibid=str_replace("..","",$ibid);
   // contraint à rester dans la zone de repertoire définie (CHROOT)
   $base=$basedir_fma.substr($ibid,strlen($basedir_fma));
} else {
   $browse="";
   $base=$basedir_fma;
}

// initialisation de la classe
$obj= new Navigator();
$obj->Extension=explode(" ",$extension_fma);

// traitements
$rename_dir=""; $remove_dir=""; $chmod_dir="";
$remove_file=""; $move_file=""; $rename_file=""; $chmod_file=""; $edit_file="";

if (substr(@php_uname(),0,7)=="Windows") {
   $log_dir=str_replace($basedir_fma,"",$base);
} else {
   $log_dir=str_replace("\\","/",str_replace($basedir_fma,"",$base));
}

include_once("modules/upload/upload.conf.php");

settype($op,'string');
switch ($op) {
   case "upload":
      if ($ficcmd_fma[0]) {
         if ($userfile!="none") {
            global $language;
            include_once("modules/upload/lang/upload.lang-$language.php");
            include_once("modules/upload/clsUpload.php");
            $upload = new Upload();
            $filename = trim($upload->getFileName("userfile"));
            if ($filename) {
               $upload->maxupload_size=$max_size;
               $auto=fma_filter("f", $filename, $obj->Extension);
               if ($auto[0]) {
                  if (!$upload->saveAs($auto[2], $base."/", "userfile", true)) {
                     $Err=$upload->errors;
                  } else {
                     Ecr_Log("security","Upload File", $log_dir."/".$filename." IP=>".getip());
                  }
               } else {
                  $Err=$auto[1];
               }
            }
         }
      }
      break;

   // Répertoires
   case "createdir":
      if ($dircmd_fma[0]) {
         $auto=fma_filter("d", $userdir, $obj->Extension);
         if ($auto[0]) {
            if (!$obj->Create("d",$base."/".$auto[2])) {
               $Err=$obj->Errors;
            } else {
               Ecr_Log("security","Create Directory", $log_dir."/".$userdir." IP=>".getip());
               $fp = fopen($base."/".$auto[2]."/.htaccess", 'w');
               fputs($fp, "Deny from All");
               fclose($fp);
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "renamedir":
      if ($dircmd_fma[1]) {
         $auto=fma_filter("d", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<span class="text-muted"><i class="fa fa-folder fa-2x"></i>&nbsp;</span>'.fma_translate("Renommer un répertoire");
               $rename_dir ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="renamedir-save" />';
               $rename_dir.=extend_ascii($auto[2])."<b> >> </b>";
               $rename_dir.='<input class="form-control" type="text" name="renamefile" value="'.extend_ascii($auto[2]).'" />
               &nbsp;<input class="btn btn-primary" type="submit" name="ok" value="'.fma_translate("Ok").'" />
               </form>';
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "renamedir-save":
      if ($dircmd_fma[1]) {
         // origine
         $auto=fma_filter("d", $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $autoD=fma_filter("d", $renamefile, $obj->Extension);
            if ($autoD[0]) {
               $auto[3]=decrypt($browse);
               if (!$obj->Rename($auto[3]."/".$auto[2],$auto[3]."/".$autoD[2])) {
                  $Err=$obj->Errors;
               } else {
                  Ecr_Log("security","Rename Directory", $log_dir."/".$autoD[2]." IP=>".getip());
               }
            } else {
               $Err=$autoD[1];
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;

   case "removedir":
      if ($dircmd_fma[2]) {
         $auto=fma_filter("d", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd=fma_translate("Supprimer un répertoire");
               $remove_dir ="<form method=\"post\" action=\"modules.php\">";
               $remove_dir.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $remove_dir.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $remove_dir.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $remove_dir.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $remove_dir.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $remove_dir.="<input type=\"hidden\" name=\"op\" value=\"removedir-save\" />";
               $remove_dir.=fma_translate("Confirmez-vous la suppression de")." ".extend_ascii($auto[2])." : ";
               $remove_dir.="&nbsp;<input class=\"btn btn-danger\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "removedir-save":
      if ($dircmd_fma[2]) {
         $auto=fma_filter("d", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            @unlink($auto[3]."/".$auto[2]."/.htaccess");
            @unlink($auto[3]."/".$auto[2]."/pic-manager.txt");
            if (!$obj->RemoveDir($auto[3]."/".$auto[2])) {
               $Err=$obj->Errors;
            } else {
               Ecr_Log("security","Delete Directory", $log_dir."/".$auto[2]." IP=>".getip());
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;

   case "chmoddir":
      if ($dircmd_fma[3]) {
         $auto=fma_filter("d", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd=fma_translate("Changer les droits d'un répertoire");
               $chmod_dir ="<form method=\"post\" action=\"modules.php\">";
               $chmod_dir.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $chmod_dir.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $chmod_dir.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $chmod_dir.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $chmod_dir.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $chmod_dir.="<input type=\"hidden\" name=\"op\" value=\"chmoddir-save\" />";
               $chmod_dir.=extend_ascii($auto[2])." : ";
               $chmod_dir.="<select class=\"textbox_standard\" name=\"chmoddir\">";
               $chmod_dir.=chmod_pres($obj->GetPerms($auto[3]."/".$auto[2]),"chmoddir");
               $chmod_dir.="&nbsp;<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "chmoddir-save":
      if ($dircmd_fma[3]) {
         $auto=fma_filter("d", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               settype($chmoddir,"integer");
               if (!$obj->ChgPerms($auto[3]."/".$auto[2],$chmoddir)) {
                  $Err=$obj->Errors;
               } else {
                  Ecr_Log("security","Chmod Directory", $log_dir."/".$auto[2]." IP=>".getip());
               }
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;

   // Fichiers
   case "createfile":
      if ($ficcmd_fma[0]) {
         $auto=fma_filter("f", $userfile, $obj->Extension);
         if ($auto[0]) {
            if (!$obj->Create("f",$base."/".$auto[2])) {
               $Err=$obj->Errors;
            } else {
               Ecr_Log("security","Create File", $log_dir."/".$userfile." IP=>".getip());
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "renamefile":
      if ($ficcmd_fma[1]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd=fma_translate("Renommer un fichier");
               $rename_file ="<form method=\"post\" action=\"modules.php\">";
               $rename_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $rename_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $rename_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $rename_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $rename_file.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $rename_file.="<input type=\"hidden\" name=\"op\" value=\"renamefile-save\" />";
               $rename_file.=extend_ascii($auto[2])."<b> >> </b>";
               $rename_file.="<input class=\"form-control\" type=\"text\" size=\"60\" name=\"renamefile\" value=\"".extend_ascii($auto[2])."\" />";
               $rename_file.="&nbsp;<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "renamefile-save":
      if ($ficcmd_fma[1]) {
         // origine
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $autoD=fma_filter("f", $renamefile, $obj->Extension);
            if ($autoD[0]) {
               $auto[3]=decrypt($browse);
               if (!$obj->Rename($auto[3]."/".$auto[2],$auto[3]."/".$autoD[2])) {
                  $Err=$obj->Errors;
               } else {
                  Ecr_Log("security","Rename File", $log_dir."/".$autoD[2]." IP=>".getip());
               }
            } else {
               $Err=$autoD[1];
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "movefile":
      if ($ficcmd_fma[1]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<span class="text-muted"><i class="fa fa-file fa-2x"></i>&nbsp;</span>'.fma_translate("Déplacer / Copier un fichier");
               $move_file ="<form method=\"post\" action=\"modules.php\">";
               $move_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $move_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $move_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $move_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $move_file.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $move_file.="<select class=\"textbox_standard\" name=\"op\">";
               $move_file.="<option value=\"movefile-save\" selected=\"selected\"> ".fma_translate("Déplacer")."</option>";
               $move_file.="<option value=\"copyfile-save\">".fma_translate("Copier")."</option>";
               $move_file.="</select>";
               $move_file.="&nbsp;&nbsp;".extend_ascii($auto[2])."<b> >> </b>";
               $move_file.="<select class=\"c-select form-control\" name=\"movefile\">";
                  $move_file.="<option value=\"\">/</option>";
                  $arb=explode("|",$obj->GetDirArbo($basedir_fma));
                  while (list(,$rep)=each($arb)) {
                     if ($rep!="") {
                        $rep2=str_replace($basedir_fma,"",$rep);
                        if (fma_autorise("d",basename($rep))) {
                           $move_file.="<option value=\"$rep2\">".str_replace("/"," / ",$rep2)."</option>\n";
                        }
                     }
                  }
               $move_file.="</select>";
               $move_file.="&nbsp;<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "movefile-save":
      if ($ficcmd_fma[1]) {
         // origine
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $auto[3]=decrypt($browse);
            if (!$obj->Move($auto[3]."/".$auto[2],$basedir_fma.$movefile."/".$auto[2])) {
               $Err=$obj->Errors;
            } else {
               Ecr_Log("security","Move File", $log_dir."/".$auto[2]." TO ".$movefile."/".$auto[2]." IP=>".getip());
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "copyfile-save":
      if ($ficcmd_fma[1]) {
         // origine
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $auto[3]=decrypt($browse);
            if (!$obj->Copy($auto[3]."/".$auto[2],$basedir_fma.$movefile."/".$auto[2])) {
               $Err=$obj->Errors;
            } else {
               Ecr_Log("security","Copy File", $log_dir."/".$auto[2]." TO ".$movefile."/".$auto[2]." IP=>".getip());
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "removefile":
      if ($ficcmd_fma[2]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists("$auto[3]/$auto[2]")) {
               $theme_fma=$themeC_fma;
               $cmd='<span class="text-muted"><i class="fa fa-file fa-2x"></i>&nbsp;</span>'.fma_translate("Supprimer un fichier");
               $remove_file ="<form method=\"post\" action=\"modules.php\">";
               $remove_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $remove_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $remove_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $remove_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $remove_file.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $remove_file.="<input type=\"hidden\" name=\"op\" value=\"removefile-save\" />";
               $remove_file.=fma_translate("Confirmez-vous la suppression de")." <code>".extend_ascii($auto[2])."</code>";
               $remove_file.="&nbsp;<input class=\"btn btn-danger\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "removefile-save":
      if ($ficcmd_fma[2]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (!$obj->Remove($auto[3]."/".$auto[2])) {
               $Err=$obj->Errors;
            } else {
               Ecr_Log("security","Delete File", $log_dir."/".$auto[2]." IP=>".getip());
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "chmodfile":
      if ($ficcmd_fma[3]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd=fma_translate("Changer les droits d'un fichier");
               $chmod_file ="<form method=\"post\" action=\"modules.php\">";
               $chmod_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $chmod_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $chmod_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $chmod_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $chmod_file.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $chmod_file.="<input type=\"hidden\" name=\"op\" value=\"chmodfile-save\" />";
               $chmod_file.=extend_ascii($auto[2])." : ";
               $chmod_file.="<select class=\"c-select form-control\" name=\"chmodfile\">";
               $chmod_file.=chmod_pres($obj->GetPerms($auto[3]."/".$auto[2]),"chmodfile");
               $chmod_file.="&nbsp;<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "chmodfile-save":
      if ($ficcmd_fma[3]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               settype($chmodfile,"integer");
               if (!$obj->ChgPerms($auto[3]."/".$auto[2],$chmodfile)) {
                  $Err=$obj->Errors;
               } else {
                  Ecr_Log("security","Chmod File", $log_dir."/".$auto[2]." IP=>".getip());
               }
            }
         } else {
            $Err=$auto[1];
         }
      }
      $op="";
      break;
   case "editfile":
      if ($ficcmd_fma[4]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3]."/".$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd=fma_translate("Editer un fichier");
               $fp=fopen($auto[3]."/".$auto[2],"r");
               if (filesize($auto[3]."/".$auto[2])>0)
                  $Fcontent=fread($fp,filesize($auto[3]."/".$auto[2]));
               fclose($fp);
               $edit_file ="<form method=\"post\" action=\"modules.php\" name=\"adminForm\">";
               $edit_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
               $edit_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
               $edit_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
               $edit_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
               $edit_file.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
               $edit_file.="<input type=\"hidden\" name=\"op\" value=\"editfile-save\" />";
               $edit_file.=extend_ascii($auto[2])." : <br />";
               settype($Fcontent, 'string');
               $edit_file.="<textarea class=\"form-control\" name=\"editfile\" rows=\"30\" cols=\"70\" style=\"width: 100%;\">".htmlspecialchars($Fcontent,ENT_COMPAT|ENT_HTML401,cur_charset)."</textarea>";

               $tabW=explode(" ",$extension_Wysiwyg_fma);
               $suffix = strtoLower(substr(strrchr( $att_name, '.' ), 1 ));
               if (in_array($suffix,$tabW))
                  $edit_file.=aff_editeur("editfile", "true");

               $edit_file.="<br /><input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
            }
         } else {
            $Err=$auto[1];
         }
      }
      break;
   case "editfile-save":
      if ($ficcmd_fma[4]) {
         $auto=fma_filter("f", $att_name, $obj->Extension);
         if ($auto[0]) {
            $tabW=explode(" ",$extension_Edit_fma);
            $suffix = strtoLower(substr(strrchr( $att_name, '.' ), 1 ));
            if (in_array($suffix,$tabW)) {
               $auto[3]=decrypt($browse);
               if (file_exists($auto[3]."/".$auto[2])) {
                  $fp=fopen($auto[3]."/".$auto[2],"w");
                     fputs($fp,stripslashes($editfile));
                  fclose($fp);
                  Ecr_Log("security","Edit File", $log_dir."/".$auto[2]." IP=>".getip());
               }
            } else {
               Ecr_Log("security","Edit File forbidden", $log_dir."/".$auto[2]." IP=>".getip());
            }
         } else {
            $Err=$auto[1];
         }
      }
      $op="";
      break;
   case "pict":
      $auto=fma_filter("d", $att_name, $obj->Extension);
      if ($auto[0]) {
         $auto[3]=decrypt($browse);
         if (file_exists($auto[3]."/".$auto[2])) {
            $theme_fma=$themeC_fma;
            $cmd=fma_translate("Autoriser Pic-Manager")." >> ".$auto[2];
            $pict_dir ="<form method=\"post\" action=\"modules.php\">";
            $pict_dir.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
            $pict_dir.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
            $pict_dir.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
            $pict_dir.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
            $pict_dir.="<input type=\"hidden\" name=\"att_name\" value=\"$att_name\" />";
            $pict_dir.="<input type=\"hidden\" name=\"op\" value=\"pict-save\" />";
            $pict_dir.=fma_translate("Taille maximum (pixel) de l'imagette")." : ";
            $fp=@file($auto[3]."/".$auto[2]."/pic-manager.txt");
            // La première ligne du tableau est un commentaire
            settype($fp[1],"integer");
            $Max_thumb=$fp[1];
            if ($Max_thumb==0)
               $Max_thumb=150;
            settype($fp[2],"integer");
            $refresh=$fp[2];
            if ($refresh==0)
               $refresh=3600;
            $pict_dir.="<input class=\"textbox_standard\" type=\"text\" name=\"maxthumb\" size=\"4\" value=\"$Max_thumb\" /><br />";
            $pict_dir.=fma_translate("Temps de cache (en seconde) des imagettes")." : ";
            $pict_dir.="<input class=\"textbox_standard\" type=\"text\" name=\"refresh\" size=\"6\" value=\"$refresh\" /><br /><br />";
            $pict_dir.="&nbsp;<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";
         }
      } else {
         $Err=$auto[1];
      }
      break;
   case "pict-save":
      $auto=fma_filter("d", $att_name, $obj->Extension);
      if ($auto[0]) {
         $auto[3]=decrypt($browse);
         $fp = fopen($auto[3]."/".$auto[2]."/pic-manager.txt", 'w');
         settype($maxthumb,"integer");
         fputs($fp, "Enable and customize pic-manager / to remove pic-manager : just remove pic-manager.txt\n");
         fputs($fp, $maxthumb."\n");
         fputs($fp, $refresh."\n");
         fclose($fp);
         Ecr_Log("security","Pic-Manager", $log_dir."/".$auto[2]." IP=>".getip());
      } else {
         $Err=$auto[1];
      }
   case "searchfile":
      $resp=$obj->SearchFile($base,$filesearch);
      if ($resp) {
         $resp=explode("|",$resp);
         array_pop($resp);
         $cpt=0;
         while($fic_resp=each($resp)) {
            // on limite le retour au niveau immédiatement inférieur au rep courant
            $rep_niv1=explode("/",str_replace($base,"",$fic_resp[1]));
            if (count($rep_niv1)<4) {
               $dir_search=basename(dirname($fic_resp[1]));
               $fic_search=basename($fic_resp[1]);
               if (fma_autorise("d",$dir_search)) {
                  if (fma_autorise("f",$fic_search)) {
                     $tab_search[$cpt][0]=$dir_search;
                     $tab_search[$cpt][1]=$fic_search;
                     $cpt++;
                  }
               }
            }
         }
         $fic_minuscptr=0;
      }
      break;
   default:
      break;
}

// Construction de la Classe
if ($obj->File_Navigator($base, $tri_fma['tri'], $tri_fma['sens'], $dirsize_fma)) {
   // Current PWD and Url_back / match by OS determination
   if (substr(@php_uname(),0,7) == "Windows") {
      $cur_nav=str_replace("\\","/",$obj->Pwd());
      $cur_nav_back=dirname($base);
   } else {
      $cur_nav=$obj->Pwd();
      $cur_nav_back=str_replace("\\","/",dirname($base));
   }
   // contraint à rester dans la zone de repertoire définie (CHROOT)
   $cur_nav=$base.substr($cur_nav,strlen($base));

   $home="/".basename($basedir_fma);
   $cur_nav_href_back="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt($cur_nav_back))."$urlext_fma\">".str_replace(dirname($basedir_fma),"",$cur_nav_back)."</a>/".basename($cur_nav);
   if ($home_fma!="") {
      $cur_nav_href_back=str_replace($home,$home_fma,$cur_nav_href_back);
   }
   $cur_nav_encrypt=rawurlencode(encrypt($cur_nav));
} else {
   // le répertoire ou sous répertoire est protégé (ex : chmod)
   redirect_url("modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt(dirname($base))));
}

// gestion des types d'extension de fichiers
$att_icons="";
$handle=opendir("$racine_fma/images/upload/file_types");
while (false!==($file = readdir($handle))) {
   if ($file!="." && $file!="..")  {
      $prefix = strtoLower(substr($file,0,strpos($file,'.')));
      $att_icons[$prefix]="<img src=\"images/upload/file_types/".$file."\" border=\"0\" alt=\"\" />";
   }
}
closedir($handle);
$att_icon_default="<img src=\"images/upload/file_types/unknown.gif\" border=\"0\" alt=\"\" />";
$att_icon_multiple="<img src=\"images/upload/file_types/multiple.gif\" border=\"0\" alt=\"\" />";
//$att_icon_dir="<img src=\"images/upload/file_types/dir.gif\" border=\"0\" alt=\"\" />";
$att_icon_dir='<i class="fa fa-folder fa-lg"></i>';

$att_icon_search="<img src=\"images/upload/file_types/search.gif\" border=\"0\" alt=\"\" />";

$suppM=fma_translate("Supprimer");
$renaM=fma_translate("Renommer");
$chmoM=fma_translate("Chmoder");
$editM=fma_translate("Editer");
$moveM=fma_translate("Déplacer / Copier");
$pictM=fma_translate("Autoriser Pic-Manager");
$linked = "onmouseOver=\"this.className='ligna'; this.style.cursor='default';\" onmouseOut=\"this.className='lignb';\"";
// Répertoires
$subdirs=""; $sizeofDir=0;
settype($tab_search,'array');
while ($obj->NextDir()) {
   if (fma_autorise("d", $obj->FieldName)) {
      $sizeofDir=0;
      $subdirs.= "<tr class=\"lignb\" ".$linked.">";
      $clik_url="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt("$base/$obj->FieldName"))."$urlext_fma\">";
      if ($dirpres_fma[0])
         $subdirs.="<td width=\"3%\" align=\"center\">".$clik_url.$att_icon_dir."</a></td>";
      if ($dirpres_fma[1])
         $subdirs.="<td nowrap=\"nowrap\">$clik_url".extend_ascii($obj->FieldName)."</a></td>";
      if ($dirpres_fma[2])
         $subdirs.="<td>".$obj->FieldDate."</td>";
      if ($dirpres_fma[3]) {
         $sizeofD=$obj->FieldSize;
         $sizeofDir=$sizeofDir+$sizeofD;
         $subdirs.="<td>".$obj->ConvertSize($sizeofDir)."</td>";
      }else{$subdirs.="<td>#NA#</td>";}
      if ($dirpres_fma[4])
         $subdirs.="<td>".$obj->FieldPerms."</td>";
      // Traitements
      $obj->FieldName=rawurlencode($obj->FieldName);
      $subdirs.="<td>";
      if ($dircmd_fma[1])
         $subdirs.="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=renamedir&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/rename.png\" border=\"0\" alt=\"$renaM\" title=\"$renaM\" /></a>";
      if ($dircmd_fma[2])
         $subdirs.=" <a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=removedir&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/delete.png\" border=\"0\" alt=\"$suppM\" title=\"$suppM\" /></a>";
      if ($dircmd_fma[3])
         $subdirs.=" <a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=chmoddir&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/chmod.png\" border=\"0\" alt=\"$chmoM\" title=\"$chmoM\" /></a>";
      if ($dirpres_fma[5])
         $subdirs.=" <a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=pict&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/picture.png\" border=\"0\" alt=\"$pictM\" title=\"$pictM\" /></a>";
      $subdirs.="</td></tr>";

      // Search Result for sub-directories
      if ($tab_search) {
         reset($tab_search);
         while (list($l,$fic_resp)=each($tab_search)) {
            if ($fic_resp[0]==$obj->FieldName) {
               $ibid=rawurlencode(encrypt(rawurldecode(encrypt($cur_nav."/".$fic_resp[0]))."#fma#".encrypt($fic_resp[1])));
               $subdirs.="<tr><td width=\"3%\"></td><td>";
               $pop="'getfile.php?att_id=$ibid&amp;apli=f-manager'";
               $target="target=\"_blank\"";
               if (!$wopen_fma) {
                  $subdirs.="$att_icon_search <a href=$pop $target>".extend_ascii($fic_resp[1])."</a></td></tr>\n";
               } else {
                  if (!isset($wopenH_fma)) $wopenH_fma=500;
                  if (!isset($wopenW_fma)) $wopenW_fma=400;
                  $PopUp="$pop,'FManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$wopenH_fma,width=$wopenW_fma,toolbar=no,scrollbars=yes,resizable=yes'";
                  $subdirs.="$att_icon_search <a href=\"javascript:void(0);\" onclick=\"popup=window.open($PopUp); popup.focus();\">".extend_ascii($fic_resp[1])."</a></td></tr>\n";
               }
               array_splice($tab_search,$l,1);
            }
         }
      }
   }
}

// Fichiers
$files=""; $sizeofFic=0;
while ($obj->NextFile()) {
   if (fma_autorise("f", $obj->FieldName)) {
      $ibid=rawurlencode(encrypt($cur_nav_encrypt."#fma#".encrypt($obj->FieldName)));
      $files.= "
      <tr id=\"REPEREDEBUGG\" class=\"lignb\" ".$linked.">";
      if ($ficpres_fma[0]) {
         $ico_search=false;
         $files.="
         <td width=\"3%\" align=\"center\">";
         if ($tab_search) {
            reset($tab_search);
            while ( (list($l,$fic_resp)=each($tab_search)) and (!$ico_search)) {
               if ($fic_resp[1]==$obj->FieldName) {
                  array_splice($tab_search,$l,1);
                  $files.=$att_icon_search;
                  $ico_search=true;
               }
            }
         }
         if (!$ico_search) {
            if (($obj->FieldView=="jpg") or ($obj->FieldView=="gif") or ($obj->FieldView=="png"))
               $files.="<img src=\"getfile.php?att_id=$ibid&amp;apli=f-manager\" width=\"16\" height=\"16\" border=\"0\" />";
            else {
               if (isset($att_icons[$obj->FieldView])) {
                  $files.=$att_icons[$obj->FieldView];
               } else {
                  $files.=$att_icon_default;
               }
            }
         }
         $files.="</td>\n";
      }
      if ($ficpres_fma[1]) {
         if ($url_fma_modifier) {
            include("$racine_fma/modules/$ModPath/users/$FmaRep.mod.php");
            $pop=$url_modifier;
            $target="";
         } else {
            $pop="'getfile.php?att_id=$ibid&amp;apli=f-manager'";
            $target="target=\"_blank\"";
         }
         if (!$wopen_fma) {
            $files.="<td nowrap=\"nowrap\" width=\"50%\"><a href=$pop $target>".extend_ascii($obj->FieldName)."</a></td>\n";
         } else {
            if (!isset($wopenH_fma)) $wopenH_fma=500;
            if (!isset($wopenW_fma)) $wopenW_fma=400;
            $PopUp="$pop,'FManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$wopenH_fma,width=$wopenW_fma,toolbar=no,scrollbars=yes,resizable=yes'";
            if (stristr($PopUp,"window.opener"))
               $files.="<td><a href=\"javascript:void(0);\" $PopUp popup.focus();\">".extend_ascii($obj->FieldName)."</a></td>\n";
            else
               $files.="<td><a href=\"javascript:void(0);\" onclick=\"popup=window.open($PopUp); popup.focus();\">".extend_ascii($obj->FieldName)."</a></td>\n";
         }
      }
      if ($ficpres_fma[2])
         $files.="<td>".$obj->FieldDate."</td>\n";
      if ($ficpres_fma[3]) {
         $sizeofF=$obj->FieldSize;
         $sizeofFic=$sizeofFic+$sizeofF;
         $files.="<td>".$obj->ConvertSize($sizeofF)."</td>\n";
      }  else $files.="<td>#NA#</td>";
      if ($ficpres_fma[4])
         $files.="<td>".$obj->FieldPerms."</td>\n";
      // Traitements
      $obj->FieldName=rawurlencode($obj->FieldName);
      $cmd_ibid="";
      if ($ficcmd_fma[1])
         $cmd_ibid.="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=renamefile&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/rename.png\" border=\"0\" alt=\"$renaM\" title=\"$renaM\" /></a>\n";
      if ($ficcmd_fma[5])
         $cmd_ibid.="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=movefile&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/move.png\" border=\"0\" alt=\"$moveM\" title=\"$moveM\" /></a>\n";
      if ($ficcmd_fma[2])
         $cmd_ibid.="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=removefile&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/delete.png\" border=\"0\" alt=\"$suppM\" title=\"$suppM\" /></a>\n";
      if ($ficcmd_fma[3])
         $cmd_ibid.="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=chmodfile&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/chmod.png\" border=\"0\" alt=\"$chmoM\" title=\"$chmoM\" /></a>\n";
      if ($ficcmd_fma[4]) {
         $tabW=explode(" ",$extension_Edit_fma);
         $suffix = strtoLower(substr(strrchr( $obj->FieldName, '.' ), 1 ));
         if (in_array($suffix,$tabW))
            $cmd_ibid.="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".$cur_nav_encrypt."&amp;op=editfile&amp;att_name=".$obj->FieldName."\"><img src=\"modules/$ModPath/images/edit.png\" border=\"0\" alt=\"$editM\" title=\"$editM\" /></a>\n";
      }
      if ($cmd_ibid) $files.="<td>".$cmd_ibid."</td>";
      $files.="</tr>";
   }
}

if (file_exists($infos_fma)) {
   $infos=aff_langue(join("",file($infos_fma)));
}

// Form
   $upload_file ="<form enctype=\"multipart/form-data\" method=\"post\" action=\"modules.php\">";
   $upload_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
   $upload_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
   $upload_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
   $upload_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
   $upload_file.="<input type=\"hidden\" name=\"op\" value=\"upload\" />";
   $upload_file.="<div class=\"form-group\"><span class=\"help-block\"><b>".fma_translate("Extensions autorisées : ")."</b>".$extension_fma."</span><input class=\"form-control\" name=\"userfile\" type=\"file\" size=\"50\" value=\"\" /></div>";
   $upload_file.="<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /><br />";
   $upload_file.="</form>";

   $create_dir ="<form method=\"post\" action=\"modules.php\">";
   $create_dir.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
   $create_dir.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
   $create_dir.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
   $create_dir.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
   $create_dir.="<input type=\"hidden\" name=\"op\" value=\"createdir\" />";
   $create_dir.="<div class=\"form-group\"><input class=\"form-control\" name=\"userdir\" type=\"text\" size=\"50\" value=\"\" /></div>";
   $create_dir.="<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";

   $create_file ="<form method=\"post\" action=\"modules.php\">";
   $create_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />";
   $create_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
   $create_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\" />";
   $create_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\" />";
   $create_file.="<input type=\"hidden\" name=\"op\" value=\"createfile\" />";
   $create_file.="<div class=\"form-group\"><input class=\"form-control\" name=\"userfile\" type=\"text\" size=\"50\" value=\"\" /></div>";
   $create_file.="<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\" /></form>";

   $search_file ="<form method=\"post\" action=\"modules.php\">";
   $search_file.="<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\">";
   $search_file.="<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\">";
   $search_file.="<input type=\"hidden\" name=\"FmaRep\" value=\"$FmaRep\">";
   $search_file.="<input type=\"hidden\" name=\"browse\" value=\"$browse\">";
   $search_file.="<input type=\"hidden\" name=\"op\" value=\"searchfile\">";
   $search_file.="<div class=\"form-group\"><input class=\"form-control\" name=\"filesearch\" type=\"text\" size=\"50\" value=\"\"></div>";
   $search_file.="<input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".fma_translate("Ok")."\"></form>";

chdir("$racine_fma/");
// Génération de l'interface
$inclusion=false;
if (file_exists("themes/$Default_Theme/html/modules/f-manager/$theme_fma")) {
   $inclusion="themes/$Default_Theme/html/modules/f-manager/$theme_fma";
} elseif (file_exists("themes/default/html/modules/f-manager/$theme_fma")) {
   $inclusion="themes/default/html/modules/f-manager/$theme_fma";
} else {
   echo "html/modules/f-manager/$theme_fma manquant / not find !";
}

if ($inclusion) {
   $Xcontent=join("",file($inclusion));
   $Xcontent=str_replace("_back",extend_ascii($cur_nav_href_back),$Xcontent);
   $Xcontent=str_replace("_refresh","<a class=\"btn btn-primary-outline btn-small\" href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode($browse)."$urlext_fma\"><i class=\"fa fa-refresh fa-spin\"></i>&nbsp;".fma_translate("Rafraichir")."</a>",$Xcontent);
//   if ($dirsize_fma)
      $Xcontent=str_replace("_size",$obj->ConvertSize($obj->GetDirSize($cur_nav)),$Xcontent);
//   else $Xcontent=str_replace("_size",'-',$Xcontent);
   $Xcontent=str_replace("_nb_subdir",($obj->Count("d")-$dir_minuscptr),$Xcontent);
   $Xcontent=str_replace("_subdirs",$subdirs,$Xcontent);
   $Xcontent=str_replace("_nb_file",($obj->Count("f")-$fic_minuscptr),$Xcontent);
   $Xcontent=str_replace("_files",$files,$Xcontent);

   if (isset($cmd))
      $Xcontent=str_replace("_cmd",$cmd,$Xcontent);
   else
      $Xcontent=str_replace("_cmd","",$Xcontent);

   if ($dircmd_fma[0]) {
      $Xcontent=str_replace("_cre_dir",$create_dir,$Xcontent);
   } else {
      $Xcontent=str_replace("<div id=\"cre_dir\">","<div id=\"cre_dir\" style=\"display: none;\">",$Xcontent);
      $Xcontent=str_replace("_cre_dir","",$Xcontent);
   }
   $Xcontent=str_replace("_del_dir",$remove_dir,$Xcontent);
   $Xcontent=str_replace("_ren_dir",$rename_dir,$Xcontent);
   $Xcontent=str_replace("_chm_dir",$chmod_dir,$Xcontent);

   if (isset($pict_dir))
      $Xcontent=str_replace("_pic_dir",$pict_dir,$Xcontent);
   else
      $Xcontent=str_replace("_pic_dir","",$Xcontent);

   if ($ficcmd_fma[0]) {
      $Xcontent=str_replace("_upl_file",$upload_file,$Xcontent);
      $Xcontent=str_replace("_cre_file",$create_file,$Xcontent);
   } else {
      $Xcontent=str_replace("<div id=\"upl_file\">","<div id=\"upl_file\" style=\"display: none;\">",$Xcontent);
      $Xcontent=str_replace("<div id=\"cre_file\">","<div id=\"cre_file\" style=\"display: none;\">",$Xcontent);
      $Xcontent=str_replace("_upl_file","",$Xcontent);
      $Xcontent=str_replace("_cre_file","",$Xcontent);
   }
   $Xcontent=str_replace("_sea_file",$search_file,$Xcontent);
   $Xcontent=str_replace("_del_file",$remove_file,$Xcontent);
   $Xcontent=str_replace("_chm_file",$chmod_file,$Xcontent);
   $Xcontent=str_replace("_ren_file",$rename_file,$Xcontent);
   $Xcontent=str_replace("_mov_file",$move_file,$Xcontent);

   if (isset($Err))
      $Xcontent=str_replace("_error",$Err,$Xcontent);
   else
      $Xcontent=str_replace("_error","",$Xcontent);
   if (isset($infos))
      $Xcontent=str_replace("_infos",$infos,$Xcontent);
   else
      $Xcontent=str_replace("_infos","",$Xcontent);

   if ($dirpres_fma[5]) {
      if ($uniq_fma)
         $Xcontent=str_replace("_picM","<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=pic-manager&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode($browse)."\"><b>".fma_translate("Pic-Manager")."</b></a>",$Xcontent);
      else
         $Xcontent=str_replace("_picM","<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=pic-manager&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode($browse)."\" target=\"_blank\"><b>".fma_translate("Pic-Manager")."</b></a>",$Xcontent);
   } else
      $Xcontent=str_replace("_picM","",$Xcontent);

   $Xcontent=str_replace("_quota",$obj->ConvertSize($sizeofDir+$sizeofFic)." || ".fma_translate("Taille maximum d'un fichier : ").$obj->ConvertSize($max_size),$Xcontent);

   if (!$NPDS_fma) {
      // utilisation de pages.php
      settype($PAGES, 'array');
      require_once("themes/pages.php");
      $Titlesitename=aff_langue($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title']);

      include("meta/meta.php");
      echo "<link rel=\"shortcut icon\" href=\"images/favicon.ico\" type=\"image/x-icon\" />\n";
      echo ("<link href=\"$css_fma\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");

      global $tiny_mce;
      if ($tiny_mce) {
         $tiny_mce_init=$PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['TinyMce'];
         if ($tiny_mce_init) {
            $tiny_mce_theme=$PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['TinyMce-theme'];
            echo aff_editeur("tiny_mce", "begin");
         }
      }
      echo "</head>\n<body>";
   } else {
      include ("header.php");
   }

   // Head banner de présentation F-Manager
   if (file_exists("themes/$Default_Theme/html/modules/f-manager/head.html")) {
      echo "\n";
      include ("themes/$Default_Theme/html/modules/f-manager/head.html");
      echo "\n";
   }

   ?>
   <script type="text/javascript">
   //<![CDATA[
   function previewImage(fileInfo) {
     var filename = "";
     filename = fileInfo;

     //create the popup
     popup = window.open('', 'imagePreview', 'width=600,height=450,left=100,top=75,screenX=100,screenY=75,scrollbars,location,menubar,status,toolbar,resizable=1');

     //start writing in the html code
     popup.document.writeln("<html><body style='background-color: #FFFFFF;'>");
     popup.document.writeln("<img src='" + filename + "'></body></html>");
   }
   //]]>
   </script>
   <?php

   // l'insertion de la FORM d'édition doit intervenir à la fin du calcul de l'interface ... sinon on modifie le contenu
   // Meta_lang n'est pas chargé car trop lent pour une utilisation sur de gros répertoires
   $Xcontent=aff_langue($Xcontent);
   $Xcontent=str_replace("_edt_file",$edit_file,$Xcontent);
   echo $Xcontent;

   // Foot banner de présentation F-Manager
   if (file_exists("themes/$Default_Theme/html/modules/f-manager/foot.html")) {
      echo "\n";
      include ("themes/$Default_Theme/html/modules/f-manager/foot.html");
      echo "\n";
   }

   if (!$NPDS_fma) {
      echo "</body></html>";
      if ($tiny_mce) {
         if ($tiny_mce_init)
            echo aff_editeur("tiny_mce", "end");
      }
   } else {
      include ("footer.php");
   }
}
?>
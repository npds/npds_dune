<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) die();

global $ModPath, $ModStart, $language, $Default_Theme, $Default_Skin, $NPDS_Key, $NPDS_Prefix;

if (file_exists("modules/$ModPath/lang/f-manager-$language.php"))
   include ("modules/$ModPath/lang/f-manager-$language.php");
else
   include ("modules/$ModPath/lang/f-manager-english.php");

include ("modules/$ModPath/class.navigator.php");

// Gestion Ascii étendue
function extend_ascii($ibid) {
   $tmp=urlencode($ibid);
   $tmp=str_replace("%82","È",$tmp);
   $tmp=str_replace("%85","‡",$tmp);
   $tmp=str_replace("%87","Á",$tmp);
   $tmp=str_replace("%88","Í",$tmp);
   $tmp=str_replace("%97","˘",$tmp);
   $tmp=str_replace("%8A","Ë",$tmp);
   $tmp=urldecode($tmp);
   return ($tmp);
}

// Gestion des fichiers autorisés
function fma_filter($type, $filename, $Extension) {
   $autorise=false;
   $error='';
   if ($type=="f") $filename=removeHack($filename);
   $filename=preg_replace('#[/\\\:\*\?"<>|]#i','', rawurldecode($filename));
   $filename=str_replace('..','',$filename);

   // Liste des extensions autorisées
   $suffix = strtoLower(substr(strrchr( $filename, '.' ), 1 ));
   if (($suffix!='') or ($type=='d')) {
      if ((in_array($suffix,$Extension)) or ($Extension[0]=='*') or $type=='d') {
         // Fichiers interdits en fonction de qui est connecté
         if (fma_autorise($type, $filename))
            $autorise=true;
         else
            $error=fma_translate("Fichier interdit");
      } else
         $error=fma_translate("Type de fichier interdit");
   } else
      $error=fma_translate("Fichier interdit");
   $tab[]=$autorise;
   $tab[]=$error;
   $tab[]=$filename;
   return($tab);
}

// Gestion des autorisations sur les répertoires et les fichiers
function fma_autorise($type, $dir) {
   global $user, $admin, $dirlimit_fma, $ficlimit_fma, $access_fma, $dir_minuscptr, $fic_minuscptr;

   $autorise_arbo=false;

   if ($type=='a')
      $autorise_arbo=$access_fma;
   if ($type=='d') {
      if (is_array($dirlimit_fma)) {
         if (array_key_exists($dir,$dirlimit_fma))
            $autorise_arbo=$dirlimit_fma[$dir];
      }
   }
   if ($type=='f') {
      if (is_array($ficlimit_fma)) {
         if (array_key_exists($dir,$ficlimit_fma))
            $autorise_arbo=$ficlimit_fma[$dir];
      }
   }

   if ($autorise_arbo) {
      $auto_dir='';
      if (($autorise_arbo=='membre') and ($user)) {
         $auto_dir=true;
      } elseif (($autorise_arbo=='anonyme') and (!$user)) {
         $auto_dir=true;
      } elseif (($autorise_arbo=='admin') and ($admin)) {
         $auto_dir=true;
      } elseif (($autorise_arbo!='membre') and ($autorise_arbo!='anonyme') and ($autorise_arbo!='admin') and ($user)) {
         $tab_groupe=valid_group($user);
         if ($tab_groupe) {
            foreach($tab_groupe as $groupevalue) {
               $tab_auto=explode(',',$autorise_arbo);
               foreach($tab_auto as $gp) {
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
      if ($type=='d')
         $dir_minuscptr++;
      if ($type=='f')
         $fic_minuscptr++;
   }
   return($auto_dir);
}

function chmod_pres($ibid, $champ) {
   $options = ['400' => 'r--------', '444' => 'r-x------', '500' => 'r--------', '544' => 'r-xr--r--', '600' => 'rw-------', '644' => 'rw-r--r--', '655' => 'rw-r-xr-x', '666' => 'rw-rw-rw-', '700' => 'rwx------', '744' => 'rwxr--r--', '755' => 'rwxr-xr-x', '766' => 'rwxrw-rw-', '770' => 'rwxrwx---', '777' => 'rwxrwxrwx'];
   $chmod = '';
   $current_value = isset($ibid[0]) ? $ibid[0] : '';
   foreach ($options as $value => $description) {
      $selected = ($current_value == $value) ? ' selected="selected"' : '';
      $chmod .= '<option value="' . $value . '"' . $selected . '> ' . $value . ' (' . $description . ')</option>';
   }
   return $chmod;
}

// Lancement sur un Répertoire en fonction d'un fichier de conf particulier
if ($FmaRep) {
   if (filtre_module($FmaRep)) {
      // Si je ne trouve pas de fichier - est-ce que l'utilisateur fait partie d'un groupe ?
      if (!file_exists("modules/$ModPath/users/".strtolower($FmaRep).".conf.php")) {
         $tab_groupe=valid_group($user);
         if ($tab_groupe) {
            // si j'ai au moins un groupe est ce que celui-ci dispose d'un fichier de configuration ?  - je m'arrête au premier groupe !
            foreach($tab_groupe as $gp) {
               $groupename=Q_select("SELECT groupe_name FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$gp' ORDER BY `groupe_id` ASC",3600);
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
             $themelist = explode(' ', $themelist);
             $pos=array_search($cookie[9],$themelist);
             if ($pos!==false)
               $Default_Theme=$themelist[$pos];
         }
         include("modules/$ModPath/users/".strtolower($FmaRep).".conf.php");
         if (fma_autorise('a', '')) {
            $theme_fma=$themeG_fma;
            $fic_minuscptr=0;
            $dir_minuscptr=0;
         } else
            Access_Error();
      } else
         Access_Error();
   } else
      Access_Error();
} else
   Access_Error();

if (isset($browse)) {
   $ibid=rawurldecode(decrypt($browse));
   if (substr(@php_uname(),0,7) == 'Windows')
      $ibid=preg_replace('#[\*\?"<>|]#i','', $ibid);
   else
      $ibid=preg_replace('#[\:\*\?"<>|]#i','', $ibid);
   $ibid=str_replace('..','',$ibid);
   // contraint à rester dans la zone de repertoire définie (CHROOT)
   $base=$basedir_fma.substr($ibid,strlen($basedir_fma));
} else {
   $browse='';
   $base=$basedir_fma;
}

// initialisation de la classe
$obj= new Navigator();
$obj->Extension=explode(' ',$extension_fma);

// traitements
$rename_dir=''; $remove_dir=''; $chmod_dir='';
$remove_file=''; $move_file=''; $rename_file=''; $chmod_file=''; $edit_file='';

if (substr(@php_uname(),0,7)=="Windows")
   $log_dir=str_replace($basedir_fma,'',$base);
else
   $log_dir=str_replace("\\","/",str_replace($basedir_fma,'',$base));

include_once("modules/upload/upload.conf.php");

settype($op,'string');
switch ($op) {
   case 'upload':
      if ($ficcmd_fma[0]) {
         if ($userfile!='none') {
            global $language;
            include_once("modules/upload/lang/upload.lang-$language.php");
            include_once("modules/upload/clsUpload.php");
            $upload = new Upload();
            $filename = trim($upload->getFileName("userfile"));
            if ($filename) {
               $upload->maxupload_size=$max_size;
               $auto=fma_filter('f', $filename, $obj->Extension);
               if ($auto[0]) {
                  if (!$upload->saveAs($auto[2], $base.'/', 'userfile', true))
                     $Err=$upload->errors;
                  else
                     Ecr_Log('security','Upload File', $log_dir.'/'.$filename.' IP=>'.getip());
               } else
                  $Err=$auto[1];
            }
         }
      }
   break;

   // Répertoires
   case 'createdir':
      if ($dircmd_fma[0]) {
         $auto=fma_filter('d', $userdir, $obj->Extension);
         if ($auto[0]) {
            if (!$obj->Create('d',$base.'/'.$auto[2]))
               $Err=$obj->Errors;
            else {
               Ecr_Log('security','Create Directory', $log_dir.'/'.$userdir.' IP=>'.getip());
               $fp = fopen($base.'/'.$auto[2].'/.htaccess', 'w');
               fputs($fp, 'Deny from All');
               fclose($fp);
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'renamedir':
      if ($dircmd_fma[1]) {
         $auto=fma_filter('d', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-folder fs-1 me-2 align-middle text-body-secondary"></i>'.fma_translate("Renommer un répertoire");
               $rename_dir ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="renamedir-save" />
                  <div class="mb-3">
                     <label><code> '.extend_ascii($auto[2]).'</code></label>
                     <input class="form-control" type="text" name="renamefile" value="'.extend_ascii($auto[2]).'" />
                  </div>
                  <div class="mb-3">
                     <button class="btn btn-primary" type="submit" name="ok">'.fma_translate("Ok").'</button>
                  </div>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'renamedir-save':
      if ($dircmd_fma[1]) {
         // origine
         $auto=fma_filter('d', $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $autoD=fma_filter('d', $renamefile, $obj->Extension);
            if ($autoD[0]) {
               $auto[3]=decrypt($browse);
               if (!$obj->Rename($auto[3].'/'.$auto[2],$auto[3].'/'.$autoD[2]))
                  $Err=$obj->Errors;
               else
                  Ecr_Log('security','Rename Directory', $log_dir.'/'.$autoD[2].' IP=>'.getip());
            } else
               $Err=$autoD[1];
         } else
            $Err=$auto[1];
      }
   break;

   case 'removedir':
      if ($dircmd_fma[2]) {
         $auto=fma_filter('d', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-folder fs-1 me-2 text-danger align-middle"></i><span class="text-danger">'.fma_translate("Supprimer un répertoire").'</span>';
               $remove_dir ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="removedir-save" />
                  <div class="mb-3">
                     '.fma_translate("Confirmez-vous la suppression de").' <code>'.extend_ascii($auto[2]).'</code>
                  </div>
                  <div class="mb-3">
                     <button class="btn btn-danger" type="submit" name="ok">'.fma_translate("Ok").'</button>
                  </div>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'removedir-save':
      if ($dircmd_fma[2]) {
         $auto=fma_filter('d', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            @unlink($auto[3].'/'.$auto[2].'/.htaccess');
            @unlink($auto[3].'/'.$auto[2].'/pic-manager.txt');
            if (!$obj->RemoveDir($auto[3].'/'.$auto[2])) {
               $Err=$obj->Errors;
            } else
               Ecr_Log('security','Delete Directory', $log_dir.'/'.$auto[2].' IP=>'.getip());
         } else
            $Err=$auto[1];
      }
   break;

   case 'chmoddir':
      if ($dircmd_fma[3]) {
         $auto=fma_filter('d', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-folder fs-1 me-2 align-middle text-body-secondary"></i>'.fma_translate("Changer les droits d'un répertoire");
               $chmod_dir ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="chmoddir-save" />
                  <div class="mb-3">
                     <label class="form-label" for="chmoddir" ><code>'.extend_ascii($auto[2]).'</code></label>
                     <select class="form-select" id="chmoddir" name="chmoddir">
                        '.chmod_pres($obj->GetPerms($auto[3].'/'.$auto[2]),'chmoddir').'
                     </select>
                  </div>
                  <div class="mb-3">
                     <input class="btn btn-primary" type="submit" name="ok" value="'.fma_translate("Ok").'" />
                  </div>
               </form>';
            }
         }
         else
            $Err=$auto[1];
      }
   break;
   case 'chmoddir-save':
      if ($dircmd_fma[3]) {
         $auto=fma_filter('d', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               settype($chmoddir,'integer');
               if (!$obj->ChgPerms($auto[3].'/'.$auto[2],$chmoddir))
                  $Err=$obj->Errors;
               else
                  Ecr_Log('security','Chmod Directory', $log_dir.'/'.$auto[2].' IP=>'.getip());
            }
         }
         else
         $Err=$auto[1];
      }
   break;

   // Fichiers
   case 'createfile':
      if ($ficcmd_fma[0]) {
         $auto=fma_filter('f', $userfile, $obj->Extension);
         if ($auto[0]) {
            if (!$obj->Create('f',$base.'/'.$auto[2]))
               $Err=$obj->Errors;
            else
               Ecr_Log('security','Create File', $log_dir.'/'.$userfile.' IP=>'.getip());
         } else
            $Err=$auto[1];
      }
   break;
   case 'renamefile':
      if ($ficcmd_fma[1]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-file-earmark fs-2 me-2 align-middle text-body-secondary"></i>'.fma_translate("Renommer un fichier");
               $rename_file ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="renamefile-save" />
                  <div class="mb-3">
                     <label class="form-label" for="renamefile"><code>'.extend_ascii($auto[2]).'</code></label>
                     <input class="form-control" type="text" size="60" id="renamefile" name="renamefile" value="'.extend_ascii($auto[2]).'" />
                  </div>
                  <div class="mb-3">
                     <input class="btn btn-primary" type="submit" name="ok" value="'.fma_translate("Ok").'" />
                  </div>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'renamefile-save':
      if ($ficcmd_fma[1]) {
         // origine
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $autoD=fma_filter('f', $renamefile, $obj->Extension);
            if ($autoD[0]) {
               $auto[3]=decrypt($browse);
               if (!$obj->Rename($auto[3].'/'.$auto[2],$auto[3].'/'.$autoD[2]))
                  $Err=$obj->Errors;
               else
                  Ecr_Log('security','Rename File', $log_dir.'/'.$autoD[2].' IP=>'.getip());
            } else
               $Err=$autoD[1];
         } else
            $Err=$auto[1];
      }
   break;
   case 'movefile':
      if ($ficcmd_fma[1]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-file-earmark fs-2 me-2 align-middle text-body-secondary"></i>'.fma_translate("Déplacer / Copier un fichier");
               $move_file ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <div class="mb-3">
                     <select class="form-select me-2" name="op">
                        <option value="movefile-save" selected="selected"> '.fma_translate("Déplacer").'</option>
                        <option value="copyfile-save">'.fma_translate("Copier").'</option>
                     </select>
                     <code>'.extend_ascii($auto[2]).'</code>
                  </div>
                  <div class="mb-3">
                     <select class="form-select" name="movefile">';
                  $move_file.='
                        <option value="">/</option>';
                  $arb=explode('|',$obj->GetDirArbo($basedir_fma));
                  foreach($arb as $rep) {
                     if ($rep!='') {
                        $rep2=str_replace($basedir_fma,'',$rep);
                        if (fma_autorise('d',basename($rep)))
                           $move_file.='
                        <option value="'.$rep2.'">'.str_replace('/',' / ',$rep2).'</option>';
                     }
                  }
                  $move_file.='
                     </select>
                  </div>
                  <div class="mb-3">
                     <button class="btn btn-primary" type="submit" name="ok">'.fma_translate("Ok").'</button>
                  </div>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'movefile-save':
      if ($ficcmd_fma[1]) {
         // origine
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $auto[3]=decrypt($browse);
            if (!$obj->Move($auto[3].'/'.$auto[2],$basedir_fma.$movefile."/".$auto[2]))
               $Err=$obj->Errors;
            else
               Ecr_Log('security','Move File', $log_dir.'/'.$auto[2].' TO '.$movefile.'/'.$auto[2].' IP=>'.getip());
         } else
            $Err=$auto[1];
      }
   break;
   case 'copyfile-save':
      if ($ficcmd_fma[1]) {
         // origine
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            // destination
            $auto[3]=decrypt($browse);
            if (!$obj->Copy($auto[3].'/'.$auto[2],$basedir_fma.$movefile.'/'.$auto[2]))
               $Err=$obj->Errors;
            else
               Ecr_Log('security','Copy File', $log_dir.'/'.$auto[2].' TO '.$movefile.'/'.$auto[2].' IP=>'.getip());
         } else
            $Err=$auto[1];
      }
   break;
   case 'removefile':
      if ($ficcmd_fma[2]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists("$auto[3]/$auto[2]")) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-file-earmark fs-2 me-2 text-danger align-middle"></i><span class="text-danger">'.fma_translate("Supprimer un fichier").'</span>';
               $remove_file ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="removefile-save" />
                  <div class="mb-3 lead">
                     '.fma_translate("Confirmez-vous la suppression de").' <code>'.extend_ascii($auto[2]).'</code>
                  </div>
                  <div class="mb-3">
                     <button class="btn btn-danger" type="submit" name="ok">Ok</button>
                  </div>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'removefile-save':
      if ($ficcmd_fma[2]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (!$obj->Remove($auto[3].'/'.$auto[2]))
               $Err=$obj->Errors;
            else
               Ecr_Log('security','Delete File', $log_dir.'/'.$auto[2].' IP=>'.getip());
         } else
            $Err=$auto[1];
      }
   break;
   case 'chmodfile':
      if ($ficcmd_fma[3]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-file-earmark fs-2 me-2 align-middle text-body-secondary"></i>'.fma_translate("Changer les droits d'un fichier").'</span>';
               $chmod_file ='
               <form method="post" action="modules.php">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="chmodfile-save" />
                  <div class="mb-3">
                     <label class="form-label" for="chmodfile"><code>'.extend_ascii($auto[2]).'</code></label>
                     <select class="form-select" id="chmodfile" name="chmodfile">
                        '.chmod_pres($obj->GetPerms($auto[3].'/'.$auto[2]),"chmodfile").'
                     </select>
                  </div>
                  <div class="mb-3">
                     <button class="btn btn-primary" type="submit" name="ok">'.fma_translate("Ok").'</button>
                  </div>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'chmodfile-save':
      if ($ficcmd_fma[3]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               settype($chmodfile,"integer");
               if (!$obj->ChgPerms($auto[3].'/'.$auto[2],$chmodfile))
                  $Err=$obj->Errors;
               else
                  Ecr_Log('security','Chmod File', $log_dir.'/'.$auto[2].' IP=>'.getip());
            }
         } else
            $Err=$auto[1];
      }
      $op='';
   break;
   case 'editfile':
      if ($ficcmd_fma[4]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $auto[3]=decrypt($browse);
            if (file_exists($auto[3].'/'.$auto[2])) {
               $theme_fma=$themeC_fma;
               $cmd='<i class="bi bi-file-earmark fs-2 me-2 align-middle text-body-secondary"></i>'.fma_translate("Editer un fichier").'</span>';
               $fp=fopen($auto[3].'/'.$auto[2],'r');
               if (filesize($auto[3].'/'.$auto[2])>0)
                  $Fcontent=fread($fp,filesize($auto[3].'/'.$auto[2]));
               fclose($fp);
               $edit_file ='
               <form method="post" action="modules.php" name="adminForm">
                  <input type="hidden" name="ModPath" value="'.$ModPath.'" />
                  <input type="hidden" name="ModStart" value="'.$ModStart.'" />
                  <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
                  <input type="hidden" name="browse" value="'.$browse.'" />
                  <input type="hidden" name="att_name" value="'.$att_name.'" />
                  <input type="hidden" name="op" value="editfile-save" />
                  <div class="mb-3 row">
                     <label class="form-label col-12" for="editfile"><code>'.extend_ascii($auto[2]).'</code></label>';
               settype($Fcontent, 'string');
               $edit_file.='
                     <div class="col-12">
                        <textarea class="tin form-control" id="editfile" name="editfile" rows="18">'.htmlspecialchars($Fcontent,ENT_COMPAT|ENT_HTML401,'UTF-8').'</textarea>
                     </div>
                  </div>';
               $tabW=explode(' ',$extension_Wysiwyg_fma);
               $suffix = strtoLower(substr(strrchr( $att_name, '.' ), 1 ));
               if (in_array($suffix,$tabW))
                  $edit_file.=aff_editeur('editfile', 'true');
               $edit_file.='
                  <button class="btn btn-primary" type="submit" name="ok">'.fma_translate("Ok").'</button>
               </form>';
            }
         } else
            $Err=$auto[1];
      }
   break;
   case 'editfile-save':
      if ($ficcmd_fma[4]) {
         $auto=fma_filter('f', $att_name, $obj->Extension);
         if ($auto[0]) {
            $tabW=explode(' ',$extension_Edit_fma);
            $suffix = strtoLower(substr(strrchr( $att_name, '.' ), 1 ));
            if (in_array($suffix,$tabW)) {
               $auto[3]=decrypt($browse);
               if (file_exists($auto[3].'/'.$auto[2])) {
                  $fp=fopen($auto[3].'/'.$auto[2],'w');
                     fputs($fp,stripslashes($editfile));
                  fclose($fp);
                  Ecr_Log('security','Edit File', $log_dir.'/'.$auto[2].' IP=>'.getip());
               }
            } else
               Ecr_Log('security','Edit File forbidden', $log_dir.'/'.$auto[2].' IP=>'.getip());
         } else
            $Err=$auto[1];
      }
      $op='';
   break;
   case 'pict':
      $auto=fma_filter('d', $att_name, $obj->Extension);
      if ($auto[0]) {
         $auto[3]=decrypt($browse);
         if (file_exists($auto[3].'/'.$auto[2])) {
            $theme_fma=$themeC_fma;
            $cmd='<span class="text-body-secondary"><i class="fa fa-image fa-2x me-2 align-middle"></i></span>'.fma_translate("Autoriser Pic-Manager").' >> '.$auto[2];
            $pict_dir ='
            <form method="post" action="modules.php">
               <input type="hidden" name="ModPath" value="'.$ModPath.'" />
               <input type="hidden" name="ModStart" value="'.$ModStart.'" />
               <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
               <input type="hidden" name="browse" value="'.$browse.'" />
               <input type="hidden" name="att_name" value="'.$att_name.'" />
               <input type="hidden" name="op" value="pict-save" />
               <div class="mb-3">
                  <label class="form-label" for="maxthumb">'.fma_translate("Taille maximum (pixel) de l'imagette").'</label>';
            $fp=@file($auto[3].'/'.$auto[2].'/pic-manager.txt');
            // La première ligne du tableau est un commentaire
            settype($fp[1],'integer');
            $Max_thumb=$fp[1];
            if ($Max_thumb==0)
               $Max_thumb=150;
            settype($fp[2],'integer');
            $refresh=$fp[2];
            if ($refresh==0)
               $refresh=3600;
            $pict_dir.='
                  <input class="form-control" type="number" id="maxthumb" name="maxthumb" size="4" value="'.$Max_thumb.'" />
               </div>
               <div class="mb-3">
                  <label class="form-label" for="refresh">'.fma_translate("Temps de cache (en seconde) des imagettes").'</label> 
                  <input class="form-control" type="number" id="refresh" name="refresh" size="6" value="'.$refresh.'" />
               </div>
               <div class="mb-3">
                  <button class="btn btn-primary" type="submit" name="ok">'.fma_translate("Ok").'</button>
               </div>
            </form>';
         }
      } else
         $Err=$auto[1];
   break;
   case 'pict-save':
      $auto=fma_filter('d', $att_name, $obj->Extension);
      if ($auto[0]) {
         $auto[3]=decrypt($browse);
         $fp = fopen($auto[3].'/'.$auto[2].'/pic-manager.txt', 'w');
         settype($maxthumb,'integer');
         fputs($fp, "Enable and customize pic-manager / to remove pic-manager : just remove pic-manager.txt\n");
         fputs($fp, $maxthumb."\n");
         fputs($fp, $refresh."\n");
         fclose($fp);
         Ecr_Log('security','Pic-Manager', $log_dir.'/'.$auto[2].' IP=>'.getip());
      }
      else
         $Err=$auto[1];
   break;
   case 'searchfile':
      $resp=$obj->SearchFile($base,$filesearch);
      if ($resp) {
         $resp=explode('|',$resp);
         array_pop($resp);
         $cpt=0;

         foreach($resp as $fic_resp) {
            // on limite le retour au niveau immédiatement inférieur au rep courant
            $rep_niv1=explode('/',str_replace($base,'',$fic_resp));
            if (count($rep_niv1)<4) {
               $dir_search=basename(dirname($fic_resp));
               $fic_search=basename($fic_resp);
               if (fma_autorise('d',$dir_search)) {
                  if (fma_autorise('f',$fic_search)) {
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
   // contraint à rester dans la zone de répertoire définie (CHROOT)
   $cur_nav=$base.substr($cur_nav,strlen($base));

   $home='/'.basename($basedir_fma);
   $cur_nav_href_back="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt($cur_nav_back))."$urlext_fma\">".str_replace(dirname($basedir_fma),"",$cur_nav_back)."</a>/".basename($cur_nav);
   if ($home_fma!='') {
      $cur_nav_href_back=str_replace($home,$home_fma,$cur_nav_href_back);
   }
   $cur_nav_encrypt=rawurlencode(encrypt($cur_nav));
} else {
   // le répertoire ou sous répertoire est protégé (ex : chmod)
   redirect_url("modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt(dirname($base))));
}

// gestion des types d'extension de fichiers
$extensions = ['asf', 'avi', 'bmp', 'box', 'cfg', 'cfm', 'conf', 'crypt', 'css', 'dia', 'dir', 'doc', 'dot', 'dwg', 'excel', 'exe', 'filebsd', 'filelinux', 'fla', 'flash', 'gif', 'gz', 'gzip', 'hlp', 'htaccess', 'htm', 'html', 'ico', 'image', 'img', 'indd', 'index', 'ini', 'iso', 'java', 'jpg', 'js', 'json', 'kml', 'lyx', 'mdb', 'mid', 'mov', 'mp3', 'mp4', 'mpeg', 'mpg', 'pdf', 'php', 'php3', 'php4', 'phps', 'png', 'pot', 'ppt', 'ps', 'psd', 'psp', 'ra', 'rar', 'rpm', 'rtf', 'search', 'sit', 'svg', 'swf', 'sxc', 'sxd', 'sxi', 'sys', 'tar', 'tgz', 'ttf', 'txt', 'unknown', 'vsd', 'wav', 'wbk', 'wma', 'wmf', 'wmv', 'word', 'xls', 'xml', 'xsl', 'zip'];
foreach ($extensions as $extens) {
   $att_icons[$extens]='
      <span class="fa-stack">
        <i class="bi bi-file-earmark-fill fa-stack-2x text-body-secondary"></i>
        <span class="fa-stack-1x filetype-text small ">'.$extens.'</span>
      </span>';
}
$att_icon_default='
      <span class="fa-stack">
        <i class="bi bi-file-earmark-fill fa-stack-2x text-body-secondary"></i>
        <span class="fa-stack-1x filetype-text ">?</span>
      </span>';
$att_icon_multiple='
      <span class="fa-stack">
        <i class="bi bi-file-earmark-fill fa-stack-2x text-body-secondary"></i>
        <span class="fa-stack-1x filetype-text ">...</span>
      </span>';
$att_icon_dir='<i class="bi bi-folder fs-3"></i>';
$att_icon_search='<i class="bi bi-search fs-4"></i>';

$suppM=fma_translate("Supprimer");
$renaM=fma_translate("Renommer");
$chmoM=fma_translate("Chmoder");
$editM=fma_translate("Editer");
$moveM=fma_translate("Déplacer / Copier");
$pictM=fma_translate("Autoriser Pic-Manager");
// Répertoires
$subdirs=''; $sizeofDir=0;
settype($tab_search,'array');
while ($obj->NextDir()) {
   if (fma_autorise('d', $obj->FieldName)) {
      $sizeofDir=0;
      $subdirs.= '
      <tr>';
      $clik_url="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt("$base/$obj->FieldName"))."$urlext_fma\">";
      if ($dirpres_fma[0])
         $subdirs.='
         <td width="3%" align="center">'.$clik_url.$att_icon_dir.'</a></td>';
      if ($dirpres_fma[1])
         $subdirs.='
         <td nowrap="nowrap">'.$clik_url.extend_ascii($obj->FieldName).'</a></td>';
      if ($dirpres_fma[2])
         $subdirs.='
         <td class="text-center"><small>'.$obj->FieldDate.'</small></td>';
      if ($dirpres_fma[3]) {
         $sizeofD=$obj->FieldSize;
         $sizeofDir=$sizeofDir+(integer)$sizeofD;
         $subdirs.='
         <td class="d-none d-sm-table-cell"><small>'.$obj->ConvertSize($sizeofDir).'</small></td>';
      }else{$subdirs.='
         <td class="d-none d-sm-table-cell"><small>#NA#</small></td>';}
      if ($dirpres_fma[4])
         $subdirs.='
         <td class="d-none d-sm-table-cell"><small>'.$obj->FieldPerms.'</small></td>';
      // Traitements
      $obj->FieldName=rawurlencode($obj->FieldName);
      $subdirs.='
         <td class="">';
      if ($dircmd_fma[1])
         $subdirs.='<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=renamedir&amp;att_name='.$obj->FieldName.'"><i class="bi bi-pencil-fill ms-2 fs-4" title="'.$renaM.'" data-bs-toggle="tooltip"></i></a>';
      if ($dircmd_fma[3])
         $subdirs.=' <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=chmoddir&amp;att_name='.$obj->FieldName.'"><i class="bi bi-pencil ms-3 fs-4" title="'.$chmoM.'" data-bs-toggle="tooltip"></i><small>7..</small></a>';
      if ($dirpres_fma[5])
         $subdirs.=' <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=pict&amp;att_name='.$obj->FieldName.'"><i class="bi bi-image-fill ms-3 fs-4" title="'.$pictM.'" data-bs-toggle="tooltip"></i></a>';
      if ($dircmd_fma[2])
         $subdirs.=' <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=removedir&amp;att_name='.$obj->FieldName.'"><i class="bi bi-trash2-fill text-danger ms-3 fs-4" title="'.$suppM.'" data-bs-toggle="tooltip"></i></a>';
      $subdirs.='</td>
      </tr>';

      // Search Result for sub-directories
      if ($tab_search) {
         reset($tab_search);
         foreach($tab_search as $l => $fic_resp) {
            if ($fic_resp[0]==$obj->FieldName) {
               $ibid=rawurlencode(encrypt(rawurldecode(encrypt($cur_nav.'/'.$fic_resp[0])).'#fma#'.encrypt($fic_resp[1])));
               $subdirs.='
      <tr>
         <td width="3%"></td>
         <td>';
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
$files=''; $sizeofFic=0;
while ($obj->NextFile()) {
   if (fma_autorise('f', $obj->FieldName)) {
      $ibid=rawurlencode(encrypt($cur_nav_encrypt."#fma#".encrypt($obj->FieldName)));
      $files.= '
      <tr>';
      if ($ficpres_fma[0]) {
         $ico_search=false;
         $files.='
         <td width="3%" align="center">';
         if (!$ico_search) {
            foreach($tab_search as $l => $fic_resp) {
               if ($fic_resp[1]==$obj->FieldName) {
                  array_splice($tab_search,$l,1);
                  $files.=$att_icon_search;
                  $ico_search=true;
               }
            }
         }
         if (!$ico_search) {
            if (($obj->FieldView=='jpg') or ($obj->FieldView=='jpeg') or ($obj->FieldView=='gif') or ($obj->FieldView=='png') or ($obj->FieldView=='svg'))
               $files.="<img src=\"getfile.php?att_id=$ibid&amp;apli=f-manager\" width=\"32\" height=\"32\" loading=\"lazy\" />";
            else {
               if (isset($att_icons[$obj->FieldView]))
                  $files.=$att_icons[$obj->FieldView];
               else
                  $files.=$att_icon_default;
            }
         }
         $files.='</td>';
      }
      if ($ficpres_fma[1]) {
         if ($url_fma_modifier) {
            include("$racine_fma/modules/$ModPath/users/$FmaRep.mod.php");
            $pop=$url_modifier;
            $target='';
         } else {
            $pop="'getfile.php?att_id=$ibid&amp;apli=f-manager'";
            $target='target="_blank"';
         }
         if (!$wopen_fma) {
            $files.="
         <td nowrap=\"nowrap\" width=\"50%\"><a href=$pop $target>".extend_ascii($obj->FieldName)."</a></td>";
         } else {
            if (!isset($wopenH_fma)) $wopenH_fma=500;
            if (!isset($wopenW_fma)) $wopenW_fma=400;
            $PopUp="$pop,'FManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$wopenH_fma,width=$wopenW_fma,toolbar=no,scrollbars=yes,resizable=yes'";
            if (stristr($PopUp,"window.opener"))
               $files.="
         <td><a href=\"javascript:void(0);\" $PopUp popup.focus();\">".extend_ascii($obj->FieldName)."</a></td>";
            else
               $files.="
         <td><a href=\"javascript:void(0);\" onclick=\"popup=window.open($PopUp); popup.focus();\">".extend_ascii($obj->FieldName)."</a></td>";
         }
      }
      if ($ficpres_fma[2])
         $files.='
         <td class="text-center"><small>'.$obj->FieldDate.'</small></td>';
      if ($ficpres_fma[3]) {
         $sizeofF=$obj->FieldSize;
         $sizeofFic=$sizeofFic+$sizeofF;
         $files.='
         <td><small>'.$obj->ConvertSize($sizeofF).'</small></td>';
      }  else $files.='
         <td><small>#NA#</small></td>';
      if ($ficpres_fma[4]) 
         $files.='
         <td class="text-end"><small>'.$obj->FieldPerms.'</small></td>'; else $files.="<td><small>#NA#</small></td>";
      // Traitements
      $obj->FieldName=rawurlencode($obj->FieldName);
      $cmd_ibid='&nbsp;';
      if ($ficcmd_fma[1])
         $cmd_ibid.='<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=renamefile&amp;att_name='.$obj->FieldName.'"><i class="bi bi-pencil-fill ms-3 fs-4" title="'.$renaM.'" data-bs-toggle="tooltip"></i></a>';
      if ($ficcmd_fma[4]) {
         $tabW=explode(' ',$extension_Edit_fma);
         $suffix = strtoLower(substr(strrchr( $obj->FieldName, '.' ), 1 ));
         if (in_array($suffix,$tabW))
            $cmd_ibid.='<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=editfile&amp;att_name='.$obj->FieldName.'"><i class="bi bi-pencil-square ms-3 fs-4" title="'.$editM.'" data-bs-toggle="tooltip"></i></a>';
      }
      if ($ficcmd_fma[5])
         $cmd_ibid.='<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=movefile&amp;att_name='.$obj->FieldName.'"><i class="bi bi-box-arrow-up-right ms-3 fs-4" title="'.$moveM.'" data-bs-toggle="tooltip"></i></a>';
      if ($ficcmd_fma[3])
         $cmd_ibid.='<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=chmodfile&amp;att_name='.$obj->FieldName.'"><i class="bi bi-pencil ms-3 fs-4" title="'.$chmoM.'" data-bs-toggle="tooltip"></i><small>7..</small></a>';
      if ($ficcmd_fma[2])
         $cmd_ibid.='<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.$cur_nav_encrypt.'&amp;op=removefile&amp;att_name='.$obj->FieldName.'"><i class="bi bi-trash2-fill text-danger  ms-3 fs-4" title="'.$suppM.'" data-bs-toggle="tooltip"></i></a>';
      if ($cmd_ibid) $files.='
         <td>'.$cmd_ibid.'</td>';
      $files.='
      </tr>';
   }
}

if (file_exists($infos_fma))
   $infos=aff_langue(join('',file($infos_fma)));
// Form
   $upload_file ='
   <form id="uploadfichier" enctype="multipart/form-data" method="post" action="modules.php" lang="'.language_iso(1,'','').'">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
      <input type="hidden" name="browse" value="'.$browse.'" />
      <input type="hidden" name="op" value="upload" />
      <div class="mb-3">
         <div class="help-block mb-2">'.fma_translate("Extensions autorisées : ").'<span class="text-success">'.$extension_fma.'</span></div>
         <div class="input-group mb-3 me-sm-2">
            <button class="btn btn-secondary" type="button" onclick="reset2($(\'#userfile\'),\'\');"><i class="bi bi-arrow-clockwise"></i></button>
            <label class="input-group-text n-ci" id="lab" for="userfile"></label>
            <input type="file" class="form-control custom-file-input" name="userfile" id="userfile" />
         </div>
         <button class="btn btn-primary" type="submit" name="ok" ><i class="bi bi-upload"></i></button>
      </div>
   </form>
   <script type="text/javascript">
      //<![CDATA[
         window.reset2 = function (e,f) {
            e.wrap("<form>").closest("form").get(0).reset();
            e.unwrap();
            event.preventDefault();
         };
      //]]>
   </script>';

   $create_dir ='
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
      <input type="hidden" name="browse" value="'.$browse.'" />
      <input type="hidden" name="op" value="createdir" />
      <div class="mb-3">
         <input class="form-control" name="userdir" type="text" value="" />
      </div>
      <input class="btn btn-primary" type="submit" name="ok" value="'.fma_translate("Ok").'" />
   </form>';

   $create_file ='
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
      <input type="hidden" name="browse" value="'.$browse.'" />
      <input type="hidden" name="op" value="createfile" />
      <div class="mb-3">
         <input class="form-control" name="userfile" type="text" value="" />
      </div>
      <input class="btn btn-primary" type="submit" name="ok" value="'.fma_translate("Ok").'" />
   </form>';

   $search_file ='
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="FmaRep" value="'.$FmaRep.'" />
      <input type="hidden" name="browse" value="'.$browse.'" />
      <input type="hidden" name="op" value="searchfile" />
      <div class="mb-3">
         <input class="form-control" name="filesearch" type="text" size="50" value="" />
      </div>
      <input class="btn btn-primary" type="submit" name="ok" value="'.fma_translate("Ok").'" />
   </form>';

chdir("$racine_fma/");
// Génération de l'interface
$inclusion=false;
if (file_exists("themes/$Default_Theme/html/modules/f-manager/$theme_fma"))
   $inclusion="themes/$Default_Theme/html/modules/f-manager/$theme_fma";
elseif (file_exists("themes/default/html/modules/f-manager/$theme_fma"))
   $inclusion="themes/default/html/modules/f-manager/$theme_fma";
else 
   echo "html/modules/f-manager/$theme_fma manquant / not find !";

if ($inclusion) {
   $Xcontent=join('',file($inclusion));
   if($FmaRep =='minisite-ges') {
      if ($user) {
         $userdata = explode(':', base64_decode($user));
         $Xcontent=str_replace('_home','<a class="nav-link" href="minisite.php?op='.$userdata[1].'" target="_blank"><i class="bi bi-display-fill fs-1"></i></a>',$Xcontent);
      }
   }
   else
      $Xcontent=str_replace('_home','<a class="nav-link" href="index.php" target="_blank"><span class="bi bi-house-fill fs-1 align-middle"></a>',$Xcontent);
   
   $Xcontent=str_replace('_back',extend_ascii($cur_nav_href_back),$Xcontent);
   $Xcontent=str_replace('_refresh','<a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.rawurlencode($browse).$urlext_fma.'"><i class="bi bi-arrow-clockwise fs-1 d-md-none" title="'.fma_translate("Rafraîchir").'" data-bs-toggle="tooltip"></i><span class="d-none d-md-block mt-2">'.fma_translate("Rafraîchir").'</span></a>',$Xcontent);
//   if ($dirsize_fma)
      $Xcontent=str_replace('_size',$obj->ConvertSize($obj->GetDirSize($cur_nav)),$Xcontent);
//   else $Xcontent=str_replace("_size",'-',$Xcontent);
   $Xcontent=str_replace('_nb_subdir',($obj->Count("d")-$dir_minuscptr),$Xcontent);
   if(($obj->Count("d")-$dir_minuscptr)==0)
      $Xcontent=str_replace('_tabdirclassempty','collapse',$Xcontent);
   $Xcontent=str_replace('_subdirs',$subdirs,$Xcontent);
   $Xcontent=str_replace('_nb_file',($obj->Count("f")-$fic_minuscptr),$Xcontent);
   $Xcontent=str_replace('_files',$files,$Xcontent);
   $Xcontent= (isset($cmd)) ?
      str_replace('_cmd',$cmd,$Xcontent) :
      str_replace('_cmd','',$Xcontent) ;

   if ($dircmd_fma[0])
      $Xcontent=str_replace('_cre_dir',$create_dir,$Xcontent);
   else {
      $Xcontent=str_replace('_classcredirno','collapse',$Xcontent);
      $Xcontent=str_replace('<div id="cre_dir">','<div id="cre_dir" style="display: none;">',$Xcontent);
      $Xcontent=str_replace('_cre_dir','',$Xcontent);
   }
   $Xcontent=str_replace('_del_dir',$remove_dir,$Xcontent);
   $Xcontent=str_replace('_ren_dir',$rename_dir,$Xcontent);
   $Xcontent=str_replace('_chm_dir',$chmod_dir,$Xcontent);
   $Xcontent= (isset($pict_dir)) ?
      str_replace('_pic_dir',$pict_dir,$Xcontent) :
      str_replace("_pic_dir",'',$Xcontent) ;

   if ($ficcmd_fma[0]) {
      $Xcontent=str_replace('_upl_file',$upload_file,$Xcontent);
      $Xcontent=str_replace('_cre_file',$create_file,$Xcontent);
   } else {
      $Xcontent=str_replace('_classuplfileno','collapse',$Xcontent);
      $Xcontent=str_replace('<div id="upl_file">','<div id="upl_file" style="display: none;">',$Xcontent);
      $Xcontent=str_replace('_classcrefileno','collapse',$Xcontent);
      $Xcontent=str_replace('<div id="cre_file">','<div id="cre_file" style="display: none;">',$Xcontent);
      $Xcontent=str_replace('_upl_file','',$Xcontent);
      $Xcontent=str_replace('_cre_file','',$Xcontent);
   }
   $Xcontent=str_replace('_sea_file',$search_file,$Xcontent);
   $Xcontent=str_replace('_del_file',$remove_file,$Xcontent);
   $Xcontent=str_replace('_chm_file',$chmod_file,$Xcontent);
   $Xcontent=str_replace('_ren_file',$rename_file,$Xcontent);
   $Xcontent=str_replace('_mov_file',$move_file,$Xcontent);
   $Xcontent= (isset($Err)) ?
      str_replace('_error','<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$Err.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',$Xcontent) :
      str_replace('_error','',$Xcontent) ;
   $Xcontent= (isset($infos)) ?
      str_replace('_infos',$infos,$Xcontent) :
      str_replace('_infos','',$Xcontent) ;
      $chemin_pic = $cur_nav.'/pic-manager.txt';
   if ($dirpres_fma[5] and file_exists($chemin_pic)) {
      $Xcontent = ($uniq_fma) ?
         str_replace('_picM','<a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=pic-manager&amp;FmaRep='.$FmaRep.'&amp;browse='.rawurlencode($browse).'"><span class="d-md-none"><i class="bi bi-image fs-1" title="'.fma_translate("Images manager").'" data-bs-toggle="tooltip" data-bs-placement="bottom"></i></span><span class="d-none d-md-block mt-2">'.fma_translate("Images manager").'</span></a>',$Xcontent) :
         str_replace('_picM','<a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=pic-manager&amp;FmaRep='.$FmaRep.'&amp;browse='.rawurlencode($browse).'" target="_blank"><span class="d-md-none"><i class="fa fa-image fa-lg"></i></span><span class="d-none d-md-block mt-2">'.fma_translate("Images manager").'</span></a>',$Xcontent) ;
   } else
      $Xcontent=str_replace('_picM','<a class="nav-link text-body-secondary"><span class="d-md-none"><i class="bi bi-image fs-1" title="'.fma_translate("Vous n'êtes pas autorisé à utiliser le gestionnaire de média. SVP contacter l'administrateur.").'" data-bs-toggle="tooltip" data-bs-placement="top"></i></span><span class="d-none d-md-block mt-2" title="'.fma_translate("Vous n'êtes pas autorisé à utiliser le gestionnaire de média. SVP contacter l'administrateur.").'" data-bs-toggle="tooltip" data-bs-placement="bottom">'.fma_translate("Images manager").'</span></a>',$Xcontent);
   $Xcontent=str_replace('_quota',$obj->ConvertSize($sizeofDir+$sizeofFic).' || '.fma_translate("Taille maximum d'un fichier : ").$obj->ConvertSize($max_size),$Xcontent);

   if (!$NPDS_fma) {
      // utilisation de pages.php
      settype($PAGES, 'array');
      require_once("themes/pages.php");
      $Titlesitename=aff_langue($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title']);

      global $Default_Theme, $Default_Skin, $user;
      if (isset($user) and $user!='') {
         global $cookie;
         if($cookie[9] !='') {
            $ibix=explode('+', urldecode($cookie[9]));
            $theme = (array_key_exists(0, $ibix)) ? $ibix[0] : $Default_Theme ;
            $skin = (array_key_exists(1, $ibix)) ? $ibix[1] : $Default_skin ;
            $tmp_theme=$theme;
            if (!$file=@opendir("themes/$theme")) $tmp_theme=$Default_Theme;
         } else 
            $tmp_theme=$Default_Theme;
      } else {
         $theme=$Default_Theme;
         $skin=$Default_Skin;
         $tmp_theme=$theme;
      }
      $skin = $skin =='' ? 'default' : $skin ;

      include("meta/meta.php");
      echo '
      <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
      <link rel="stylesheet" href="lib/font-awesome/css/all.min.css" />
      <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap-icons.css" />
      <link rel="stylesheet" id="fw_css" href="themes/_skins/'.$skin.'/bootstrap.min.css" />
      <link rel="stylesheet" href="lib/bootstrap-table/dist/bootstrap-table.min.css" />
      <link rel="stylesheet" id="fw_css_extra" href="themes/_skins/'.$skin.'/extra.css" />
      <link rel="stylesheet" href="'.$css_fma.'" title="default" type="text/css" media="all" />';

      global $tiny_mce;
      if ($tiny_mce) {
         $tiny_mce_init=$PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['TinyMce'];
         if ($tiny_mce_init) {
            $tiny_mce_theme=$PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['TinyMce-theme'];
            echo aff_editeur("tiny_mce", "begin");
         }
      }
      echo '
      <script type="text/javascript" src="lib/js/jquery.min.js"></script>
      </head>
      <body class="p-3">';
   } else
      include ("header.php");

   // Head banner de présentation F-Manager
   if (file_exists("themes/$Default_Theme/html/modules/f-manager/head.html")) {
      echo "\n";
      include ("themes/$Default_Theme/html/modules/f-manager/head.html");
      echo "\n";
   }
   else if (file_exists("themes/default/html/modules/f-manager/head.html")) {
      echo "\n";
      include ("themes/default/html/modules/f-manager/head.html");
      echo "\n";
   }

   ?>
   <script type="text/javascript">
   //<![CDATA[
      function previewImage(fileInfo) {
        var filename = '';
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
   $Xcontent=str_replace('_edt_file',$edit_file,$Xcontent);
   echo $Xcontent;

   // Foot banner de présentation F-Manager
   if (file_exists("themes/$Default_Theme/html/modules/f-manager/foot.html")) {
      echo "\n";
      include ("themes/$Default_Theme/html/modules/f-manager/foot.html");
      echo "\n";
   }
   else if (file_exists("themes/default/html/modules/f-manager/foot.html")) {
      echo "\n";
      include ("themes/default/html/modules/f-manager/foot.html");
      echo "\n";
   }

   if (!$NPDS_fma) {
      echo '
   </body>
</html>';
      if ($tiny_mce)
         if ($tiny_mce_init)
            echo aff_editeur("tiny_mce", "end");
   } else
      include ("footer.php");
}
?>
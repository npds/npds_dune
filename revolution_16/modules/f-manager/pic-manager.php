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
if (!stristr($_SERVER['PHP_SELF'],'modules.php')) die();

global $ModPath, $ModStart, $language, $Default_Theme, $Default_Skin, $NPDS_Key, $nuke_url;
include ("modules/$ModPath/lang/f-manager-$language.php");
include ("modules/$ModPath/class.navigator.php");

if (isset($user)) {
   include("themes/list.php");
   $themelist = explode(' ', $themelist);
   $pos=array_search($cookie[9],$themelist);
   if ($pos!==false)
      $Default_Theme=$themelist[$pos];
}
settype($curn_nav,'string');
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
   if ($type=='f') $filename=removeHack($filename);
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
   if ($type=='d')
      if(isset($dirlimit_fma) and array_key_exists($dir, $dirlimit_fma))
         $autorise_arbo=$dirlimit_fma[$dir];
   if ($type=='f')
      if(array_key_exists($dir, $ficlimit_fma))
         $autorise_arbo=$ficlimit_fma[$dir];
   if ($autorise_arbo) {
      $auto_dir='';
      if (($autorise_arbo=='membre') and ($user))
         $auto_dir=true;
      elseif (($autorise_arbo=='anonyme') and (!$user))
         $auto_dir=true;
      elseif (($autorise_arbo=='admin') and ($admin))
         $auto_dir=true;
      elseif (($autorise_arbo!='membre') and ($autorise_arbo!='anonyme') and ($autorise_arbo!='admin') and ($user)) {
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
   } else
      $auto_dir=true;
   if ($auto_dir!=true) {
      if ($type=='d')
         $dir_minuscptr++;
      if ($type=='f')
         $fic_minuscptr++;
   }
   return($auto_dir);
}

function imagesize($name, $Max_thumb) {
   $size = getimagesize($name);
   $h_i = $size[1]; //hauteur
   $w_i = $size[0]; //largeur

   if (($h_i > $Max_thumb) || ($w_i > $Max_thumb)) {
      if ($h_i > $w_i) {
         $convert = $Max_thumb/$h_i;
         $h_i = $Max_thumb;
         $w_i = ceil($w_i*$convert);
      } else {
         $convert = $Max_thumb/$w_i;
         $w_i = $Max_thumb;
         $h_i = ceil($h_i*$convert);
      }
   }
   $s_img['hauteur'][0]=$h_i;
   $s_img['hauteur'][1]=$size[1];
   $s_img['largeur'][0]=$w_i;
   $s_img['largeur'][1]=$size[0];
   return ($s_img);
}
function CreateThumb($Image, $Source, $Destination, $Max, $ext) {
   switch ($ext) {
      case (preg_match('/jpeg|jpg/i', $ext) ? true : false) :
         if (function_exists('imagecreatefromjpeg'))
            $src=@imagecreatefromjpeg($Source.$Image);
      break;
      case (preg_match('/gif/i', $ext) ? true : false) :
         if (function_exists('imagecreatefromgif'))
            $src=@imagecreatefromgif($Source.$Image);
      break;
      case (preg_match('/png/i', $ext) ? true : false) :
         if (function_exists('imagecreatefrompng'))
            $src=@imagecreatefrompng($Source.$Image);
      break;
   }

   $size = imagesize($Source.'/'.$Image, $Max);
   $h_i = $size['hauteur'][0]; //hauteur
   $w_i = $size['largeur'][0]; //largeur

   if ($src) {
      if (function_exists('imagecreatetruecolor'))
         $im = @imagecreatetruecolor($w_i, $h_i);
      else
         $im = @imagecreate($w_i, $h_i);

      @imagecopyresized($im, $src, 0, 0, 0, 0, $w_i, $h_i, $size['largeur'][1], $size['hauteur'][1]);
      @imageinterlace ($im,1);
      switch ($ext) {
         case (preg_match('/jpeg|jpg/i', $ext) ? true : false) :
            @imagejpeg($im, $Destination.$Image, 100);
         break;
         case (preg_match('/gif/i', $ext) ? true : false) :
            @imagegif($im, $Destination.$Image);
         break;
         case (preg_match('/png/i', $ext) ? true : false) :
            @imagepng($im, $Destination.$Image, 6);
         break;
      }
      @chmod($Destination.$Image,0766);
      $size['gene-img'][0]=true;
   }
   return ($size);
}

// Lancement sur un Répertoire en fonction d'un fichier de conf particulier
if ($FmaRep) {
   if (filtre_module($FmaRep)) {
      if (file_exists("modules/$ModPath/users/".strtolower($FmaRep).".conf.php")) {
         include("modules/$ModPath/users/".strtolower($FmaRep).".conf.php");
         if (fma_autorise("a", "")) {
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

if(isset($browse) and $browse!='') {
   $ibid=rawurldecode(decrypt($browse));
   if (substr(@php_uname(),0,7) == "Windows")
      $ibid=preg_replace('#[\*\?"<>|]#i','', $ibid);
   else
      $ibid=preg_replace('#[\:\*\?"<>|]#i','', $ibid);
   $ibid=str_replace('..','',$ibid);
   // contraint à rester dans la zone de répertoire définie
   $ibid=$basedir_fma.substr($ibid,strlen($basedir_fma));
   $base=$ibid;
} else
   $base = $basedir_fma;

// initialisation de la classe
$obj= new Navigator();
$obj->Extension=explode(' ',$extension_fma);

// Construction de la Classe
if ($obj->File_Navigator($base, $tri_fma['tri'], $tri_fma['sens'], $dirsize_fma)) {
   // Current PWD and Url_back / match by OS determination
   if (substr(@php_uname(),0,7) == "Windows") {
      $cur_nav=str_replace('\\','/',$obj->Pwd());
      $cur_nav_back=dirname($base);
   } else {
      $cur_nav=$obj->Pwd();
      $cur_nav_back=str_replace('\\','/',dirname($base));
   }
   $home='/'.basename($basedir_fma);
   $cur_nav_href_back="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt($cur_nav_back))."\">".str_replace(dirname($basedir_fma),"",$cur_nav_back)."</a>/".basename($cur_nav);
   if ($home_fma!='')
      $cur_nav_href_back=str_replace($home,$home_fma,$cur_nav_href_back);
   $cur_nav_encrypt=rawurlencode(encrypt($cur_nav));
} else {
   // le répertoire ou sous répertoire est protégé (ex : chmod)
   redirect_url("modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt(dirname($base))));
}

// gestion des types d'extension de fichiers
$handle=opendir("$racine_fma/images/upload/file_types");
while (false!==($file = readdir($handle))) {
   if ($file!='.' && $file!='..') {
      $prefix = strtoLower(substr($file,0,strpos($file,'.')));
      $att_icons[$prefix]="<img src=\"images/upload/file_types/".$file."\" alt=\"\" />";
   }
}
closedir($handle);
$att_icon_dir='<i class="bi bi-folder fs-2"></i>';

// Répertoires
$subdirs=''; $sizeofDir=0;
while ($obj->NextDir()) {
   if (fma_autorise('d', $obj->FieldName)) {
      $subdirs.= '
      <tr>';
      $clik_url="<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=".rawurlencode(encrypt("$base/$obj->FieldName"))."\">";
      if ($dirpres_fma[0])
         $subdirs.='
         <td width="3%">'.$clik_url.$att_icon_dir.'</a></td>';
      if ($dirpres_fma[1])
         $subdirs.="
         <td nowrap=\"nowrap\" width=\"50%\">$clik_url".extend_ascii($obj->FieldName)."</a></td>";
      if ($dirpres_fma[2])
         $subdirs.='
         <td><small>'.$obj->FieldDate.'</small></td>';
      if ($dirpres_fma[3]) {
         $sizeofD=$obj->FieldSize;
         $sizeofDir=$sizeofDir+$sizeofD;
         $subdirs.='
         <td><small>'.$obj->ConvertSize($sizeofDir).'</small></td>';
      }
      else
         $subdirs.='
         <td><small>#NA</small></td>';
      $subdirs.='
      </tr>';
   }
}

// Fichiers
$fp=@file("pic-manager.txt");
   // La première ligne du tableau est un commentaire
   settype($fp[1],'integer');
   $Max_thumb=$fp[1];
   settype($fp[2],'integer');
   $refresh=$fp[2];
   if ($refresh==0)
      $refresh=3600;
$rep_cache=$racine_fma.'/cache/';
$rep_cache_encrypt=rawurlencode(encrypt($rep_cache));
$cook = isset($cookie[1]) ? $cookie[1] : '' ;
$cache_prefix=$cook.md5(str_replace('/','.',str_replace($racine_fma.'/','',$cur_nav)));

if ($Max_thumb>0) {
   $files='<div id="photo" class="mt-3 d-flex flex-wrap justify-content-center justify-content-md-between">';
   while ($obj->NextFile()) {
      if (fma_autorise('f', $obj->FieldName)) {
         $suf=strtolower($obj->FieldView);
         if (($suf=='gif') or ($suf=='jpg') or ($suf=='jpeg') or ($suf=='png') or ($suf=='swf') or ($suf=='mp3') or ($suf=='mp4') or ($suf=='svg') ) {
            if ($ficpres_fma[1]) {
               $ibid=rawurlencode(encrypt(rawurldecode($cur_nav_encrypt).'#fma#'.encrypt($obj->FieldName)));
               $imagette='';

               if (($suf=='gif') or ($suf=='jpg') or ($suf=='jpeg')) {
                  if ((function_exists('gd_info')) or extension_loaded('gd')) {
                     //cached or not ?
                     if (file_exists($rep_cache.$cache_prefix.'.'.$obj->FieldName)) {
                        if (filemtime($rep_cache.$cache_prefix.'.'.$obj->FieldName) > time()-$refresh) {
                           if (filesize($rep_cache.$cache_prefix.'.'.$obj->FieldName)>0) {
                              $cache=true;
                              $image=imagesize($obj->FieldName, $Max_thumb);
                              $imagette=rawurlencode(encrypt(rawurldecode($rep_cache_encrypt).'#fma#'.encrypt($cache_prefix.'.'.$obj->FieldName)));
                           } else
                              $cache=false;
                        } else
                           $cache=false;
                     } else
                        $cache=false;
                     if (!$cache) {
                        $image=CreateThumb($obj->FieldName, $cur_nav, $rep_cache.$cache_prefix.'.', $Max_thumb, $suf);
                        if(array_key_exists('gene-img', $image))
                           if ($image['gene-img'][0]==true)
                              $imagette=rawurlencode(encrypt(rawurldecode($rep_cache_encrypt).'#fma#'.encrypt($cache_prefix.'.'.$obj->FieldName)));
                     }
                  } else {
                     $image=imagesize($curn_nav.$obj->FieldName, $Max_thumb);
                  }
               } else if (($suf=='png') or ($suf=='swf')) {
                  $image=imagesize($curn_nav.$obj->FieldName, $Max_thumb);
               }
               if(($suf!='mp3') or ($suf!='mp4') or ($suf!='svg')) {
                  $h_i=$image['hauteur'][0];
                  $h_pi=$image['hauteur'][1];
                  $w_i=$image['largeur'][0];
                  $w_pi=$image['largeur'][1];
               }

               switch ($suf) {
                  case 'gif':
                  case 'jpg':
                  case 'png':
                     $PopUp="'getfile.php?att_id=$ibid&amp;apli=f-manager','PicManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".($h_pi+40).",width=".($w_pi+40).",toolbar=no,scrollbars=yes,resizable=yes'";
                     $files.='
                     <div class="imagethumb">
                        <a title="'.$obj->FieldName.'" data-bs-toggle="tooltip" href="javascript:void(0);" onclick="popup=window.open('.$PopUp.'); popup.focus();"><img src="getfile.php?att_id=';
                     $files.= ($imagette) ? $imagette : $ibid ;
                     $files.="&amp;apli=f-manager\" border=\"0\" width=\"$w_i\" height=\"$h_i\" alt=\"$obj->FieldName\" loading=\"lazy\"/ ></a>\n";
                     $files.='
                     </div>';
                  break;

                  case "swf":
                     $img_size="width=\"$w_i\" height=\"$h_i\"";
                     $PopUp="getfile.php?att_id=$ibid&amp;apli=f-manager";
                     $files.="<div class=\"imagethumb\">";
                     $files.="<a href=\"$PopUp\" target=\"_blank\">";
                     $files.="<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4\,0\,2\,0\" $img_size><param name=\"quality\" value=\"high\"><param name=\"src\" value=\"$PopUp\"><embed src=\"$PopUp\" quality=\"high\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" $img_size></embed></object>";
                     $files.="</a>\n</div>";
                  break;

                  case "mp3":
                     $PopUp="getfile.php?att_id=$ibid&amp;apli=f-manager";
                     $files.= '
                     <div class="mp3">
                        <audio controls src="'.$PopUp.'"></audio><br />
                        <a href="javascript:void(0);" onclick="popup=window.open(\''.$PopUp.'\',\'PicManager\',\'menubar=no,location=no,directories=no,status=no,copyhistory=no,height=180,width=280,toolbar=no,scrollbars=yes,resizable=yes\'); popup.focus();">'.fma_translate("Ecouter").'<br />'.$obj->FieldName.'</a>
                     </div>';
                  break;

                  case "mp4":
                     $PopUp='getfile.php?att_id='.$ibid.'&amp;apli=f-manager';
                     $files.= '
                     <div class="mp4">
                        <video data-bs-toggle="tooltip" title="'.$obj->FieldName.'" preload="auto" width="220" height="175" controls="controls">
                           <source src="'.$PopUp.'" type="video/mp4">
                        </video>
                     </div>';
                  break;

                  case "svg":
                     $ibid=decrypt(rawurldecode($cur_nav_encrypt)).'/'.$obj->FieldName;
                     $PopUp='getfile.php?att_id='.$ibid.'&amp;apli=f-manager';
                     $files.= '
                     <div class="svg">
                        <a data-bs-toggle="tooltip" title="'.$obj->FieldName.'">
                        '.file_get_contents($ibid).'
                        </a>
                     </div>';
                  break;
               }
            }
         }
      }
   }
   $files.='
   </div>';
}
chdir("$racine_fma/");

// Génération de l'interface
$inclusion=false;
if (file_exists("themes/$Default_Theme/html/modules/f-manager/pic-manager.html"))
   $inclusion="themes/$Default_Theme/html/modules/f-manager/pic-manager.html";
elseif (file_exists("themes/default/html/modules/f-manager/pic-manager.html"))
   $inclusion="themes/default/html/modules/f-manager/pic-manager.html";
else
   echo "html/modules/f-manager/pic-manager.html manquant / not find !";

if ($inclusion) {
   $browse = isset($browse) ? $browse : '';
   $Xcontent=join('',file($inclusion));
   $Xcontent=str_replace('_nuke',$nuke_url,$Xcontent);
   $Xcontent=str_replace('_back',extend_ascii($cur_nav_href_back),$Xcontent);
   $Xcontent=str_replace('_refresh','<a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;FmaRep='.$FmaRep.'&amp;browse='.rawurlencode($browse).'"><i class="bi bi-arrow-clockwise fs-1 d-sm-none" title="'.fma_translate("Rafraîchir").'" data-bs-toggle="tooltip"></i><span class="d-none d-sm-block mt-2">'.fma_translate("Rafraîchir").'</span></a>',$Xcontent);
   $Xcontent=str_replace('_nb_subdir',($obj->Count('d')-$dir_minuscptr),$Xcontent);
   if(($obj->Count('d')-$dir_minuscptr)==0)
      $Xcontent=str_replace('_classempty','collapse',$Xcontent);
   $Xcontent=str_replace('_subdirs',$subdirs,$Xcontent);
   $Xcontent = ($uniq_fma) ?
      str_replace('_fileM','<a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=f-manager&amp;FmaRep='.$FmaRep.'&amp;browse='.rawurlencode($browse).'"><i class="bi bi-folder fs-1 d-sm-none" data-bs-toggle="tooltip" title="'.fma_translate("Gestionnaire de fichiers").'"></i><span class="d-none d-sm-block mt-2">'.fma_translate("Gestionnaire de fichiers").'</span></a>',$Xcontent) :
      str_replace('_fileM','',$Xcontent) ;
   $Xcontent = (isset($files)) ? str_replace('_files',$files,$Xcontent) : str_replace('_files','',$Xcontent) ;

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
            $theme = (array_key_exists(0, $ibix)) ? $theme=$ibix[0] : $Default_Theme ;
            $skin = (array_key_exists(1, $ibix)) ? $skin=$ibix[1] : $skin=$Default_skin ;
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
         <link href="'.$css_fma.'" title="default" rel="stylesheet" type="text/css" media="all" />
         <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

      </head>
      <body class="p-3">';
   }
   else
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

   echo meta_lang(aff_langue($Xcontent));

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
   if (!$NPDS_fma)
      echo '
      </body>
   </html>';
   else
      include ("footer.php");
}
?>

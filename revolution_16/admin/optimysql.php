<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* M. PASCAL aKa EBH (plan.net@free.fr)                                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   if (!stristr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
   $f_meta_nom ='OptimySQL';
   $f_titre = adm_translate("Optimisation de la base de données").' : '.$dbname;
   //==> controle droit
   admindroits($aid,$f_meta_nom);
   //<== controle droit

   $date_opt = date(adm_translate("dateforop"));
   $heure_opt = date("h:i a");
   include("header.php");
   GraphicAdmin($hlpfile);
   global $dbname;

   // Création de la table optimy (si nécessaire)
   $result = sql_query("CREATE TABLE IF NOT EXISTS ".$NPDS_Prefix."optimy (optid INT(11) NOT NULL auto_increment, optgain DECIMAL(10,3), optdate VARCHAR (11) DEFAULT '', opthour VARCHAR (8) DEFAULT '', optcount INT(11) DEFAULT '0', PRIMARY KEY (optid))");
   // Insertion de valeurs d'initialisation de la table (si nécessaire)
   $result = sql_query("SELECT optid FROM ".$NPDS_Prefix."optimy");
   list($idopt) = sql_fetch_row($result);
   if(!$idopt OR ($idopt == ''))
       $result = sql_query("INSERT INTO ".$NPDS_Prefix."optimy (optid, optgain, optdate, opthour, optcount) VALUES ('1', '', '', '', '0')");
   // Extraction de la date et de l'heure de la précédente optimisation
   $last_opti='';
   $result = sql_query("SELECT optdate, opthour FROM ".$NPDS_Prefix."optimy WHERE optid='1'");
   list($dateopt, $houropt) = sql_fetch_row($result);
   if (!$dateopt OR ($dateopt == '') OR !$houropt OR ($houropt == '')) {
   } else {
      $last_opti= adm_translate("Dernière optimisation effectuée le")." : ".$dateopt." ".adm_translate(" à ")." ".$houropt."<br />\n";
   }

   $tot_data = 0;
   $tot_idx = 0;
   $tot_all = 0;
   $li_tab_opti='';
   // si optimysql n'affiche rien - essayer avec la ligne ci-dessous
   //$result = sql_query("SHOW TABLE STATUS FROM `$dbname`";);
   $result = sql_query("SHOW TABLE STATUS FROM ".$dbname);
   if (sql_num_rows($result)) {
      while($row = sql_fetch_assoc($result)) {
         $tot_data = $row['Data_length'];
         $tot_idx  = $row['Index_length'];
         $total = ($tot_data + $tot_idx);
         $total = ($total / 1024);
         $total = round($total,3);
         $gain = $row['Data_free'];
         $gain = ($gain / 1024);
         settype($total_gain,'integer');
         $total_gain += $gain;
         $gain = round($gain,3);
         $resultat = sql_query("OPTIMIZE TABLE ".$row['Name']." ");
         if ($gain == 0)
            $li_tab_opti.= '
            <tr class="table-success">
               <td align="right">'.$row['Name'].'</td>
               <td align="right">'.$total.' Ko</td>
               <td align="center">'.adm_translate("optimisée").'</td>
               <td align="center"> -- </td>
            </tr>';
          else
             $li_tab_opti.= '
             <tr class="table-danger">
                <td align="right">'.$row['Name'].'</td>
                <td align="right">'.$total.' Ko</td>
                <td class="text-danger" align="center">'.adm_translate("non optimisée").'</td>
                <td align="right">'.$gain.' Ko</td>
             </tr>';
      }
   }
   $total_gain = round($total_gain,3);

   // Historique des gains
   // Extraction du nombre d'optimisation effectuée
   $result = sql_query("SELECT optgain, optcount FROM ".$NPDS_Prefix."optimy WHERE optid='1'");
   list($gainopt, $countopt) = sql_fetch_row($result);
   $newgain = ($gainopt + $total_gain);
   $newcount = ($countopt + 1);
   // Enregistrement du nouveau gain
   $result = sql_query("UPDATE ".$NPDS_Prefix."optimy SET optgain='$newgain', optdate='$date_opt', opthour='$heure_opt', optcount='$newcount' WHERE optid='1'");
   // Lecture des gains précédents et addition
   $result = sql_query("SELECT optgain, optcount FROM ".$NPDS_Prefix."optimy WHERE optid='1'");
   list($gainopt, $countopt) = sql_fetch_row($result);

   // Affichage
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '<hr /><p class="lead">'.adm_translate("Optimisation effectuée").' : '.adm_translate("Gain total réalisé").' '.$total_gain.' Ko</br>';
   echo $last_opti;
   echo '
   '.adm_translate("A ce jour, vous avez effectué ").' '.$countopt.' optimisation(s) '.adm_translate(" et réalisé un gain global de ").' '.$gainopt.' Ko.</p>
   <table id="tad_opti" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
   <thead>
       <tr>
           <th data-sortable="true" data-halign="center" data-align="center">'.adm_translate('Table').'</th>
           <th data-halign="center" data-align="center">'.adm_translate('Taille actuelle').'</th>
           <th data-sortable="true" data-halign="center" data-align="center">'.adm_translate('Etat').'</th>
           <th data-halign="center" date-align="center">'.adm_translate('Gain réalisable').'</th>
       </tr>
   </thead>
   <tfoot>
       <tr>
           <td></td>
           <td></td>
           <td>'.adm_translate("Gain total réalisé").' : </td>
           <td>'.$total_gain.' Ko</td>
       </tr>
   </tfoot>
   <tbody>';
   echo $li_tab_opti;
   echo '
   </tbody>
   </table>';
   adminfoot('','','','');
   global $aid; Ecr_Log('security', "OptiMySql() by AID : $aid", '');
?>
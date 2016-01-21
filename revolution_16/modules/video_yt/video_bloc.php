<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2008 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module video_yt                                                      */
/* video_bloc file 2007 by jpb                                          */
/*                                                                      */
/* version 2.2 10/07/2012                                               */
/************************************************************************/

$ModPath = 'video_yt';
include ('modules/'.$ModPath.'/video_yt_conf.php');

//<== compatibilitŽ old version of php
if (!function_exists('file_get_contents'))
{
  function file_get_contents($url)
  {
    $temp = "";
    $fp = @fopen ($url, "r");
    if ($fp)
    {
      while ($data=fgets($fp, 1024))
        $temp .= $data;
      fclose ($fp);
    }
    return $temp;
  }
}
//<== compatibilitŽ old version of php


//rŽcupŽration fichier xml de youtube
$stream = file_get_contents("http://gdata.youtube.com/feeds/api/videos?start-index=1&max-results=25&author=$account");
//mise en tableau
preg_match_all('#<(media:title)[^>]*>([^<]*)</\1>#s',$stream,$regs);
  $ar_title=$regs[2];//titre
preg_match_all('#<entry><id>((.*?)(videos/)(.*?))</id>#s',$stream,$regs);
  $ar_id=$regs[4];
preg_match_all('#<media:thumbnail url=\'(.*?)\'#s',$stream,$regs);
  $ar_thumbnail_url=$regs[1];
  $ar_thumbnail_media = array_chunk($ar_thumbnail_url,4);

srand ((double) microtime() * 10000000); // for old php  < 4.2.0...
$vid_ran = array_rand ($ar_id, 1);//the second parameter can be change 1 ou +
$content = '';
$nb = 0;
if (is_array($vid_ran)) {$nb = count($vid_ran);} else {$nb = 1;};
for($i = 0; $i<$nb; $i++)
{
if (is_array($vid_ran)) {$id_ran=$vid_ran[$i];} else {$id_ran=$vid_ran;};
$content.= '<div id ="player_bloc_'.$i.'" title="'.$ar_title[$id_ran].'" >';

}
$content.='<div class="row">
	<div class="col-md-12">
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="http://www.youtube.com/embed/'.$ar_id[$id_ran].'?rel=0&showinfo=0&theme=light&modestbranding=1&fs=0&color=white" allowfullscreen="" frameborder="0"></iframe>
		</div>
	</div>
</div>';

$content .= '</div>';
$content .= '<br /><a href="modules.php?ModPath=video_yt&amp;ModStart=video_yt">[french]Vid&#xE9;oth&#xE8;que[/french][english]Videos[/english][chinese]&#x5F55;&#x5F71;[/chinese]</a> | <a href="http://gdata.youtube.com/feeds/users/'.$account.'/uploads" target="blank"><img style="vertical-align:middle;" src ="modules/'.$ModPath.'/images/standard_rss.png" border="0" alt="RSS icon" />
</a>';
$content = aff_langue($content);
?>
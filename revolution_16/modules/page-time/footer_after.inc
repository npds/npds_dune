<?php
   $time_end = getmicrotime();
   $time = round($time_end - $time_start, 4);
   echo aff_langue("<p id=\"pied\">[french]temps [/french][english]time[/english]: $time [french]seconde(s)[/french][english]second(s)[/english]</p>");
?>
<?php
/************************************************************************/
/* Language File for PUSH ADDON                                         */
/* This PHPNuke ADDON by Christopher Bradford (csb@wpsf.com) 2001       */
/* Be careful when making changes... pda browser platforms such as      */
/* Avantgo and others support as limited HTML set.                      */
//* ===========================                                         */
/*                                                                      */
/* File Copyright (c) 2001 - 2024 by Philippe Brunier                   */
/*                                                                      */
/************************************************************************/

function push_translate($phrase) {
   $tmp=push_translate_pass1($phrase);
   return ($tmp);
}

function push_translate_pass1($phrase) {
 switch($phrase) {
   case "Latest Poll Results": $tmp = "&#x6700;&#x8FD1;&#x8C03;&#x67E5;"; break;
   case "Latest Articles": $tmp = "&#x6700;&#x65B0;&#x6587;&#x7AE0;"; break;
   case "Total Votes:": $tmp = "&#x5408;&#x8BA1;&#x6295;&#x7968;:"; break;
   case "Poll": $tmp = "&#x6295;&#x7968;&#x8C03;&#x67E5;"; break;
   case "Home": $tmp = "&#x56DE;&#x5230;&#x9996;&#x9875;"; break;
   case "Posted by": $tmp = "&#x53D1;&#x8868;&#x8005;"; break;
   case "Member(s)": $tmp = "&#x4F1A;&#x5458;&#x901A;&#x8BAF;&#x5F55;"; break;
   case "Internal": $tmp = "&#x5185;&#x90E8;"; break;
   case "Next": $tmp = "&#x4E0B;&#x4E00;&#x4E2A;"; break;
   case "Web links": $tmp = "&#x7F51;&#x7AD9;&#x94FE;&#x63A5;"; break;

   default: $tmp = "&#x9700;&#x8981;&#x7FFB;&#x8BD1;&#x7A3F; <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
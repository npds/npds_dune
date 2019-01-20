<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
function translate_ml($u_langue, $message) {
  switch($u_langue) {
     case "english":
        switch($message) {
           case "Abonnement": $tmp="Subscribe"; break;
           case "Le titre de la dernière publication est": $tmp="The title of the last new post is"; break;
           case "L'URL pour cet article est : ": $tmp="The URL for this story is: "; break;
           case "Vous recevez ce Mail car vous vous êtes abonné à : ": $tmp="You are receiving this email in accordance to your subscription: "; break;
           case "Sujet": $tmp="Topic"; break;
           case "Forum": $tmp="Forum"; break;
           case "Bonjour": $tmp="Hello"; break;
           case "Une réponse à votre dernier Commentaire a été posté.": $tmp="A reply to your topic has been posted."; break;
           case "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.": $tmp="You are receiving this email because a message you posted on forums has been replied to, and you selected to be notified on this event."; break;
           case "Pour lire la réponse": $tmp="You may view the topic at"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="Click here to read your new message."; break;
           case "Vous avez un nouveau message.": $tmp="You have a new message."; break;
        }
     break;

     case "german":
        switch($message) {
           case "Abonnement": $tmp="Anmelden"; break;
           case "Le titre de la dernière publication est": $tmp="Der Titel des letzten Beitrags ist"; break;
           case "L'URL pour cet article est : ": $tmp="Die URL des letzten Beitrags ist: "; break;
           case "Vous recevez ce Mail car vous vous êtes abonné à : ": $tmp="Sie erhalten diese E-mail im Zusammenhang mit Ihrer Anmeldung: "; break;
           case "Sujet": $tmp="Thema"; break;
           case "Forum": $tmp="Forum"; break;
           case "Bonjour": $tmp="Hallo"; break;
           case "Une réponse à votre dernier Commentaire a été posté.": $tmp="Eine Antwort auf Ihren Beitrag ist eingegangen ."; break;
           case "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.": $tmp="Sie erhalten diese E-mail, weil Sie die benachrichtigt werden wollen, wenn es Antwort(en) auf Ihren Beitrag gibt."; break;
           case "Pour lire la réponse": $tmp="Sie kˆnnen die Antwort(en) ansehen unter"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="Klicken Sie hier, um Ihre neue Nachricht zu lesen."; break;
           case "Vous avez un nouveau message.": $tmp="Sie haben eine neue Nachricht."; break;
        }
     break;

     case "chinese":
        switch($message) {
           case "Abonnement": $tmp="&#x9884;&#x5B9A;&#x8BE5;&#x670D;&#x52A1;"; break;
           case "Le titre de la dernière publication est": $tmp="&#x6700;&#x8FD1;&#x8C03;&#x67E5;"; break;
           case "L'URL pour cet article est : ": $tmp="&#x8FD9;&#x7BC7;&#x6587;&#x7AE0;&#x7684;URL&#x662F;&#xFF1A; : "; break;
           case "Vous recevez ce Mail car vous vous êtes abonné à : ": $tmp="&#x7531;&#x4E8E;&#x60A8;&#x662F;&#x6CE8;&#x518C;&#x7528;&#x6237;&#xFF0C;&#x6240;&#x4EE5;&#x6536;&#x5230;&#x8FD9;&#x5C01;&#x7535;&#x5B50;&#x90AE;&#x4EF6;: "; break;
           case "Sujet": $tmp="&#x4E3B;&#x9898;"; break;
           case "Forum": $tmp="&#x7248;&#x9762;&#x7BA1;&#x7406;"; break;
           case "Bonjour": $tmp="&#x60A8;&#x597D;"; break;
           case "Une réponse à votre dernier Commentaire a été posté.": $tmp="&#x60A8;&#x7684;&#x8BA8;&#x8BBA;&#x8BDD;&#x9898;&#x6536;&#x5230;&#x4E00;&#x4E2A;&#x56DE;&#x590D;"; break;
           /**/case "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.": $tmp="You are receiving this email because a message you posted on forums has been replied to, and you selected to be notified on this event."; break;
           case "Pour lire la réponse": $tmp="&#x9605;&#x8BFB;&#x8BE5;&#x56DE;&#x590D;"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="&#x70B9;&#x51FB;&#x8FD9;&#x91CC;&#x4EE5;&#x6D4F;&#x89C8;&#x60A8;&#x7684;&#x6D88;&#x606F;."; break;
           case "Vous avez un nouveau message.": $tmp="&#x60A8;&#x6536;&#x5230;&#x4E00;&#x6761;&#x65B0;&#x6D88;&#x606F;."; break;
        }
     break;

     case "spanish":
        switch($message) {
           case "Abonnement": $tmp="Suscripcion"; break;
           case "Le titre de la dernière publication est": $tmp="El titulo de la ultima publication es"; break;
           case "L'URL pour cet article est : ": $tmp="El URL para este articlo es : "; break;
           case "Vous recevez ce Mail car vous vous êtes abonné à : ": $tmp="Reciben este correo electronico ya que se suscriberon por : "; break;
           case "Sujet": $tmp="Tema"; break;
           case "Forum": $tmp="Foro"; break;
           case "Bonjour": $tmp="Buenos dias"; break;
           case "Une réponse à votre dernier Commentaire a été posté.": $tmp="Une respuesto a su ultimo comentario a verano fijada."; break;
           case "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.": $tmp="Reciben este correo electronico ya que pidieron informar les en la publicacion de une respuesto."; break;
           case "Pour lire la réponse": $tmp="Para leer la respuesto"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="Pulsar aqui para leer su nuevo mensaje."; break;
           case "Vous avez un nouveau message.": $tmp="Tienen un nuevo mensaje."; break;
        }
     break;

     case "french":
        switch($message) {
           default: $tmp = "$message"; break;
        }
     break;

     default:
        $tmp = "$message";
     break;

  }
//   if (cur_charset=="utf-8") {
//      return utf8_encode($tmp);
//   } else {
     return ($tmp);
//  }
}
?>
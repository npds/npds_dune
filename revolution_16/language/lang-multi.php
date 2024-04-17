<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
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
           case "Notification message privé.": $tmp="Private message notification."; break;
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
           case "Pour lire la réponse": $tmp="Sie können die Antwort ansehen unter"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="Klicken Sie hier, um Ihre neue Nachricht zu lesen."; break;
           case "Vous avez un nouveau message.": $tmp="Sie haben eine neue Nachricht."; break;
           case "Notification message privé.": $tmp="Benachrichtigung über eine private Nachricht."; break;
        }
     break;

     case "chinese":
        switch($message) {
           case "Abonnement": $tmp="预定该服务"; break;
           case "Le titre de la dernière publication est": $tmp="最近调查"; break;
           case "L'URL pour cet article est : ": $tmp="这篇文章的URL是： : "; break;
           case "Vous recevez ce Mail car vous vous êtes abonné à : ": $tmp="由于您是注册用户，所以收到这封电子邮件: "; break;
           case "Sujet": $tmp="主题"; break;
           case "Forum": $tmp="版面管理"; break;
           case "Bonjour": $tmp="您好"; break;
           case "Une réponse à votre dernier Commentaire a été posté.": $tmp="您的讨论话题收到一个回复"; break;
           case "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.": $tmp="You are receiving this email because a message you posted on forums has been replied to, and you selected to be notified on this event."; break;
           case "Pour lire la réponse": $tmp="阅读该回复"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="点击这里以浏览您的消息."; break;
           case "Vous avez un nouveau message.": $tmp="您收到一条新消息."; break;
           case "Notification message privé.": $tmp="私人邮件的通知。"; break;
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
           case "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.": $tmp="Reciben este correo electrónico ya que pidieron informar les en la publicacion de une respuesto."; break;
           case "Pour lire la réponse": $tmp="Para leer la respuesto"; break;
           case "Cliquez ici pour lire votre nouveau message.": $tmp="Pulsar aqui para leer su nuevo mensaje."; break;
           case "Vous avez un nouveau message.": $tmp="Tienen un nuevo mensaje."; break;
           case "Notification message privé.": $tmp="Notificación mensaje privado."; break;
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
    return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
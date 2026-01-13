<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2026 by Philippe Brunier                     */
/* IZ-Xinstall-MAJ v.1.3                                                */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*         : v.1.3 jpb - 2024                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function ins_translate($phrase) {
   switch($phrase) {
      case "Actualiser": $tmp = "刷新"; break;
      case "Administrateur": $tmp = "管理员"; break;
      case "Adresse (URL) de votre site": $tmp = "网站地址(URL)"; break;
      case "Adresse e-mail de l'administrateur": $tmp = "管理员电子邮箱"; break;
      case "Autoriser l'upload dans le répertoire personnel": $tmp = "允许在个人目录中上传"; break;
      case "Autres paramètres": $tmp = "其他参数"; break;
      case "Base de données": $tmp = "数据库"; break;
      case "Bienvenue": $tmp = "欢迎"; break;
      case "caractères au minimum": $tmp = "个字符至少"; break;
      case "Cette mise à jour est uniquement compatible avec ces versions": $tmp = "此更新仅兼容以下版本"; break;
      case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "此选项确认接受GNU/GPL V3许可证并移除某些安装操作结果的显示。"; break;
      case "Cette version de npds définie dans votre fichier config.php est incompatible": $tmp = "您在config.php文件中定义的NPDS版本不兼容"; break;
      case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "网站根目录的绝对物理路径"; break;
      case "Compte Admin": $tmp = "用户访问"; break;
      case "Configuration du module UPload": $tmp = "上传模块配置"; break;
      case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "提示：使用您喜欢的FTP客户端进行这些修改，然后点击'刷新'。"; break;
      case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs": $tmp = "将/logs文件夹内容复制到/slogs文件夹并删除/logs文件夹"; break;
      case "corrects": $tmp = "正确"; break;
      case "Créer": $tmp = "创建"; break;
      case "Droits d'accès du fichier ": $tmp = "文件访问权限 "; break;
      case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "错误：数据库不存在且无法自动创建。您必须手动创建或联系您的托管服务提供商！"; break;
      case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "错误：无法连接到数据库。请检查您的参数！"; break;
      case "est introuvable !": $tmp = "找不到！"; break;
      case "Etape suivante": $tmp = "下一步"; break;
      case "Exemple par défaut ou SI vous ne savez pas": $tmp = "默认示例或如果您不知道"; break;
      case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "示例：/data/www/my_website 或 c:\web\my_website"; break;
      case "Exemples :": $tmp = "示例："; break;
      case "Exemples SI redirection": $tmp = "重定向示例"; break;
      case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "恭喜，您现在拥有了NPDS门户网站。"; break;
      case "Fichier de configuration": $tmp = "配置文件"; break;
      case "Fichier de licence indisponible !": $tmp = "许可证文件不可用！"; break;
      case "Fichier journal de sécurité": $tmp = "安全日志文件"; break;
      case "Fin": $tmp = "结束"; break;
      case "Identifiant": $tmp = "登录名"; break;
      case "incorrects": $tmp = "错误"; break;
      case "Installation automatique": $tmp = "自动安装"; break;
      case "Installation rapide": $tmp = "快速设置"; break;
      case "Intitulé de votre site": $tmp = "网站标题"; break;
      case "J'accepte": $tmp = "我同意"; break;
      case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "使用NPDS需接受GNU GPL许可证条款 "; break;
      case "La base de données a été créée avec succès !": $tmp = "数据库创建成功！"; break;
      case "La base de données a été mise à jour avec succès !": $tmp = "数据库更新成功！"; break;
      case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "数据库创建失败。请检查参数和文件，然后重试。"; break;
      case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "数据库更新失败。请检查参数和文件，然后重试。"; break;
      case "Langue": $tmp = "语言"; break;
      case "Le compte Admin a été modifié avec succès !": $tmp = "管理员账户修改成功！"; break;
      case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "管理员账户修改失败。请检查参数和文件，然后重试。"; break;
      case "Le fichier 'abla.log.php' est introuvable !": $tmp = "文件'abla.log.php'找不到！"; break;
      case "Le fichier 'cache.config.php' est introuvable !": $tmp = "文件'cache.config.php'找不到！"; break;
      case "Le fichier 'config.php' est introuvable !": $tmp = "文件'config.php'找不到！"; break;
      case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "文件'filemanager.conf'找不到！"; break;
      case "Le fichier 'logs/security.log' est introuvable !": $tmp = "文件'logs/security.log'找不到！"; break;
      case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "文件'meta/meta.php'找不到！"; break;
      case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "文件'modules/upload/upload.conf.php'找不到！"; break;
      case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "文件'static/edito_membres.txt'找不到！"; break;
      case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "文件'static/edito.txt'找不到！"; break;
      case "Le fichier de configuration a été écrit avec succès !": $tmp = "配置文件写入成功！"; break;
      case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "配置文件无法修改。请检查文件'config.php'的访问权限，然后重试。"; break;
      case "Le fichier": $tmp = "文件"; break;
      case "Le mot de passe doit contenir au moins un caractère en majuscule.": $tmp = "密码必须包含至少一个大写字母。"; break;
      case "Le mot de passe doit contenir au moins un caractère en minuscule.": $tmp = "密码必须包含至少一个小写字母。"; break;
      case "Le mot de passe doit contenir au moins un caractère non alphanumérique.": $tmp = "密码必须包含至少一个非字母数字字符。"; break;
      case "Le mot de passe doit contenir au moins un chiffre.": $tmp = "密码必须包含至少一个数字。"; break;
      case "Le mot de passe doit contenir": $tmp = "密码必须包含"; break;
      case "les changements de nom de classes et attributs du framework bs 5.2 ne sont corrigées que dans les fichiers ou tables de la base de données affectés par cette mise à jour. Ce qui signifie que quelques classes et attributs resteront à corriger." : $tmp = "bs 5.2框架中的类和属性名称更改仅在此更新影响的数据库文件或表中进行修正。这意味着一些类和属性仍需要手动修正。"; break;
      case "Les deux mots de passe ne sont pas identiques.": $tmp = "两个密码不一致。"; break;
      case "Licence": $tmp = "许可证"; break;
      case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes une mise à jour de votre site.": $tmp = "现在您已将NPDS文件传输到您的互联网托管服务器，本脚本将引导您完成几个步骤，在几分钟内完成网站更新。"; break; 
      case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes votre nouveau portail NPDS.": $tmp = "现在您已将NPDS文件传输到您的互联网托管服务器，本脚本将引导您完成几个步骤，在几分钟内获得新的NPDS门户网站。"; break; 
      case "Merci encore d'avoir choisi": $tmp = "再次感谢您选择"; break;
      case "Mettre à jour": $tmp = "进行更新"; break;
      case "Mise à jour": $tmp = "更新"; break;
      case "Mise à jour interrompue": $tmp = "更新已中止"; break;
      case "Mise à jour terminée": $tmp = "更新完成"; break;
      case "Modification": $tmp = "修改"; break;
      case "Modifier": $tmp = "更改"; break;
      case "Module UPload": $tmp = "上传模块"; break;
      case "Mot de passe": $tmp = "密码"; break;
      case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "不存在，本脚本将尝试为您创建。"; break;
      case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "不要忘记使用FTP客户端删除'install/'目录以及'install.php'文件！"; break;
      case "Nom d'hôte du serveur mySQL": $tmp = "MySQL服务器主机名"; break;
      case "Nom d'utilisateur (identifiant)": $tmp = "用户名(登录名)"; break;
      case "Nom de la base de données": $tmp = "数据库名称"; break;
      case "Nom de votre site": $tmp = "网站名称"; break;
      case "Non permanente": $tmp = "非持久性"; break;
      case "Non": $tmp = "否"; break;
      case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "现在我们将进行数据库表的创建 "; break;
      case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "现在我们将进行数据库更新。建议在继续之前备份数据库！"; break;
      case "Nous allons maintenant procéder à la modification des tables de la base de données " : $tmp = "我们现在将修改数据库表 "; break;
      case "Nouvelle installation": $tmp = "新安装"; break;
      case "NPDS nécessite une version 5.6.0 ou supérieure !": $tmp = "NPDS需要PHP 5.6.0或更高版本！"; break;
      case "Oui": $tmp = "是"; break;
      case "Paramètres de connexion": $tmp = "连接参数"; break;
      case "Permanente": $tmp = "持久性"; break;
      case "Pour cet utilisateur SQL": $tmp = "对于此SQL用户"; break;
      case "Pour éviter les conflits de nom de table sql...": $tmp = "为避免SQL表名冲突..."; break;
      case "Préfixe des tables sql": $tmp = "SQL表前缀"; break;
      case "Premier utilisateur": $tmp = "第一个用户"; break;
      case "Quitter": $tmp = "退出"; break;
      case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "注意：此操作可能需要一些时间。请耐心等待。"; break;
      case "Remarque": $tmp = "备注"; break;
      case "Répertoire de téléchargement": $tmp = "上传目录"; break;
      case "Répertoire de votre site": $tmp = "网站目录"; break;
      case "Répertoire des fichiers temporaires": $tmp = "临时文件目录"; break;
      case "SI installation locale" : $tmp = "如果是本地安装"; break;
      case "Si la base de données": $tmp = "如果数据库"; break;
      case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "如果您的数据库已有表，请在继续之前进行备份！"; break;
      case "Slogan de votre site": $tmp = "网站标语"; break;
      case "souvent identique à l'identifiant": $tmp = "通常与登录名相同"; break;
      case "Suppression": $tmp = "删除"; break;
      case "sur le serveur d'hébergement": $tmp = "在托管服务器上"; break;
      case "Tables préfixées avec : ": $tmp = "使用此前缀的表： "; break;
      case "Taille maxi des fichiers en octets": $tmp = "最大文件大小(字节)"; break;
      case "Thème graphique": $tmp = "网站图形主题"; break;
      case "Type de connexion au serveur mySQL": $tmp = "MySQL服务器连接类型"; break;
      case "Une seconde fois": $tmp = "再次输入"; break;
      case "URL HTTP(S) de votre site": $tmp = "网站HTTP(S) URL"; break;
      case "Valider": $tmp = "确定"; break;
      case "Vérification des fichiers": $tmp = "文件检查"; break;
      case "vers": $tmp = "到"; break;
      case "Version actuelle de PHP": $tmp = "当前PHP版本"; break;
      case "veuillez valider les préférences et les metatags dans l'interface d'administration pour parfaire la mise à jour.": $tmp = "请在管理界面中验证偏好设置和元标签以完成更新过程。"; break;
      case "Vos paramètres personnels": $tmp = "您的个人参数"; break;
      case "Votre version de NPDS est incorrecte, version requise": $tmp = "NPDS版本不正确，需要版本"; break;
      case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "您必须修改文件的访问权限(读/写) "; break;
      default: $tmp = "需要翻译 [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>
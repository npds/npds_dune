<?php
######################################################################
# DUNE by NPDS : Net Portal Dynamic System
# ===================================================
#
# This version name NPDS Copyright (c) 2001-2023 by Philippe Brunier
#
# This file is to configure PHPMailer to send email from NPDS portal
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License.
######################################################################

# Configurer le serveur SMTP
$smtp_host = "";
# Port TCP, utilisez 587 si vous avez activé le chiffrement TLS
$smtp_port = "";
# Activer l'authentification SMTP
$smtp_auth = 0;
# Nom d'utilisateur SMTP
$smtp_username = "";
# Mot de passe SMTP
$smtp_password = "";
# Activer le chiffrement TLS
$smtp_secure = 0;
# Type du chiffrement TLS
$smtp_crypt = "tls";
# DKIM 1 pour celui du dns 2 pour une génération automatique
$dkim_auto = 1;
?>
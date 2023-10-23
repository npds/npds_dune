<?php
######################################################################
# DUNE by NPDS : Net Portal Dynamic System
# ===================================================
#
# This version name NPDS Copyright (c) 2001-2023 by Philippe Brunier
#
# This module is to configure Footer of Email send By NPDS
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License.
######################################################################

# Configurer le serveur SMTP
$smtp_host = "";
# Activer l'authentification SMTP
$smtp_auth = 1;
# Nom d'utilisateur SMTP
$smtp_username = "";
# Mot de passe SMTP
$smtp_password = "";
# Activer le chiffrement TLS
$smtp_secure = 1;
# Type du chiffrement TLS
$smtp_crypt = "tls";
# Port TCP, utilisez 587 si vous avez activé le chiffrement TLS
$smtp_port = 587;
# DKIM 1 pour celui du dns 2 pour une génération automatique
$dkim_auto = 1;
?>
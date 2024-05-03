<?php
######################################################################
# DUNE by NPDS : Net Portal Dynamic System
# ===================================================
#
# This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier
#
# This module is to configure Footer of Email send By NPDS
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License.
######################################################################
settype($message,'string');
$message .= "-----------------------------------------------------
Gestion de Contenu et de Communauté
www.npds.org -:- copyright 2001-".date("Y")."
-----------------------------------------------------";
?>
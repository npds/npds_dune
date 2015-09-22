<?php
/************************************************************************/
/* Module de gestion de galeries pour NPDS                              */
/* ===========================                                          */
/*                                                                      */
/* TD-Galerie Language File Copyright (c) 2004-2005 by Tribal-Dolphin   */
/*                                                                      */
/************************************************************************/

function adm_gal_trans($phrase) {
    switch ($phrase) {
       case "Administration des galeries": $tmp = "Administraci&oacute;n Albumes fotos"; break;
       case "Accueil": $tmp = "Inicio"; break;
       case "Ajouter une catégorie": $tmp = "A&ntilde;adir una categor&iacute;a"; break;
       case "Ajouter une sous-catégorie": $tmp = "A&ntilde;adir una subcategor&iacute;a"; break;
       case "Ajouter des images": $tmp = "A&ntilde;adir im&aacute;genes"; break;
       case "Ajouter une galerie": $tmp = "A&ntilde;adir un Album"; break;
       case "Voir l'arborescence": $tmp = "Ver la estructura"; break;
       case "Configuration": $tmp = "Configuraci&oacute;n"; break;
       case "Nombre de catégories :": $tmp = "Cantidad de categor&iacute;as :"; break;
       case "Nombre de sous-catégories :": $tmp = "Cantidad de subcategor&iacute;as :"; break;
       case "Nombre d'images :": $tmp = "Cantidad de im&aacute;geness :"; break;
       case "Nombre de galeries :": $tmp = "Cantidad de Albumes :"; break;
       case "Informations": $tmp = "Informaci&oacute;nes"; break;
       case "Aucune catégorie trouvée": $tmp = "Ninguna categor&iacute;a encontrada"; break;
       case "Aucune sous-catégorie trouvée": $tmp = "Ninguna subcategor&iacute;a encontrada"; break;
       case "Aucune galerie trouvée": $tmp = "Ningun Album encontrado"; break;
       case "Nom de la catégorie :": $tmp = "Nombre de la categor&iacute;a :"; break;
       case "Nom de la sous-catégorie :": $tmp = "Nombre de la subcategor&iacute;a :"; break;
       case "Nom de la galerie :": $tmp = "Nombre del album :"; break;
       case "Ajouter": $tmp = "A&ntilde;adir"; break;
       case "Valider": $tmp = "Validar"; break;
       case "Cette catégorie existe déjà": $tmp = "Esta categor&iacute;a ya existe"; break;
       case "Erreur lors de l'ajout de la catégorie": $tmp = "Error incluyendo la categor&iacute;a"; break;
       case "Administrateurs": $tmp = "Administradores"; break;
       case "Accès pour :": $tmp = "Acceso para :"; break;
       case "Catégorie parente :": $tmp = "Categor&iacute;a pariente :"; break;
       case "Cette sous-catégorie existe déjà": $tmp = "Esta subcategor&iacute;a ya existe"; break;
       case "Cette galerie existe déjà": $tmp = "Este Album ya existe"; break;
       case "Erreur lors de l'ajout de la sous-catégorie": $tmp = "Error incluyendo la subcategor&iacute;a"; break;
       case "Erreur lors de l'ajout de la galerie": $tmp = "Error incluyendo el album"; break;
       case "Catégorie :": $tmp = "Categor&iacute;a :"; break;
       case "Sous-catégorie": $tmp = "subcategor&iacute;a"; break;
       case "Galerie": $tmp = "Album"; break;
       case "Galerie :": $tmp = "Album :"; break;
       case "Image :": $tmp = "Imagen :"; break;
       case "Description :": $tmp = "Descripci&oacute;n :"; break;
       case "Ce fichier n'est pas un fichier jpg ou gif": $tmp = "Este fichero no es un fichero jpg o gif"; break;
       case "Image ajoutée avec succès": $tmp = "Imagen a&ntilde;adida con &eacute;xito"; break;
       case "Impossible d'ajouter l'image en BDD": $tmp = "Imposible de a&ntilde;adir la imagen en la BDD"; break;
       case "Dimension maximale de l'image en pixels :": $tmp = "Dimensi&oacute;n m&aacute;xima de la imagen en pixeles:"; break;
       case "Dimension maximale de la miniature en pixels :": $tmp = "Dimensi&oacute;n m&aacute;xima de la miniatura en pixeles:"; break;
       case "Dimension maximale de l'image incorrecte": $tmp = "Dimensi&oacute;n m&aacute;xima incorrecta de la imagen"; break;
       case "Dimension maximale de la miniature incorrecte": $tmp = "Dimensi&oacute;n m&aacute;xima incorrecta de la miniatura"; break;
       case "Nombre d'images par ligne :": $tmp = "Im&aacute;genes por l&iacute;nea :"; break;
       case "Nombre d'images par page :": $tmp = "Im&aacute;genes por p&aacute;gina:"; break;
       case "Choisissez": $tmp = "Elija"; break;
       case "Les anonymes peuvent voter ?": $tmp = "&iquest;Los an&oacute;nimos pueden votar?"; break;
       case "Les anonymes peuvent poster un commentaire ?": $tmp = "&iquest;Los an&oacute;nimos pueden comentar?"; break;
       case "Les anonymes peuvent envoyer des E-Cartes ?": $tmp = "&iquest;Los an&oacute;nimos pueden enviar E-tarjetas?"; break;
       case "Nombre de commentaires :": $tmp = "Cantidad de comentarios :"; break;
       case "Nombre de votes :": $tmp = "Cantidad de votos :"; break;
       case "Afficher des photos aléatoires ?": $tmp = "&iquest;Mostrar fotograf&iacute;as aleatorias?"; break;
       case "Afficher les derniers ajouts ?": $tmp = "&iquest;Mostrar las &uacute;ltimas a&ntilde;adidas?";break;
       case "Vous allez supprimer la catégorie": $tmp = "La categor&iacute;a ser&aacute; suprimida"; break;
       case "Vous allez supprimer la sous-catégorie": $tmp = "La subcategor&iacute;a ser&aacute; suprimida"; break;
       case "Vous allez supprimer la galerie": $tmp = "El album ser&aacute; suprimido"; break;
       case "Vous allez supprimer une image": $tmp = "La imagen ser&aacute; suprimida"; break;
       case "Confirmer": $tmp = "Confirmar"; break;
       case "Annuler": $tmp = "Cancelar"; break;
       case "Miniature supprimée": $tmp = "Miniatura suprimida"; break;
       case "Miniature non supprimée": $tmp = "Miniatura NO suprimida"; break;
       case "Image supprimée": $tmp = "Imagen suprimida"; break;
       case "Image non supprimée": $tmp = "Imagen NO suprimida"; break;
       case "Enregistrement supprimé": $tmp = "Registro suprimido"; break;
       case "Enregistrement non supprimé": $tmp = "Registro NO suprimido"; break;
       case "Galerie supprimée": $tmp = "Album suprimido"; break;
       case "Galerie non supprimée": $tmp = "Galer&iacute;a NO suprimida"; break;
       case "Sous-catégorie supprimée": $tmp = "Subcategor&iacute;a suprimida"; break;
       case "Sous-catégorie non supprimée": $tmp = "Subcategor&iacute;a NO suprimida"; break;
       case "Catégorie supprimée": $tmp = "Categor&iacute;a suprimida"; break;
       case "Catégorie non supprimée": $tmp = "Categor&iacute;a NO suprimida"; break;
       case "Votes supprimés": $tmp = "Votos suprimidos"; break;
       case "Votes non supprimés": $tmp = "Votos NO suprimidos"; break;
       case "Commentaires supprimés": $tmp = "Comentarios suprimidos"; break;
       case "Commentaires non supprimés": $tmp = "Comentarios NO suprimidos"; break;
       case "Effacer": $tmp = "Suprimir"; break;
       case "Modifier": $tmp = "Modificar"; break;
       case "Nom actuel :": $tmp = "Nombre actual :"; break;
       case "Nouveau nom :": $tmp = "Nuevo nombre :"; break;
       case "Images vues :": $tmp = "Imagenes vistas :"; break;
       case "Afficher les votes ?": $tmp = "&iquest;Mostrar los votos?"; break;
       case "Afficher les commentaires ?": $tmp = "&iquest;Mostrar los comentarios?"; break;
       case "Galerie temporaire": $tmp = "Album Temporal"; break;
       case "Importer des images": $tmp = "Importaci&oacute;n Imagenes"; break;
       case "MAJ ordre": $tmp = "actualizar el orden"; break;
       case "Importer": $tmp = "Importar"; break;
       case "Nombre d'images à afficher dans le top commentaires": $tmp = "Numero de cuadros para exhibir en top comment"; break;
       case "Nombre d'images à afficher dans le top votes": $tmp = "Numero de cuadros para exhibir en top votos"; break;
       case "Notifier par email l'administrateur de la proposition de photos ?": $tmp = "Email notificar al administrador de la proquesta fotos ?"; break;
       case "Exporter une catégorie": $tmp = "Exportar una categoría"; break;
       case "Exporter": $tmp = "Exportar"; break;

       default: $tmp = "Necesita ser traducido <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>
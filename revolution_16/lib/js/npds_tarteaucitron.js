         //<![CDATA[
            // paramétrage de tarteaucitron pour npds //
            tarteaucitron.init({
                "privacyUrl": "", /* Privacy policy url */
                "bodyPosition": "bottom", /* or top to bring it as first element for accessibility */
                "hashtag": "#tarteaucitron", /* Ouverture automatique du panel avec le hashtag */
                "cookieName": "tarteaucitron", /* Cookie name */
                "orientation": "top", /* le bandeau doit être en haut (top) ou en bas (bottom) ? */
                "groupServices": false, /* Group services by category */
                "showDetailsOnClick": true, /* Click to expand the description */
                "serviceDefaultState": "wait", /* Default state (true - wait - false) */
                "showAlertSmall": true, /* afficher le petit bandeau en bas à droite ? */
                "cookieslist": true, /* Afficher la liste des cookies installés ? */
                "closePopup": false, /* Show a close X on the banner */
                "showIcon": true, /* Show cookie icon to manage cookies */
                "iconPosition": "TopRight", /* BottomRight, BottomLeft, TopRight and TopLeft */
                "adblocker": false, /* Afficher un message si un adblocker est détecté */
                "DenyAllCta" : true, /* Show the deny all button */
                "AcceptAllCta" : true, /* Show the accept all button when highPrivacy on */
                "highPrivacy": false, /* désactiver le consentement implicite (en naviguant) ? */
                "alwaysNeedConsent": false, /* Ask the consent for "Privacy by design" services */
                "handleBrowserDNTRequest": false, /* If Do Not Track == 1, disallow all */
                "removeCredit": true, /* supprimer le lien vers la source ? */
                "moreInfoLink": true, /* Show more info link */
                "useExternalCss": false, /* If false, the tarteaucitron.css file will be loaded */
                "useExternalJs": false, /* If false, the tarteaucitron.js file will be loaded */
                "cookieDomain": "", /* Nom de domaine sur lequel sera posé le cookie - pour les multisites / sous-domaines - Facultatif */
                "readmoreLink": "static.php?op=politiqueconf.html&npds=1&metalang=1", /* Change the default readmore link */
                "mandatory": true, /* Show a message about mandatory cookies */
                "mandatoryCta": true, /* Show the disabled accept button when mandatory on */
                "googleConsentMode": true, /* Enable Google Consent Mode v2 for Google ads and GA4 */
                "partnersList": false /* Show the number of partners on the popup/middle banner */
            });
         //]]
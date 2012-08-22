<?php

/*
 * 
 * Appel aux classes utiles
 */

// Gestion globale des filtres
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/FilterManager.php';

// Gestion globale des controllers
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/ControllerManager.php';

// Gestion des dates
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/DateUtils.php';

// Gestion de calculs mathématiques / monaie etc..
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/MathUtils.php';

// Appel à la boite à outils du fwk
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/FwkUtils.php';

// Gestion des emails
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/wamailer-dev/mailer.class.php';

// Classe custom utile uniquement dans le projet (notament pour l'envoi de mails)
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/FwkCustom.php';

/*
 * 
 * Classes facultatives
 * 
require_once PATH_TO_IMPORTANT_FILES.'lib/Utils/ftp.php';
 * 
 */

?>

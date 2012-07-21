<?php

/* CONFIGURATION DE VOTRE SITE INTERNET
  ----------------------------------------------------------------- */

$adminAdresse = "florian.mithieux@supinfo.com";
$adminName = "Florian Mithieux";


if(preg_match("#localhost#", $_SERVER['HTTP_HOST'])){
    $siteURL = "http://localhost:8080/BaseSite/";
    $siteRefUrl = "index.php?content=";
}else{
    $siteURL = "http://YOURURL.fr/";
    $siteRefUrl = "index.php?content=";
}


$ie7 = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7');
$ie8 = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8');

// Connexion a� la base de donnee:
// Voir le fichier Models/PdoConnect.php



?>
<?php

/* Start session and load lib */
session_start();
include('twitteroauth.php');
include('config.inc.php');

/* Créer une connexion twitter avec les accès de l'application */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

/* On détermine quelle sera l'URL de callback. Dans notre cas, il s'agira de la page où se situe le formulaire d'ajout d'un commentaire */
if ($_SERVER['HTTP_REFERER'] != "") {
    $urlRedi = $_SERVER['HTTP_REFERER']; 
}
else {
    $urlRedi = OAUTH_CALLBACK;
}

/* On demande les tokens à Twitter, et on passe l'URL de callback */
$request_token = $connection->getRequestToken($urlRedi);

/* On sauvegarde le tout en session */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

/* On test le code de retour HTTP pour voir si la requête précédente a correctement fonctionné */
switch ($connection->http_code) {
  case 200:
    /* On construit l'URL de callback avec les tokens en params GET */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    echo 'Impossible de se connecter à twitter ... Merci de renouveler votre demande plus tard.';
    break;
}

?>
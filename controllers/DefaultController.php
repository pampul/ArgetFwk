<?php
/*
 * 
 * Page d'accueil du site ou null
 */
/*if ($content == "home" || $content == null) {

    $template = $twig->loadTemplate('views/home.html.twig');
    echo $template->render(array(
        'content' => $_SERVER['REQUEST_URI'],
        'baseUrl' => SITE_URL
    ));
}*/

if(!isset($content)){
    $template = $twig->loadTemplate('views/home.html.twig');
    echo $template->render(array(
        'content' => CURRENT_PAGE,
        'baseUrl' => SITE_URL
    ));
}
?>
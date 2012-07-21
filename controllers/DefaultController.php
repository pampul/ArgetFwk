<?php


/*
 * 
 * Page d'accueil du site ou null
 */
if ($content == "home" || $content == null) {

    $template = $twig->loadTemplate('views/home.html.twig');
    echo $template->render(array(
        'content' => $content,
        'baseUrl' => SITE_URL
    ));
    
}
?>
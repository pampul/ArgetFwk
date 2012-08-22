<?php

/**
 * 
 * Classe regroupant les fonctions de bases présentes dans tous les controllers
 */
class ControllerManager {

    protected function error404Controller() {

        header('Location: ' . SITE_URL . 'url-error/404');
    }

    protected function error404DisplayController() {

        $template = $this->twig->loadTemplate('views/404.html.twig');
        echo $template->render(array(
            'baseUrl' => SITE_URL
        ));
    }

}

?>

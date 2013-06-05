<?php


/**
 *
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */
class DefaultController extends ControllerManager {

  /**
   * Affichage de la page d'accueil
   *
   * @return view
   */
  protected function homeController() {

    $this->renderView('views/home.html.twig');

  }

}

?>
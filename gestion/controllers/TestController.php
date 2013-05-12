<?php

/**
 *
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */
class TestController extends ControllerManager {

  protected function testPageController() {

    var_dump(FwkUtils::getClientIp());
    die();

    $this->renderView('views/test-page.html.twig');
  }

}

?>

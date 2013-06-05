<?php

/**
 * Controller des pages de blog en fonction du template
 *
 * @author Flo
 */
class PageController extends ControllerManager {

  private $parameters;

  /**
   *
   * @param string $template
   */
  public function __construct($template) {
    $this->twig = FwkLoader::getTwigEnvironement();
    if (CONFIG_REQUIRE_BDD)
      $this->em = FwkLoader::getEntityManager();
    $this->fwkClasses = FwkLoader::getFwkEntities();

    $methodName = $template . 'Template';
    if (method_exists($this, $methodName))
      $this->$methodName(); else
      $this->defaultTemplate();
  }

  /**
   *
   * @param array $parameters
   */
  private function setParameters($parameters) {
    $this->parameters = $parameters;
  }

  public function getParameters() {
    return $this->parameters;
  }

  private function defaultTemplate() {
    $parameters = array();


    $this->setParameters($parameters);
  }

}

?>

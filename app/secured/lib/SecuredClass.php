<?php

use Doctrine\ORM\EntityManager;

/**
 * Classe mère regroupant les fonctions principales des scripts
 *
 * @author f.mithieux
 */
class SecuredClass {

  /**
   * EntityManager permettant les interactions Entity-BDD
   *
   * @var EntityManager $em
   */
  protected $em;

  /**
   *
   * @var int $microtimeStart
   */
  protected $microtimeStart;

  /**
   *
   * @param EntityManager $em
   */
  public function __construct(EntityManager $em) {
    $this->em             = $em;
    $this->microtimeStart = microtime(true);
  }

  /**
   *
   * @return int
   */
  protected function getLoadingTime() {

    $result = microtime(true) - $this->microtimeStart;

    return number_format($result, 3);
  }

}

?>

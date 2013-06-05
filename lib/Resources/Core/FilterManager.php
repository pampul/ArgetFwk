<?php

use Doctrine\ORM\EntityManager;

/**
 * Classe regroupant les fonctions de bases présentes dans tous les filtres
 *
 * @author f.mithieux
 */
class FilterManager extends FwkManager {

  /**
   * EntityManager permettant les interactions Entity-BDD
   *
   * @var EntityManager $em
   */
  protected $em;

  public function __construct() {
    if (CONFIG_REQUIRE_BDD)
      $this->em = FwkLoader::getEntityManager();

    if ($this->checkDBConnection())
      $this->execute();
  }

  private function checkDBConnection() {

    try {
      $this->em->getRepository('Resources\Entities\Seo')->findOneBy(array('url' => ''));

      return true;
    } catch (Exception $e) {
      header('Location: ' . SITE_URL_BASE);

      return false;
    }

  }

}

?>

<?php

use Doctrine\ORM\EntityManager;

/**
 * Classe regroupant les fonctions de bases présentes dans tous les filtres
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
        $this->execute();
    }

}

?>

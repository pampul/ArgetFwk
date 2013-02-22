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
            $this->$methodName();
        else
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
        $colLastActus = $this->em->createQueryBuilder()->select('c')
                ->from('Entities\Actualite', 'c')
                ->orderBy('c.id', 'DESC')
                ->setFirstResult(0)
                ->setMaxResults(2)
                ->getQuery()
                ->execute();

        $parameters = array(
            'colLastActus' => $colLastActus
        );


        $this->setParameters($parameters);
    }

}

?>

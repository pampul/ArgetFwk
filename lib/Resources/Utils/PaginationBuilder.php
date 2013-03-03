<?php

/**
 * Classe de génération de pagination
 *
 * @author Arget
 */
class PaginationBuilder {

    private $limit = 4;
    private $currentPage = 0;
    private $urlPrev;
    private $hasUrlPrev = null;
    private $urlNext;
    private $hasUrlNext = null;
    private $totalRows;
    private $nbrPages;
    private $prevPage;
    private $nextPage;

    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

    public function getUrlPrev() {
        return $this->urlPrev;
    }

    public function setUrlPrev($urlPrev) {
        $this->urlPrev = $urlPrev;
    }

    public function getUrlNext() {
        return $this->urlNext;
    }

    public function setUrlNext($urlNext) {
        $this->urlNext = $urlNext;
    }

    public function getTotalRows() {
        return $this->totalRows;
    }

    public function setTotalRows($totalRows) {
        $this->totalRows = $totalRows;
    }

    public function getNbrPages() {
        return $this->nbrPages;
    }

    public function setNbrPages($nbrPages) {
        $this->nbrPages = $nbrPages;
    }

    /**
     * Constructeur de la classe
     * 
     * @param int $limit - Nombre de résultats par page
     * @param int $currentPage - Page actuelle
     * @param string $urlPrev - Url précédente
     * @param string $urlNext - Url suivante
     * @param int $totalRows - Nombre de résultats max
     */
    public function __construct($limit, $urlPrev = '', $urlNext = '') {
        $this->limit = $limit;
        if ($urlPrev == '')
            $this->urlPrev = SITE_URL . GET_PATTERN . '/' . GET_CONTENT;
        else
            $this->urlPrev = $urlPrev;
        $this->urlNext = $urlNext;
    }

    /**
     * Construction de la pagination et retourne en html
     * 
     * @return string
     */
    public function buildSimplePagination() {

        $str = '
        <div class="pagination">    
            <ul>';

        $this->prevPage = $this->currentPage - 1;
        $this->nextPage = $this->currentPage + 1;

        if ($this->nbrPages > 1) {

            $str .= $this->getPreviousPage();

            $str .= $this->getBodyPagination();

            $str .= $this->getNextPage();

            $str .= $this->getTotalRowsHtml();
        }

        $str .= '
            </ul>
        </div>';

        return $str;
    }

    /**
     * Définit la page actuelle et la limite à y appliquer
     * 
     * @param Doctrine\ORM\QueryBuilder $qb
     * @return Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function setLimitOnQueryBuilder(Doctrine\ORM\QueryBuilder $qb) {

        if (isset($_GET['page']))
            $this->currentPage = (int) $_GET['page'];
        else
            $this->currentPage = 1;

        $start = $this->currentPage - 1;

        $qb->setFirstResult($start * $this->limit)
                ->setMaxResults($this->limit);

        $paginator = new Doctrine\ORM\Tools\Pagination\Paginator($qb->getQuery());

        $this->totalRows = count($paginator);
        $this->nbrPages = $this->createNbrPage();
        if ($this->nbrPages < $this->currentPage)
            $this->currentPage = $this->nbrPages;


        return $paginator;
    }

    private function createNbrPage() {
        return ceil($this->totalRows / $this->limit);
    }

    private function getPreviousPage() {

        if ($this->nbrPages > 1 && $this->currentPage > 1) {
            $this->hasUrlPrev = $this->urlPrev . '/page-' . $this->prevPage . $this->urlNext;
            return '
                <li><a href="' . $this->urlPrev . '/page-' . $this->prevPage . $this->urlNext . '" title="Aller à la page -' . $this->prevPage . '-">Précédent</a></li>';
        }
    }

    private function getNextPage() {

        if ($this->nbrPages > 1 && $this->currentPage < $this->nbrPages) {
            $this->hasUrlNext = $this->urlPrev . '/page-' . $this->nextPage . $this->urlNext;
            return '
                <li id="suivant"><a href="' . $this->urlPrev . '/page-' . $this->nextPage . $this->urlNext . '" title="Aller &agrave; la page -' . $this->nextPage . '-"  class="suivant" >Suivant</a></li>';
        }
    }

    private function getTotalRowsHtml() {
        /* return '
          <i>&nbsp;&nbsp;&nbsp; (Total: ' . $this->totalRows . ') </i>'; */
        return '';
    }

    private function getBodyPagination() {

        $str = '';
        $checkin = false;
        $checkNbr = 0;

        for ($i = 1; $i <= $this->nbrPages; $i++) {

            if ($i == $this->currentPage) {

                $str .= ' <li class="paginationSelected disabled" data-value="' . $i . '"><a href="" title="Page existante">' . $i . '</a></li>';
                $checkin = true;
            } else {

                if ($this->prevPage == $i)
                    $str .= ' <li class="paginationItem"><a data-value="' . $i . '" href="' . $this->urlPrev . '/page-' . $i . $this->urlNext . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a></li>';
                elseif ($this->nextPage == $i) {

                    if ($this->nextPage == $this->nbrPages)
                        $str .= ' <li class="paginationItem"><a data-value="' . $i . '" href="' . $this->urlPrev . '/page-' . $i . $this->urlNext . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a></li>';
                    else
                        $str .= ' <li class="paginationItem"><a data-value="' . $i . '" href="' . $this->urlPrev . '/page-' . $i . $this->urlNext . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a></li>';
                } elseif ($i < 2)
                    $str .= ' <li class="paginationItem"><a data-value="' . $i . '" href="' . $this->urlPrev . '/page-' . $i . $this->urlNext . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a></li>';
                elseif ($i >= 2 && $this->nbrPages == $i)
                    $str .= ' <li class="paginationItem"><a data-value="' . $i . '" href="' . $this->urlPrev . '/page-' . $i . $this->urlNext . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a></li>';
                elseif ($i >= 2 && $checkNbr == 0) {
                    $checkNbr = $i;
                    $str .= '<li>... </li>';
                } elseif ($i >= 2 && $checkin == true && $this->currentPage > $checkNbr) {
                    $checkNbr = $this->currentPage++;
                    $str .= '<li>...</li>';
                } else {
                    $str .= '';
                }
            }
        }

        return $str;
    }

    /**
     * Génère les meta nécessaires pour une pagination réussie en terme de SEO
     * @return string
     */
    public function getHeaderRelLinks() {
        
        $str = '';
        if($this->hasUrlPrev != null)
            $str .= '
        <link rel="prev" title="Page précédente - ' . $this->prevPage . '" href="' . $this->hasUrlPrev . '" />';
        if($this->hasUrlNext != null)
            $str .= '
        <link rel="next" title="Page suivante - ' . $this->nextPage . '" href="' . $this->hasUrlNext .  '" />';
        $str .= '
        <link rel="canonical" href="' . SITE_URL . GET_PATTERN . '/' . GET_CONTENT . '" />';
        
        return $str;
        
    }

}

?>

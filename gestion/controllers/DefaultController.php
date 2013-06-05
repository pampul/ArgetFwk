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

    $objGChartPie  = new FwkGChart('Meilleurs ventes', 'best_sales', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '');
    $objGChartLine = new FwkGChart('Vente de produits', 'sales', 'LineChart', $arrayOptions = array('head' => array('Année', 'Premier trait', 'Second trait'), 'body' => array(array('Juillet', 150, 250), array('Aout', 154, 235), array('Septembre', 158, 362), array('Octobre', 175, 289), array('Novembre', 162, 250))), '');

    $this->renderView('views/home.html.twig', array('gChartLine' => $objGChartLine, 'gChartPie' => $objGChartPie));
  }

  protected function aboutController() {

    $this->renderView('views/about.html.twig');
  }

  protected function infosController() {

    $objUser = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);

    $this->renderView('views/infos.html.twig', array('objUser' => $objUser));
  }

  protected function preferencesController() {

    $objUser = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);

    $this->renderView('views/preferences.html.twig', array('objUser' => $objUser));
  }

  protected function privilegesController() {

    $arrayActionButtons = array('edit' => array('link' => 'dashboard/privileges-gestion', 'ajax' => true), 'delete' => array('link' => 'dashboard/privileges-delete', 'ajax' => true));
    $arrayContentTable  = array('privilege' => array('#' => 'id', 'Nom' => 'nom', 'Niveau de privilèges' => 'level'));
    $arraySearchTable   = array('placeholder' => 'Nom ...', 'autocomplete' => true, 'champs' => array('nom'));

    $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
    $objFwkTable->buildHead();
    $objFwkTable->buildBody();
    $objFwkTable->buildSearch($arraySearchTable);
    $objFwkTable->build();

    $this->renderView('views/privileges.html.twig', array('tableFwk' => $objFwkTable));
  }

  protected function statsController() {

    if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {

      $objGChartPie  = new FwkGChart('Meilleurs ventes', 'best_sales', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
      $objGChartPie2 = new FwkGChart('Ventes mensuelles', 'best_sales2', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
      $objGChartLine = new FwkGChart('Vente de produits', 'sales', 'LineChart', $arrayOptions = array('head' => array('Année', 'Premier trait', 'Second trait'), 'body' => array(array('Juillet', 150, 250), array('Aout', 154, 235), array('Septembre', 158, 362), array('Octobre', 175, 289), array('Novembre', 162, 250))), '', array('width' => 80, 'height' => 90));
    } else {

      $objGChartPie  = new FwkGChart('Meilleurs ventes', 'best_sales', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
      $objGChartPie2 = new FwkGChart('Ventes mensuelles', 'best_sales2', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
      $objGChartLine = new FwkGChart('Vente de produits 2', 'sales', 'LineChart', $arrayOptions = array('head' => array('Année', 'Premier trait', 'Second trait'), 'body' => array(array('Juillet', 150, 250), array('Aout', 154, 235), array('Septembre', 158, 362), array('Octobre', 175, 289), array('Novembre', 162, 250))), '', array('width' => 80, 'height' => 90));
    }

    $startDate = DateUtils::Us2Fr(DateUtils::getDateDebutMois(), '-');
    $endDate   = date('d-m-Y');

    $this->renderView('views/stats.html.twig', array('gChartLine' => $objGChartLine, 'gChartPie' => $objGChartPie, 'gChartPie2' => $objGChartPie2, 'startDate' => $startDate, 'endDate' => $endDate));
  }

  protected function logsController() {

    $dirList = FwkLog::getLogFiles();

    $logsDir = FwkUtils::getDirList(PATH_TO_IMPORTANT_FILES . 'logs/');

    $html = '
        <table class="table table-bordered table-hover" id="dynamic-table" name="univers" data-nbrsaved="10">
            <thead>
                <th>Nom du fichier</th>
            </thead>
            <tbody>';
    asort($dirList);
    $dirList = array_reverse($dirList);
    foreach ($dirList as $oneDir) {
      if (FwkUtils::getExtension($oneDir) === 'log')
        $html .= '
                <tr>
                    <td><a class="goToLogFile hand" title="Consulter ce fichier">' . $oneDir . '</a></td>
                </tr>';
    }

    $html .= '
            </tbody>
        </table>';

    $this->renderView('views/logs.html.twig', array('dirList' => $html, 'tabLogs' => $logsDir));
  }

  /**
   * Affichage de la page d'accueil des tickets
   *
   * @return view
   */
  protected function ticketsController() {

    $arrayActionButtons = array('view' => array('link' => 'dashboard/ticket-details', 'ajax' => false), 'delete' => array('link' => 'dashboard/admins-delete'));
    $arrayContentTable  = array('ticket' => array('#' => 'id', 'Admin' => array('class' => 'admin', 'getter' => 'admin', 'method' => 'getAdminName', 'sort' => 'prenom'), 'Type de ticket' => array('class' => 'ticket', 'getter' => 'typeTicket', 'method' => 'getTypeTicket', 'sort' => 'typeTicket'), 'Statut' => array('class' => 'ticket', 'getter' => 'statut', 'method' => 'getStatut', 'sort' => 'statut'), 'Date' => 'date', 'Titre' => 'titre'));
    $arraySearchTable   = array('placeholder' => 'Titre/statut ...', 'autocomplete' => true, 'champs' => array('titre', 'statut'));

    $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
    $objFwkTable->buildHead();
    $objFwkTable->buildBody();
    $objFwkTable->buildSearch($arraySearchTable);
    $objFwkTable->build();

    $this->renderView('views/tickets.html.twig', array('tableFwk' => $objFwkTable));
  }

  protected function ticketDetailsController() {

    if (isset($_GET['id']) && isset($_SESSION['admin']['id'])) {

      $objAdmin  = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);
      $objTicket = $this->em->getRepository('Resources\Entities\Ticket')->find($_GET['id']);

      if ($objAdmin->getPrivilege()->getLevel() > 8 || $objTicket->getAdmin()->getId() === $objAdmin->getId()) {

        $colReponses = $this->em->getRepository('Resources\Entities\Reponse')->findBy(array('ticket' => $objTicket->getId()));

        $this->renderView('views/ticket-details.html.twig', array('objTicket' => $objTicket, 'objTicketOwner' => $objTicket->getAdmin(), 'colReponses' => $colReponses, 'objAdmin' => $objAdmin));
      } else
        header('Location: ' . SITE_URL . 'dashboard/tickets');
    } else
      header('Location: ' . SITE_URL . 'dashboard/tickets');
  }


  protected function configController() {

    $this->renderView('views/config.html.twig', array());

  }

  protected function toolBoxController() {

    $this->renderView('views/tool-box.html.twig', array());

  }


  protected function seoController() {

    $arrayActionButtons = array('edit' => array('link' => 'dashboard/seo-gestion', 'ajax' => true), 'delete' => array('link' => 'dashboard/seo-delete', 'ajax' => true));
    $arrayContentTable  = array('seo' => array('#' => 'id', 'URL' => 'url', 'Titre' => 'titre', 'Description' => 'description'));
    $arraySearchTable   = array('placeholder' => 'Url ou titre ...', 'autocomplete' => true, 'champs' => array('url', 'titre'));

    $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
    $objFwkTable->buildHead();
    $objFwkTable->buildBody();
    $objFwkTable->buildSearch($arraySearchTable);
    $objFwkTable->build();

    $this->renderView('views/seo.html.twig', array('tableFwk' => $objFwkTable));
  }

  protected function blogPostController() {

    $arrayActionButtons = array('edit' => array('link' => 'dashboard/blog-post-gestion', 'ajax' => false), 'delete' => array('link' => 'dashboard/blog-post-delete', 'ajax' => true), 'SortSup' => array('Statut ...' => array('class' => 'blogPost', 'classMethod' => 'statut', 'repositoryMethod' => 'getSelectStatut')));
    $arrayContentTable  = array('blogPost' => array('#' => 'id', 'Date d\'ajout' => array('class' => 'blogPost', 'getter' => 'dateAdd', 'method' => 'getDate', 'sort' => 'dateAdd'), 'Auteur' => array('class' => 'admin', 'getter' => 'admin', 'method' => 'getAdminName', 'sort' => 'prenom'), 'URL' => array('class' => 'blogPost', 'getter' => 'seoUrl', 'method' => 'getUrl', 'sort' => 'seoUrl'), 'Titre' => 'titre', 'Statut' => array('class' => 'blogPost', 'getter' => 'statut', 'method' => 'getStatut', 'sort' => 'statut')));
    $arraySearchTable   = array('placeholder' => 'Url, titre ...', 'autocomplete' => true, 'champs' => array('seoUrl', 'titre', 'seoTitle', 'seoDescription'));

    $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
    $objFwkTable->buildHead();
    $objFwkTable->buildBody();
    $objFwkTable->buildSearch($arraySearchTable);
    $objFwkTable->build();

    $this->renderView('views/blog-post.html.twig', array('tableFwk' => $objFwkTable));
  }


  /**
   * Affichage de la page d'édition de posts
   *
   * @return view
   */
  protected function blogPostGestionController() {

    $objBlogPost = null;
    $blogPostAdd = false;
    if (isset($_GET['id'])) {
      if (is_numeric($_GET['id']))
        $objBlogPost = $this->em->getRepository('Resources\Entities\BlogPost')->find($_GET['id']);
    }

    if (isset($_POST['titre'])) {
      extract($_POST);
      // On est en ajout
      if (!isset($objBlogPost)) {
        $blogPostUnset = true;
        $blogPostAdd   = true;
        $objBlogPost   = new Resources\Entities\BlogPost;
        $objBlogPost->setDateAdd(new DateTime("now", new DateTimeZone('Europe/Paris')));
      } else {
        // On est en édition : suppression de la dernière révision si plus de 15
        $this->dealWithPostRevisions($objBlogPost, $texte);
      }

      $objBlogPost->setDateEdit(new DateTime("now", new DateTimeZone('Europe/Paris')));
      $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);

      $objBlogPost->setAdmin($objAdmin);
      if (strlen($seoTitle) > 3)
        $objBlogPost->setSeoTitle($seoTitle); else
        $objBlogPost->setSeoTitle($titre);
      if (strlen($seoH1) > 3)
        $objBlogPost->setSeoH1($seoH1); else
        $objBlogPost->setSeoH1($titre);
      if (strlen($seoDescription) > 3)
        $objBlogPost->setSeoDescription($seoDescription); else
        $objBlogPost->setSeoDescription(FwkUtils::couperTexte(160, $texte));
      if (isset($seoUrl) && strlen($seoUrl) > 3)
        $objBlogPost->setSeoUrl($this->setSeoURl(FwkUtils::urlAlizeAllowSlash($seoUrl), $objBlogPost)); elseif (isset($seoUrl))
        $objBlogPost->setSeoUrl($this->setSeoURl(FwkUtils::urlAlizeAllowSlash($titre), $objBlogPost)); elseif ($blogPostAdd)
        $objBlogPost->setSeoUrl($this->setSeoURl(FwkUtils::urlAlizeAllowSlash($titre), $objBlogPost));

      $objBlogPost->setStatut($statut);
      $objBlogPost->setTemplateUrl($templateUrl);
      $objBlogPost->setTitre($titre);
      $objBlogPost->setTexte($texte);

      if ($category != "none")
        $objBlogPost->setBlogCategory($this->em->getRepository('Resources\Entities\BlogCategory')->find($category)); else
        $objBlogPost->setBlogCategory(null);

      $this->em->persist($objBlogPost);
      $this->em->flush();

      if ($actionType == 'save') {
        if (!isset($blogPostUnset))
          header('Location: ' . SITE_CURRENT_URI); else
          header('Location: ' . SITE_CURRENT_URI . '/' . $objBlogPost->getId());
      } else
        header('Location: ' . SITE_URL . 'dashboard/blog-post');
    } else {
      $colCategorys = $this->em->getRepository('Resources\Entities\BlogCategory')->findAll();
      $this->renderView('views/blog-post-gestion.html.twig', array('objBlogPost' => $objBlogPost, 'listTemplates' => BlogManager::getTemplates(), 'colCategorys' => $colCategorys));
    }
  }

  private function setSeoURl($str, $objBlogPost) {

    $i      = 0;
    $newStr = $str;
    while (!$this->getQuerySeoUrl($newStr, $objBlogPost->getId())) {
      $i++;
      if ($i > 30) {
        $this->error500Controller();
        die();
      }
      $newStr = $str . '-' . $i;
    }

    return $newStr;

  }

  private function getQuerySeoUrl($str, $id) {

    $qb = $this->em->createQueryBuilder();
    $qb->select('bp')->from('Resources\Entities\BlogPost', 'bp');

    if (is_null($id))
      $qb->where('bp.seoUrl = :seoUrl')->setParameter('seoUrl', $str); else
      $qb->where('bp.seoUrl = :seoUrl AND bp.id != :id')->setParameter('seoUrl', $str)->setParameter('id', $id);

    $arrayResult = $qb->getQuery()->getArrayResult();

    if (count($arrayResult) > 0)
      return false; else
      return true;

  }

  private function dealWithPostRevisions($objBlogPost, $content) {

    $qb = $this->em->createQueryBuilder();
    $qb->select('count(bpr.id)')->from('Resources\Entities\BlogPostRevision', 'bpr')->where('bpr.blogPost=:blogpostid')->setParameter('blogpostid', $objBlogPost->getId());

    $count = $qb->getQuery()->getSingleScalarResult();

    // Si plus grand que 15, on delete le dernier
    if ($count >= 20) {
      $qb = $this->em->createQueryBuilder();
      $qb->select('bpr')->from('Resources\Entities\BlogPostRevision', 'bpr')->where('bpr.blogPost=:blogpostid')->orderBy('bpr.dateAdd', 'DESC')->setMaxResults(1)->setParameter('blogpostid', $objBlogPost->getId());

      $lastPostRevision = $qb->getQuery()->getSingleResult();
      $this->em->remove($lastPostRevision);
    }

    $blogPostRevision = new \Resources\Entities\BlogPostRevision();
    $blogPostRevision->setBlogPost($objBlogPost);
    $blogPostRevision->setDateAdd(new DateTime("now", new DateTimeZone('Europe/Paris')));
    $blogPostRevision->setTexte($content);
    $blogPostRevision->setAdmin($this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']));

    $this->em->persist($blogPostRevision);
    $this->em->flush();

  }

  protected function blogCategorieController() {

    $arrayActionButtons = array('edit' => array('link' => 'dashboard/blog-categorie-gestion', 'ajax' => true), 'delete' => array('link' => 'blog-categorie-delete', 'ajax' => true));
    $arrayContentTable  = array('blogCategory' => array('#' => 'id', 'Nom' => 'nom'));
    $arraySearchTable   = array('placeholder' => 'Nom ...', 'autocomplete' => true, 'champs' => array('nom'));

    $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
    $objFwkTable->buildHead();
    $objFwkTable->buildBody();
    $objFwkTable->buildSearch($arraySearchTable);
    $objFwkTable->build();

    $this->renderView('views/blog-categorie.html.twig', array('tableFwk' => $objFwkTable));
  }

  protected function blogPostRevisionController() {

    if (isset($_GET['id'])) {
      if (is_numeric($_GET['id']))
        $objBlogPostRevision = $this->em->getRepository('Resources\Entities\BlogPostRevision')->find($_GET['id']);
    }

    if (!is_object($objBlogPostRevision))
      header('Location: ' . SITE_URL . 'dashboard/blog-post');

    if (isset($_POST['restoreContent'])) {
      $objBlogPost = $objBlogPostRevision->getBlogPost();
      $objBlogPost->setTexte($objBlogPostRevision->getTexte());

      $this->em->persist($objBlogPost);
      $this->em->flush();

      header('Location: ' . SITE_URL . 'dashboard/blog-post-gestion/' . $objBlogPost->getId());
    }

    $this->renderView('views/blog-post-revision.html.twig', array('objBlogPostRevision' => $objBlogPostRevision));

  }

}

?>
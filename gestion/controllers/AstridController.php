<?php

/**
 * 
 * Controller du projet Astrid
 * Le controller doit absolument heriter de ControllerManager
 */
class AstridController extends ControllerManager {

    /**
     * Affichage de la page d'univers
     * 
     * @return view
     */
    protected function universController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/univers-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/univers-delete', 'ajax' => true));
        $arrayContentTable = array('univers' => array('#' => 'id', 'Nom' => 'nom', 'Code Cégid' => 'codeCegid'));
        $arraySearchTable = array('placeholder' => 'Nom\\Code Cegid ...', 'autocomplete' => true, 'champs' => array('nom', 'codeCegid'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/univers.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    /**
     * Affichage de la page type annonces
     * 
     * @return view
     */
    protected function typeAnnonceController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/type-annonce-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/type-annonce-delete', 'ajax' => true));
        $arrayContentTable = array('typeAnnonce' => array('#' => 'id', 'Nom' => 'nom'));
        $arraySearchTable = array('placeholder' => 'Nom ...', 'autocomplete' => true, 'champs' => array('nom'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/type-annonce.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    /**
     * Affichage de la page annonce
     * 
     * @return view
     */
    protected function annonceController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/annonce-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/annonce-delete', 'ajax' => true));
        $arrayContentTable = array('annonce' => array('#' => 'id', 'Type d\'annonce' => array('class' => 'typeAnnonce', 'getter' => 'typeAnnonce', 'method' => 'getTableType', 'sort' => 'nom'), 'Client' => array('class' => 'client', 'getter' => 'client', 'method' => 'getTableNameFirstName', 'sort' => 'nom'), 'Date de dépôt' => 'dateDepot', 'Titre' => 'titre'));
        $arraySearchTable = array('placeholder' => 'Titre ...', 'autocomplete' => true, 'champs' => array('titre'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/annonce.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }

    /**
     * Affichage de la page des clients
     * 
     * @return view
     */
    protected function clientsController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/clients-gestion'), 'delete' => array('link' => 'astrid/clients-delete'));
        $arrayContentTable = array('client' => array('#' => 'id', 'Nom' => 'nom', 'Prénom' => 'prenom', 'Email' => 'email', 'Téléphone' => 'telephone'));
        $arraySearchTable = array('placeholder' => 'Nom/prénom du client ...', 'autocomplete' => true, 'champs' => array('nom', 'prenom'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/clients.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }

    /**
     * Affichage de la page des clients
     * 
     * @return view
     */
    protected function articlesController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/articles-gestion'), 'delete' => array('link' => 'astrid/articles-delete'));
        $arrayContentTable = array('article' => array('#' => 'id', 'Univers' => array('class' => 'univers', 'getter' => 'univers', 'method' => 'getTableName', 'sort' => 'nom'), 'Code Barre' => 'codeBarre', 'Code Article' => 'codeArticle', 'Taille' => 'taille', 'Couleur' => 'couleur', 'Prix' => 'prix'));
        $arraySearchTable = array('placeholder' => 'Code Barre / Code article ...', 'autocomplete' => true, 'champs' => array('codeBarre', 'codeArticle'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/articles.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    /**
     * Affichage de la page annonce
     * 
     * @return view
     */
    protected function livreOrController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/livre-or-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/livre-or-delete', 'ajax' => true));
        $arrayContentTable = array('livreOr' => array('#' => 'id', 'Client' => array('class' => 'client', 'getter' => 'client', 'method' => 'getTableNameFirstName', 'sort' => 'nom'), 'Date de dépôt' => 'dateDepot', 'Texte' => array('class' => 'livreOr', 'getter' => 'texte', 'method' => 'getTexteCutted', 'sort' => 'texte')));
        //$arraySearchTable = array('placeholder' => 'Titre ...', 'autocomplete' => true, 'champs' => array('titre'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons, 10, false);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        //$objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/livre-or.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    /**
     * Affichage de la page annonce
     * 
     * @return view
     */
    protected function newsletterController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/newsletter-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/newsletter-delete', 'ajax' => true));
        $arrayContentTable = array('newsletter' => array('#' => 'id', 'Nom' => 'nom', 'Prenom' => 'prenom', 'Email' => 'email', 'Abonné' => 'abonne'));
        $arraySearchTable = array('placeholder' => 'Email ...', 'autocomplete' => true, 'champs' => array('email'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons, 20);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/newsletter.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    /**
     * Affichage de la page annonce
     * 
     * @return view
     */
    protected function magasinController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/magasin-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/magasin-delete', 'ajax' => true));
        $arrayContentTable = array('magasin' => array('#' => 'id', 'Nom' => 'nom', 'Téléphone' => 'tel', 'Email' => 'email'));
        $arraySearchTable = array('placeholder' => 'Nom ...', 'autocomplete' => true, 'champs' => array('nom'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons, 20);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/magasin.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    
    /**
     * Affichage de la page annonce
     * 
     * @return view
     */
    protected function marqueController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/marque-gestion', 'ajax' => true), 'delete' => array('link' => 'astrid/marque-delete', 'ajax' => true));
        $arrayContentTable = array('marque' => array('#' => 'id', 'Magasin' => array('class' => 'magasin', 'getter' => 'magasin', 'method' => 'getTableName', 'sort' => 'nom'), 'Nom' => 'nom', 'Site Web' => 'url'));
        $arraySearchTable = array('placeholder' => 'Nom ...', 'autocomplete' => true, 'champs' => array('nom'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons, 10);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/marque.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }
    
    
    /**
     * Affichage de la page annonce
     * 
     * @return view
     */
    protected function focusController() {

        $arrayActionButtons = array('edit' => array('link' => 'astrid/focus-gestion', 'ajax' => true), 'delete' => array('link' => 'focus/marque-delete', 'ajax' => true));
        $arrayContentTable = array('focus' => array('#' => 'id', 'Marque' => array('class' => 'marque', 'getter' => 'marque', 'method' => 'getTableName', 'sort' => 'nom'), 'Titre' => 'titre'));
        $arraySearchTable = array('placeholder' => 'Titre ...', 'autocomplete' => true, 'champs' => array('titre'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons, 10);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/astrid/focus.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }

}

?>
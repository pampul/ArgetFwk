<?php

/**
 * 
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */
class PrivateController extends ControllerManager {

    /**
     * Affichage de la page d'accueil des admins
     * 
     * @return view
     */
    protected function adminsController() {

        $arrayActionButtons = array('edit' => array('link' => 'private/admins-gestion'), 'delete' => array('link' => 'private/admins-delete'));
        $arrayContentTable = array('admin' => array('#' => 'id', 'Privilège' => array('class' => 'privilege', 'getter' => 'privilege', 'method' => 'getTableName', 'sort' => 'nom'), 'Nom' => 'nom', 'Prenom' => 'prenom', 'Fonction' => 'fonction', 'Email' => 'email'));
        $arraySearchTable = array('placeholder' => 'Nom, prénom ...', 'autocomplete' => true, 'champs' => array('nom', 'prenom'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/admins.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }

    
}

?>
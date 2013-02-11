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

        $objGChartPie = new FwkGChart('Meilleurs ventes', 'best_sales', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '');
        $objGChartLine = new FwkGChart('Vente de produits', 'sales', 'LineChart', $arrayOptions = array('head' => array('Année', 'Premier trait', 'Second trait'), 'body' => array(array('Juillet', 150, 250), array('Aout', 154, 235), array('Septembre', 158, 362), array('Octobre', 175, 289), array('Novembre', 162, 250))), '');

        $this->renderView('views/home.html.twig', array(
            'gChartLine' => $objGChartLine,
            'gChartPie' => $objGChartPie
                )
        );
    }

    protected function aboutController() {

        $this->renderView('views/about.html.twig');
    }

    protected function infosController() {

        $objUser = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);

        $this->renderView('views/infos.html.twig', array(
            'objUser' => $objUser
                )
        );
    }

    protected function preferencesController() {

        $objUser = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);

        $this->renderView('views/preferences.html.twig', array(
            'objUser' => $objUser
                )
        );
    }

    protected function privilegesController() {

        $arrayActionButtons = array('edit' => array('link' => 'dashboard/privileges-gestion', 'ajax' => true), 'delete' => array('link' => 'dashboard/privileges-delete', 'ajax' => true));
        $arrayContentTable = array('privilege' => array('#' => 'id', 'Nom' => 'nom', 'Niveau de privilèges' => 'level'));
        $arraySearchTable = array('placeholder' => 'Nom ...', 'autocomplete' => true, 'champs' => array('nom'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/privileges.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }

    protected function statsController() {

        if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {

            $objGChartPie = new FwkGChart('Meilleurs ventes', 'best_sales', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
            $objGChartPie2 = new FwkGChart('Ventes mensuelles', 'best_sales2', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
            $objGChartLine = new FwkGChart('Vente de produits', 'sales', 'LineChart', $arrayOptions = array('head' => array('Année', 'Premier trait', 'Second trait'), 'body' => array(array('Juillet', 150, 250), array('Aout', 154, 235), array('Septembre', 158, 362), array('Octobre', 175, 289), array('Novembre', 162, 250))), '', array('width' => 80, 'height' => 90));
        } else {

            $objGChartPie = new FwkGChart('Meilleurs ventes', 'best_sales', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
            $objGChartPie2 = new FwkGChart('Ventes mensuelles', 'best_sales2', 'PieChart', $arrayOptions = array('Fromage' => 15, 'Beurre' => 21, 'Pizza' => 52), '', array('width' => 100, 'height' => 90));
            $objGChartLine = new FwkGChart('Vente de produits 2', 'sales', 'LineChart', $arrayOptions = array('head' => array('Année', 'Premier trait', 'Second trait'), 'body' => array(array('Juillet', 150, 250), array('Aout', 154, 235), array('Septembre', 158, 362), array('Octobre', 175, 289), array('Novembre', 162, 250))), '', array('width' => 80, 'height' => 90));
        }

        $startDate = DateUtils::Us2Fr(DateUtils::getDateDebutMois(), '-');
        $endDate = date('d-m-Y');

        $this->renderView('views/stats.html.twig', array(
            'gChartLine' => $objGChartLine,
            'gChartPie' => $objGChartPie,
            'gChartPie2' => $objGChartPie2,
            'startDate' => $startDate,
            'endDate' => $endDate
                )
        );
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

        $this->renderView('views/logs.html.twig', array(
            'dirList' => $html,
            'tabLogs' => $logsDir
        ));
    }

    /**
     * Affichage de la page d'accueil des tickets
     * 
     * @return view
     */
    protected function ticketsController() {

        $arrayActionButtons = array('view' => array('link' => 'dashboard/ticket-details', 'ajax' => false, 'level' => 8), 'delete' => array('link' => 'dashboard/admins-delete'));
        $arrayContentTable = array('ticket' => array('#' => 'id', 'Admin' => array('class' => 'admin', 'getter' => 'admin', 'method' => 'getAdminName', 'sort' => 'prenom'), 'Type de ticket' => array('class' => 'ticket', 'getter' => 'typeTicket', 'method' => 'getTypeTicket', 'sort' => 'typeTicket'), 'Statut' => array('class' => 'ticket', 'getter' => 'statut', 'method' => 'getStatut', 'sort' => 'statut'), 'Date' => 'date', 'Titre' => 'titre'));
        $arraySearchTable = array('placeholder' => 'Titre/statut ...', 'autocomplete' => true, 'champs' => array('titre', 'statut'));

        $objFwkTable = new FwkTable($arrayContentTable, $arrayActionButtons);
        $objFwkTable->buildHead();
        $objFwkTable->buildBody();
        $objFwkTable->buildSearch($arraySearchTable);
        $objFwkTable->build();

        $this->renderView('views/tickets.html.twig', array(
            'tableFwk' => $objFwkTable
        ));
    }

    protected function ticketDetailsController() {

        if (isset($_GET['id']) && isset($_SESSION['admin']['id'])) {

            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);
            $objTicket = $this->em->getRepository('Resources\Entities\Ticket')->find($_GET['id']);

            if ($objAdmin->getPrivilege()->getLevel() > 8 || $objTicket->getAdmin()->getId() === $objAdmin->getId()) {
                
                $colReponses = $this->em->getRepository('Resources\Entities\Reponse')->findBy(array('ticket' => $objTicket->getId()));

                $this->renderView('views/ticket-details.html.twig', array(
                    'objTicket' => $objTicket,
                    'objTicketOwner' => $objTicket->getAdmin(),
                    'colReponses' => $colReponses,
                    'objAdmin' => $objAdmin
                ));
            }
            else
                header('Location: ' . SITE_URL . 'dashboard/tickets');
        }
        else
            header('Location: ' . SITE_URL . 'dashboard/tickets');
    }
    
    
    protected function configController(){
        
        $this->renderView('views/config.html.twig', array(
                ));
        
    }

}

?>
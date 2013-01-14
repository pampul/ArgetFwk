<?php

use Resources\Entities\Admin;

/**
 * 
 * Controller par defaut
 * Le controller doit absolument heriter de AjaxManager
 */
class DefaultAjax extends AjaxManager {

    /**
     * Test de connexion de l'utilisateur
     */
    protected function loginController() {

        if (isset($_POST['login']) && isset($_POST['password'])) {

            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(
                    array('email' => $_POST['login'])
            );

            if ($objAdmin instanceof Admin) {
                $result = FwkSecurity::comparePassword($_POST['password'], $objAdmin->getPassword(), array('login' => $_POST['login'], 'dir' => '../'));
                if ($result === true)
                    echo 'User checked.';
                else
                    echo $result;
            }
            else
                echo 'Incorrect User.';
        }
    }

    /**
     * Test de connexion de l'utilisateur
     */
    protected function forgetController() {

        if (isset($_POST['login'])) {

            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(
                    array('email' => $_POST['login'])
            );

            if ($objAdmin instanceof Admin) {
                $result = FwkSecurity::multiLoginProtect(array('login' => $_POST['login'], 'dir' => '../', 'seconds' => 300, 'try' => 2), '../logs/forget-pwd/');
                if ($result === true)
                    echo 'User checked.';
                else
                    echo $result;
            }
            else
                echo 'Incorrect User.';
        }
    }

    /**
     * Régénération du body du tableau
     */
    protected function refreshBodyController() {

        extract($_POST);

        // Récupération des colonnes sous forme de tableau
        $value = explode('-', $columns);
        array_pop($value);
        $arrayBodyCheckTable = array();
        foreach ($value as $oneArrayTable) {

            $arrayCheck = explode('=>', $oneArrayTable);
            if (preg_match('#^\(([a-zA-Z0-9]+);([a-zA-Z0-9]+);([a-zA-Z0-9]+);([a-zA-Z0-9]+)\)$#', $arrayCheck[1], $matches)) {
                $arrayBodyCheckTable[$arrayCheck[0]] = array('class' => $matches[1], 'getter' => $matches[2], 'method' => $matches[3], 'sort' => $matches[4]);
            } else {
                $arrayBodyCheckTable[$arrayCheck[0]] = $arrayCheck[1];
            }
        }

        $arrayBodyTable = array($class => $arrayBodyCheckTable);

        if ($sort == '' || $order == '') {
            $sort = 'id';
            $order = 'desc';
        }
        $trueMaxResult = $maxResult;
        if ($sendPagination == 0) {
            $start = 0;
            $maxResult = $nbrSaved;
        } else {
            $start = $pagination * $maxResult;
        }
        $arrayActionButtonsCatched = explode('_#_', $actionButtons);
        $arrayActionButtons = array();
        foreach ($arrayActionButtonsCatched as $oneButton) {
            $arrayOneButton = explode('##', $oneButton);
            if (isset($arrayOneButton[0]) && isset($arrayOneButton[1])) {
                $arrayValues = array();
                foreach (explode('___', $arrayOneButton[1]) as $oneAttr) {
                    $arrayValuesTemp = explode('=>', $oneAttr);
                    $arrayValues[$arrayValuesTemp[0]] = $arrayValuesTemp[1];
                }
                $arrayActionButtons[$arrayOneButton[0]] = $arrayValues;
            }
        }

        $arraySelectsVals = explode('||', $selectsVals);
        array_pop($arraySelectsVals);
        $actionSups = array();
        foreach ($arraySelectsVals as $oneActionSup) {
            $actionSupTmp = array();
            $arrayActions = explode('__', $oneActionSup);
            foreach ($arrayActions as $oneAction) {
                $arrayOneAction = explode('==', $oneAction);
                $actionSupTmp[$arrayOneAction[0]] = $arrayOneAction[1];
            }

            if ($actionSupTmp['value'] != '')
                $actionSups[] = $actionSupTmp;
        }

        $qb = $this->em->createQueryBuilder();

        if (isset($paramIds) && preg_match('#,#', $paramIds)) {

            $arrayParamIds = explode(',', $paramIds);
            array_pop($arrayParamIds);

            $where = '';
            $i = 0;
            foreach ($arrayParamIds as $idProduit) {
                $objItem = $this->em->getRepository((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class))->find($idProduit);
                if (is_object($objItem)) {
                    $i++;
                    if ($i === 1)
                        $where .= 'c.id = ' . $idProduit;
                    else
                        $where .= ' OR c.id = ' . $idProduit;
                }
            }

            if (strlen($data_property) > 0) {

                $qb->select('c')
                        ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                        ->join('c.' . $sort, 'q')
                        ->add('where', $where)
                        ->orderBy('q.' . $data_property, strtoupper($order));
            } else {

                $qb->select('c')
                        ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                        ->add('where', $where)
                        ->orderBy('c.' . $sort, strtoupper($order))
                        ->setFirstResult($start)
                        ->setMaxResults($maxResult);
            }
        } else {
            if ($search == '') {
                if (strlen($data_property) > 0) {

                    $qb->select('c')
                            ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                            ->join('c.' . $sort, 'q')
                            ->orderBy('q.' . $data_property, strtoupper($order))
                            ->setFirstResult($start)
                            ->setMaxResults($maxResult);
                } else {

                    if (sizeof($actionSups) > 0) {

                        $where = '';
                        $i = 0;
                        foreach ($actionSups as $oneActionSup) {
                            $i++;
                            if ($i === 1)
                                $where .= 'c.' . $oneActionSup['method'] . ' = \'' . $oneActionSup['value'] . '\'';
                            else
                                $where .= ' AND c.' . $oneActionSup['method'] . ' = \'' . $oneActionSup['value'] . '\'';
                        }

                        $qb->select('c')
                                ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                                ->add('where', $where)
                                ->orderBy('c.' . $sort, strtoupper($order))
                                ->setFirstResult($start)
                                ->setMaxResults($maxResult);
                    } else {
                        $qb->select('c')
                                ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                                ->orderBy('c.' . $sort, strtoupper($order))
                                ->setFirstResult($start)
                                ->setMaxResults($maxResult);
                    }
                }
            } else {
                $arrayMethods = explode('-', $methods);
                $where = '';
                $sizeArray = sizeof($arrayMethods);
                $i = 0;
                foreach ($arrayMethods as $oneMethod) {
                    if (!empty($oneMethod) && $oneMethod != '') {
                        $i++;
                        if ($i === 1)
                            $where .= 'c.' . $oneMethod . ' LIKE \'%' . $search . '%\'';
                        else
                            $where .= ' OR c.' . $oneMethod . ' LIKE \'%' . $search . '%\'';
                    }
                }

                if ($where != '') {

                    foreach ($actionSups as $oneActionSup) {
                        $where .= ' AND c.' . $oneActionSup['method'] . ' = \'' . $oneActionSup['value'] . '\'';
                    }

                    if (strlen($data_property) > 0) {

                        $qb->select('c')
                                ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                                ->join('c.' . $sort, 'q')
                                ->add('where', $where)
                                ->orderBy('q.' . $data_property, strtoupper($order))
                                ->setFirstResult($start)
                                ->setMaxResults($maxResult);
                    } else {

                        $qb->select('c')
                                ->from((in_array($class, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                                ->add('where', $where)
                                ->orderBy('c.' . $sort, strtoupper($order))
                                ->setFirstResult($start)
                                ->setMaxResults($maxResult);
                    }
                }
            }
        }


        $colObject = $qb->getQuery()->getResult();

        // Si pas de paramètre CSV, on rafraîchit le contenu du tableau
        if ($paramCsv != 'true') {

            $coreFwkTable = new FwkTable($arrayBodyTable, $arrayActionButtons, $trueMaxResult, true, true);
            if ($sendPagination == 1)
                $coreFwkTable->buildBody($colObject, true, $nbrExisting);
            else
                $coreFwkTable->buildBody($colObject, false);

            header("Content-type: text/xml");
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';

            $xml .= '
            <xmlresults>';

            $xml .= '
                <xmlresult>';

            $xml .= '
                    <refreshTable>
                        <![CDATA[' . $coreFwkTable->getBody() . ']]>
                    </refreshTable>';
            $xml .= '
                    <totalRows>' . $coreFwkTable->totalNumberOfResults . '</totalRows>';
            $xml .= '
                    <totalResults>' . $coreFwkTable->totalNumberOfRows . '</totalResults>';

            $xml .= '
                </xmlresult>';

            $xml .= '
            </xmlresults>';

            echo $xml;
        }else {

            // On génère un CSV grâce au colObject, et on retourne son URL.
            $srcFile = FwkTable::getCsvPath($class);

            // Construction de l'en-tête
            $csvHeader = FwkTable::getCsvHead($arrayBodyTable);
            $csvContent = FwkTable::getCsvBody($colObject, $arrayBodyTable);

            $csvFullContent = $csvHeader . $csvContent;

            $fichier = fopen('../' . $srcFile, "w");

            fwrite($fichier, $csvFullContent);

            fclose($fichier);

            echo $srcFile;
        }
    }

    /**
     * Modification d'un champ d'une ligne spécifique en ajax
     */
    protected function modifyLineController() {

        extract($_POST);

        $methodToUse = 'set' . ucfirst($newMethod);

        if ($methodToUse != 'setPassword') {
            $objItem = $this->em->getRepository((in_array(ucfirst($class), $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class))->find($idProduct);
            if (is_object($objItem)) {
                $objItem->$methodToUse($newValue);
                $this->em->persist($objItem);
                $this->em->flush();

                echo 'done.';
            }
            else
                echo 'error.';
        }else {
            if ($newValue != 'password') {
                $objItem = $this->em->getRepository((in_array(ucfirst($class), $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class))->find($idProduct);
                if (is_object($objItem)) {
                    $objItem->$methodToUse(FwkSecurity::encryptPassword($newValue));
                    $this->em->persist($objItem);
                    $this->em->flush();

                    echo 'done.';
                }
                else
                    echo 'error.';
            }
            else
                echo 'error.';
        }
    }

    /**
     * Suppression d'une ligne
     */
    protected function deleteLineController() {

        extract($_POST);

        if (preg_match('#,#', $idProduct)) {
            $arrayIdProduct = explode(',', $idProduct);
            array_pop($arrayIdProduct);

            if (sizeof($arrayIdProduct > 0)) {
                foreach ($arrayIdProduct as $idProduct) {
                    $objItem = $this->em->getRepository((in_array(ucfirst($class), $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class))->find($idProduct);
                    if (is_object($objItem)) {
                        $this->em->remove($objItem);
                        $this->em->flush();
                    }
                }
                echo 'done.';
            }
            else
                echo 'error.';
        } else {

            $objItem = $this->em->getRepository((in_array(ucfirst($class), $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class))->find($idProduct);
            if (is_object($objItem)) {
                $this->em->remove($objItem);
                $this->em->flush();

                echo 'done.';
            }
            else
                echo 'error.';
        }
    }

    /**
     * Affichage de la page d'édition d'admins
     * 
     * @return view
     */
    protected function adminsGestionController() {

        $objAdmin = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);

            // On est en édition
            if (isset($objAdmin)) {
                if ($password != 'password')
                    $objAdmin->setPassword(FwkSecurity::encryptPassword($password));
            } else {
                $objAdmin = new Admin;
                $objAdmin->setPassword(FwkSecurity::encryptPassword($password));
            }

            $objPrivilege = $this->em->getRepository('Resources\Entities\Privilege')->find($privilege);

            $objAdmin->setNom($nom);
            $objAdmin->setPrenom($prenom);
            $objAdmin->setPrivilege($objPrivilege);
            $objAdmin->setFonction($fonction);
            $objAdmin->setEmail($email);
            $this->em->persist($objAdmin);
            $this->em->flush();
        } else {
            $colPrivilege = $this->em->getRepository('Resources\Entities\Privilege')->findAll();
            $this->renderView('views/admins-gestion.html.twig', array(
                'objAdmin' => $objAdmin,
                'colPrivilege' => $colPrivilege
            ));
        }
    }

    /**
     * Renvoi sur le repository demandé afin de checker les champs nécessaires
     */
    protected function sendToRepositoryController() {

        extract($_POST);

        $this->em->getRepository((in_array($repository, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($repository))->$methodRepo();
    }

    /**
     * Affichage de la page d'éditions de privilèges
     * 
     * @return view
     */
    protected function privilegesGestionController() {

        $objPrivilege = null;
        if (isset($_POST['idItem'])) {
            if (is_numeric($_POST['idItem']))
                $objPrivilege = $this->em->getRepository('Resources\Entities\Privilege')->find($_POST['idItem']);
        }

        if (isset($_POST['nom'])) {
            extract($_POST);
            // On est en édition
            if (!isset($objPrivilege)) {
                $objPrivilege = new Resources\Entities\Privilege;
            }

            $objPrivilege->setNom($nom);
            $objPrivilege->setLevel((int) $level);
            $this->em->persist($objPrivilege);
            $this->em->flush();
        } else {
            $this->renderView('views/privileges-gestion.html.twig', array(
                'objUnivers' => $objPrivilege
            ));
        }
    }

    /**
     * Ajout de ticket
     * 
     * @return view
     */
    protected function ticketAddController() {

        if (isset($_SESSION['admin']['id']))
            $idUser = $_SESSION['admin']['id'];
        else
            $idUser = null;

        if (isset($_POST['titre'])) {
            extract($_POST);

            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_POST['idAdmin']);

            $objTicket = new Resources\Entities\Ticket;
            $objTicket->setTitre($titre);
            $objTicket->setTexte($texte);
            $date = new DateTime('@' . time());
            $date->setTimezone(new DateTimeZone(TIMEZONE));
            $objTicket->setDate($date);
            $objTicket->setStatut('ouvert');
            $objTicket->setTypeTicket($typeTicket);
            $objTicket->setAdmin($objAdmin);

            $this->em->persist($objTicket);
            $this->em->flush();
        } else {
            $this->renderView('views/ticket-add.html.twig', array(
                'idAdmin' => $idUser
            ));
        }
    }

    protected function logFilesController() {

        $dirList = FwkLog::getLogFiles(array('dirFiles' => $_POST['dir']));

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

        echo $html;
    }

    protected function logReadFileController() {

        $html = '
        <br/>

        <a class="hand" id="goBack" data-prevdir="' . $_POST['dir'] . '"><i class="icon-arrow-left"></i> Page précédente</a> <span class="separator">·</span> <a id="refresh" class="hand" data-dir="' . $_POST['dir'] . '" data-file="' . $_POST['file'] . '"><i class="icon-refresh"></i> Rafraîchir</a>
        
        <br/><br/>

        <table class="table table-bordered table-hover" id="dynamic-table" name="univers" data-nbrsaved="10">
            <thead>
                <th>Contenu du fichier : ' . $_POST['file'] . '</th>
            </thead>
            <tbody>';

        $arrayLines = FwkLog::getContentLogFile(array('dirFile' => $_POST['dir'], 'fileName' => $_POST['file']));

        foreach ($arrayLines as $oneLine) {
            $html .= '
                <tr>
                    <td>' . $oneLine . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        echo $html;
    }

    protected function refreshReponsesController() {

        $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);
        $objTicket = $this->em->getRepository('Resources\Entities\Ticket')->find($_POST['idTicket']);

        if ($objAdmin->getPrivilege()->getLevel() > 8 || $objTicket->getAdmin()->getId() === $objAdmin->getId()) {

            $colReponses = $this->em->getRepository('Resources\Entities\Reponse')->findBy(array('ticket' => $objTicket->getId()));

            $this->renderView('views/ticket-reponse-listing.html.twig', array(
                'objTicket' => $objTicket,
                'objTicketOwner' => $objTicket->getAdmin(),
                'colReponses' => $colReponses,
                'objAdmin' => $objAdmin
            ));
        }
    }

    protected function addAnswerController() {

        $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);
        $objTicket = $this->em->getRepository('Resources\Entities\Ticket')->find($_POST['idTicket']);

        if ($objAdmin->getPrivilege()->getLevel() > 8 || $objTicket->getAdmin()->getId() === $objAdmin->getId()) {

            // Ajout de la réponse
            $texte = $_POST['texte'];

            $objReponse = new Resources\Entities\Reponse;
            $objReponse->setAdmin($objAdmin);
            $objReponse->setTexte($texte);
            $objReponse->setTicket($objTicket);
            $date = new DateTime('@' . time());
            $date->setTimezone(new DateTimeZone(TIMEZONE));
            $objReponse->setDate($date);

            $this->em->persist($objReponse);
            $this->em->flush();

            if ($_POST['open'] == 'oui') {
                $objTicket->setStatut('ferme');

                $this->em->persist($objTicket);
                $this->em->flush();
            }

            $colReponses = $this->em->getRepository('Resources\Entities\Reponse')->findBy(array('ticket' => $objTicket->getId()));

            $this->renderView('views/ticket-reponse-listing.html.twig', array(
                'objTicket' => $objTicket,
                'objTicketOwner' => $objTicket->getAdmin(),
                'colReponses' => $colReponses,
                'objAdmin' => $objAdmin
            ));
        }
    }

    protected function changeStatutController() {

        $objTicket = $this->em->getRepository('Resources\Entities\Ticket')->find($_POST['idTicket']);

        if ($_POST['statut'] == 'ferme')
            $statut = 'ouvert';
        else
            $statut = 'ferme';

        $objTicket->setStatut($statut);

        $this->em->persist($objTicket);
        $this->em->flush();
    }

    protected function imageUploadController() {

        $objFwkUploader = new FwkUpload(PATH_TO_BACKOFFICE_FILES . 'web/uploads/' . preg_replace('#/#', '', $_POST['upfilepath']) . '/');
        $objFwkUploader->setFileType('image');
        $objFwkUploader->setMaxSize($_POST['upmaxsize']);
        $objFwkUploader->setValidFormats($_POST['upformat']);
        $arrayResult = $objFwkUploader->upload($_FILES[$_POST['upfilename']]);

        if (isset($arrayResult['error'])) {
            FwkLog::add('Erreur durant upload : ' . $arrayResult['error'], PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
        } else {
            if ($_POST['dataPersoId'] != 0) {
                $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_POST['dataPersoId']);
                $objAdmin->setAvatar($_POST['upfilepath'] . '/' . $arrayResult['success']);
                $this->em->persist($objAdmin);
                $this->em->flush();
                echo '1';
            }
            else
                echo $_POST['upfilepath'] . '/' . $arrayResult['success'];
        }
    }

    protected function fileUploadController() {

        $objFwkUploader = new FwkUpload(PATH_TO_BACKOFFICE_FILES . 'web/uploads/' . preg_replace('#/#', '', $_POST['upfilepath']) . '/');
        $objFwkUploader->setFileType('fichier');
        $objFwkUploader->setMaxSize($_POST['upmaxsize']);
        $objFwkUploader->setValidFormats($_POST['upformat']);
        $arrayResult = $objFwkUploader->upload($_FILES[$_POST['upfilename']]);

        if (isset($arrayResult['error'])) {
            FwkLog::add('Erreur durant upload : ' . $arrayResult['error'], PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
        } else {
            if ($_POST['dataPersoId'] != 0) {
                $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_POST['dataPersoId']);
                $objAdmin->setAvatar($_POST['upfilepath'] . '/' . $arrayResult['success']);
                $this->em->persist($objAdmin);
                $this->em->flush();
                echo '1';
            }
            else
                echo $_POST['upfilepath'] . '/' . $arrayResult['success'];
        }
    }

}

?>

<?php

use Doctrine\ORM\EntityManager;

/**
 * Classe permettant de générer dynamiquement des tableaux
 * 
 * @author f.mithieux
 */
class FwkTable extends FwkManager {

    /**
     * Header du tableau
     * 
     * @var string
     */
    private $thead;

    /**
     * Body du tableau
     * 
     * @var string
     */
    private $tbody;

    /**
     * Recherche incluse
     * 
     * @var boolean
     */
    private $tsearch;

    /**
     * Contenu de la recherche
     * 
     * @var string
     */
    private $tsearchcontent;

    /**
     * Pagination incluse
     * 
     * @var boolean
     */
    private $tpagination;

    /**
     * Contenu du tableau
     * 
     * @var string
     */
    private $tcontent;

    /**
     * Boutons d'actions à executer
     * 
     * @var array
     */
    private $actionButtons;

    /**
     * Contenu relatif du tableau (champs appelés, tables demandées ...)
     * 
     * @var array 
     */
    private $arrayContentTable;

    /**
     * Nombre de résultats max
     * 
     * @var int $maxDisplay
     */
    private $limit;

    /**
     * Tri des champs
     * 
     * @var array $order 
     */
    private $order;

    /**
     * Champ de référence
     * 
     * @var string $defineBy
     */
    private $defineBy;

    /**
     * Export CSV ou non
     * 
     * @var boolean $exportCsv 
     */
    private $exportCsv;

    /**
     * Saut de ligne en CSV
     * 
     * @var string  $jumpLine
     */
    private static $jumpLine = "\n";

    /**
     * Nombre total de lignes
     * 
     * @var string 
     */
    private $totalTop;

    /**
     * Nombre total de lignes
     * 
     * @var string 
     */
    private $totalBottom;

    /**
     * Nombre de lignes visibles
     * 
     * @var int
     */
    public $totalNumberOfResults;

    /**
     * Nombre total de lignes de la table
     * 
     * @var int  
     */
    public $totalNumberOfRows;

    /**
     * EntityManager permettant les interactions Entity-BDD
     * 
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * Array regroupant l'ensemble des classes utiles au Fwk
     * 
     * @var array $fwkClasses
     */
    private $fwkClasses;

    /**
     * Nom de la classe nécessaire
     * 
     * @var string
     */
    private $className;

    /**
     * Constructeur de la classe de création de tableaux dynamiques
     * 
     * @param array $arrayContentTable - Ensemble des éléments actifs du tableau
     * @param array $actionButtons - choix des boutons edition, suppression etc.. | ex : $arrayActionButtons = array('edit' => 'private/admins-edit', 'delete' => 'private/admins-delete');
     * @param integer $limit - Nombre de lignes à afficher | ex : 10
     * @param boolean $tsearch - activation de la recherche
     * @param boolean $tpagination - Activation de la pagination
     * @param boolean $exportCsv - activation de l'export CSV
     * @param array $order - Choix de l'ordre de premier affichage | ex : $order = array('orderBy' => 'id', 'orderDir' => 'DESC');
     * @param string $defineBy - Champ auquel on fait référence pour l'edition/suppression | ex : 'id'
     */
    public function __construct($arrayContentTable, $actionButtons = array(), $limit = 10, $tsearch = true, $tpagination = true, $exportCsv = true, $order = array('orderBy' => 'id', 'orderDir' => 'DESC'), $defineBy = 'id') {

        $this->em = FwkLoader::getEntityManager();
        $this->fwkClasses = FwkLoader::getFwkEntities();
        $this->className = ucfirst(key($arrayContentTable));
        $this->thead = '';
        $this->tbody = '';
        $this->tsearch = $tsearch;
        $this->tpagination = $tpagination;
        $this->tcontent = '';
        $this->actionButtons = $actionButtons;
        $this->limit = $limit;
        $this->exportCsv = $exportCsv;
        $this->order = $order;
        $this->defineBy = $defineBy;
        $this->totalTop = '';
        $this->totalBottom = '';
        $this->arrayContentTable = $arrayContentTable;
        $this->setObjAdmin();
    }

    /**
     * Génère le tableau une fois les paramètres rentrés
     */
    public function build() {


        $qb = $this->em->createQueryBuilder();
        $qb->select('count(c)')
                ->from((in_array($this->className, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($this->className), 'c');

        $totalRows = $qb->getQuery()->getSingleScalarResult();
        $this->tcontent = '';

        if (isset($this->actionButtons['SortSup']))
            $this->tcontent .= $this->getSortSupButtons();

        if ($this->tsearch)
            $this->tcontent .= $this->tsearchcontent;


        $this->tcontent .= $this->totalTop;

        $this->tcontent .= $this->buildEffacerCriteres();

        $this->tcontent .= '
            <br/>
            
            
            <table class="table table-bordered table-hover" id="dynamic-table" name="' . $this->className . '" data-nbrsaved="' . $this->limit . '">
            
        ';
        $this->tcontent .= $this->thead;

        $this->tcontent .= '
                <tbody id="refresh-table">
                    ';
        $this->tcontent .= $this->tbody;

        $this->tcontent .= '
                </tbody>
                ';

        $this->tcontent .= '
            </table>
            
        ';

        $this->tcontent .= $this->totalBottom;

        if ($this->tpagination)
            $this->tcontent .= $this->buildPagination($totalRows);

        $this->tcontent .= $this->drawHiddenInputLink();

        $this->tcontent .= '<br/><hr />';
    }

    /**
     * Retourne le tableau dans une string
     * 
     * @return string - tableau complet
     */
    public function show() {

        return $this->tcontent;
    }

    /**
     * Construction du <thead> du tableau
     * 
     * Exemple : array('id' => 'id', 'Nom' => 'nom', 'Prenom' => 'prenom')
     * Créera un thead comprenant l'affichage des id, nom et prenom avec un tri
     * La valeur associee est le nom de la colonne dans la bdd
     * 
     */
    public function buildHead() {

        $cols = '';
        foreach (current($this->arrayContentTable) as $key => $value) {
            if (!is_array($value))
                $cols .= $key . '=>' . $value . '\o/';
            else
                $cols .= $key . '=>(' . $value['class'] . ';' . $value['getter'] . ';' . $value['method'] . ';' . $value['sort'] . ')\o/';
        }

        $this->thead = '
                <thead name="' . $cols . '" id="columns">
                    <tr>
                        <th style="width: 20px;">
                            <input type="checkbox" id="checkAll" />
                        </th>';
        foreach (current($this->arrayContentTable) as $key => $value) {

            if (!is_array($value)) {
                $this->thead .= '
                        <th>
                            ' . $key . '
                            <i class="icon-chevron-up pull-right hand sort" id="sort" name="asc" type="' . $value . '" title="Tri par ' . $key . ' ASC"></i>
                            <i class="icon-chevron-down pull-right hand sort" id="sort" name="desc" type="' . $value . '" title="Tri par ' . $key . ' DESC"></i>
                        </th>';
            } else {
                $this->thead .= '
                        <th>
                            ' . $key . '
                            <i class="icon-chevron-up pull-right hand sort" id="sort" name="asc" type="' . $value['getter'] . '" ' . (key($this->arrayContentTable) != $value['class'] ? 'data-property="' . $value['sort'] . '"' : '') . ' title="Tri par ' . $key . ' ASC"></i>
                            <i class="icon-chevron-down pull-right hand sort" id="sort" name="desc" type="' . $value['getter'] . '" ' . (key($this->arrayContentTable) != $value['class'] ? 'data-property="' . $value['sort'] . '"' : '') . ' title="Tri par ' . $key . ' DESC"></i>
                        </th>';
            }
        }
        $this->thead .= '
                        <th>
                            Actions
                        </th>
                    ';

        $this->thead .= '
                    </tr>
                </thead>
                ';
    }

    /**
     * Construction du body du tableau
     * 
     * IMPORTANT : chaque méthode doit etre marquée avec les majuscules correspondantes (ex : idAttribut et non pas idattribut)
     * 
     */
    public function buildBody($colObject = null, $pagination = false, $nbrExisting = null) {

        if ($pagination == false)
            $lastTr = '<tr id="last-tr" data-order-by="' . $this->order['orderBy'] . '" data-order-dir="' . $this->order['orderDir'] . '" data-max-result="' . $this->limit . '" />';
        else
            $lastTr = '';

        foreach ($this->arrayContentTable as $class => $value) {

            if (!is_array($colObject)) {
                $qb = $this->em->createQueryBuilder();
                $qb->select('c')
                        ->from((in_array($this->className, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($class), 'c')
                        ->orderBy('c.' . $this->order['orderBy'], strtoupper($this->order['orderDir']))
                        ->setFirstResult(0)
                        ->setMaxResults($this->limit);
                $colObject = $qb->getQuery()->getResult();
            }

            $this->buildTotalValues(sizeof($colObject), $class, $nbrExisting);

            if (sizeof($colObject) === 0) {
                $this->tbody = $lastTr;
                return '';
            }

            foreach ($colObject as $objUnique) {

                $methodToUse = 'get' . ucfirst($this->defineBy);
                if (method_exists($objUnique, $methodToUse))
                    $valueSent = $objUnique->$methodToUse();
                else
                    $valueSent = null;

                $this->tbody .= '
                <tr>
                    <td class="checkItemTd">
                        <input type="checkbox" class="checkitem" data-id="' . $valueSent . '" data-class="' . $class . '" />
                    </td>';

                foreach ($value as $key => $oneMethod) {

                    if (!is_array($oneMethod)) {

                        $savedMethod = $oneMethod;
                        $oneMethod = 'get' . ucfirst($oneMethod);
                        if ($savedMethod == 'id')
                            $classActive = '';
                        else
                            $classActive = 'class="modify-item"';

                        $this->tbody .= '
                    <td ' . $classActive . ' data-edit="false" data-class="' . $class . '" data-method="' . $savedMethod . '" ' . (method_exists($objUnique, 'getId') ? 'data-id="' . $objUnique->getId() . '"' : '') . '">
                        ' . $objUnique->$oneMethod() . '
                    </td>';
                    }else {

                        $getter = 'get' . ucfirst($oneMethod['getter']);
                        if (strtolower($class) == strtolower($oneMethod['class']))
                            $this->tbody .= $this->em->getRepository((in_array(ucfirst($oneMethod['class']), $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($oneMethod['class']))->$oneMethod['method']($objUnique);
                        else {
                            if (is_object($objUnique->$getter()))
                                $this->tbody .= $this->em->getRepository((in_array(ucfirst($oneMethod['class']), $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($oneMethod['class']))->$oneMethod['method']($objUnique->$getter());
                            else
                                $this->tbody .= '<td>-</td>';
                        }
                    }
                }

                $this->tbody .=
                        '<td>' .
                        $this->showButtons($valueSent, $class)
                        . '</td>';

                $this->tbody .= '
                </tr>';
            }
        }

        $this->tbody .=
                $lastTr;
    }

    /**
     * Retourne le contenu du tableau après le <tbody>
     * 
     * @return string
     */
    public function getBody() {
        return $this->tbody;
    }

    /**
     * Affiche les boutons "edit" "delete" etc.. en fonction de l'array envoyé
     * 
     * @param array $arrayButtons
     * @return string
     */
    public function showButtons($value = null, $className = null) {

        $resultButtons = '';

        foreach ($this->actionButtons as $oneButton => $arrayVals) {

            switch ($oneButton) {

                case 'edit' :
                    if (!isset($arrayVals['ajax']) || $arrayVals['ajax'] === true || $arrayVals['ajax'] === 'true')
                        $ajaxEdit = 'addEditItem';
                    else
                        $ajaxEdit = '';

                    if ((isset($arrayVals['level']) && ($arrayVals['level'] == "false" || $this->objAdmin->getPrivilege()->getLevel() >= $arrayVals['level'])) || !isset($arrayVals['level']))
                        $resultButtons .= '
                        <a class="btn btn-small ' . $ajaxEdit . '" title="Editer" href="' . $arrayVals['link'] . '/' . $value . '" data-id="' . $value . '" data-class="' . $className . '" >
                            <i class="hand icon-edit" data-url="' . $arrayVals['link'] . '" ></i>
                            Edit.
                        </a>
                        <span class="separator">·</span>';
                    break;

                case 'delete' :
                    if (!isset($arrayVals['ajax']) || $arrayVals['ajax'] === true || $arrayVals['ajax'] === 'true')
                        $ajaxEdit = 'delete-item';
                    else
                        $ajaxEdit = '';

                    if ((isset($arrayVals['level']) && ($arrayVals['level'] == "false" || $this->objAdmin->getPrivilege()->getLevel() >= $arrayVals['level'])) || !isset($arrayVals['level']))
                        $resultButtons .= '
                        <a class="btn btn-small btn-danger ' . $ajaxEdit . '" title="Supprimer" href="' . $arrayVals['link'] . '/' . $value . '" data-id="' . $value . '" data-class="' . $className . '" >
                            <i class="hand icon-trash icon-white" data-url="' . $arrayVals['link'] . '" ></i>
                            Suppr.
                        </a>
                        ';
                    break;

                case 'view' :
                    if (!isset($arrayVals['ajax']) || $arrayVals['ajax'] === true || $arrayVals['ajax'] === 'true')
                        $ajaxEdit = 'addEditItem';
                    else
                        $ajaxEdit = '';

                    if ((isset($arrayVals['level']) && ($arrayVals['level'] == "false" || $this->objAdmin->getPrivilege()->getLevel() >= $arrayVals['level'])) || !isset($arrayVals['level']))
                        $resultButtons .= '
                        <a class="btn btn-small ' . $ajaxEdit . '" title="Voir les détails" href="' . $arrayVals['link'] . '/' . $value . '" data-id="' . $value . '" data-class="' . $className . '" >
                            <i class="hand icon-eye-open" data-url="' . $arrayVals['link'] . '" ></i>
                            Voir
                        </a>
                        <span class="separator">·</span>
                        ';
                    break;
            }
        }

        return $resultButtons;
    }

    /**
     * Construction de la pagination dynamique
     * 
     * @return string
     */
    public function buildPagination($totalRows) {

        if ($totalRows - $this->limit > 0)
            return '
            <div id="pagination">
                <button class="btn sort" id="pagination-button">Afficher plus de résultats</button>
            </div>';
        else
            return '
            <div id="pagination"></div>';
    }

    /**
     * Créer le champ de recherche avec auto-completion
     * 
     * @param array $arrayParams
     * Ce paramètre doit contenir
     * array('placeholder' => 'Nom, prenom ...', 'autocomplete' => true, 'champs' => array('nom', 'prenom'))
     */
    public function buildSearch($arrayParams) {

        $searchMethods = implode('-', $arrayParams['champs']);

        $this->tsearchcontent = '
            <div class="form-search">
                <div class="input-append">
                    <input type="search" class="span2 search-query" placeholder="' . $arrayParams['placeholder'] . '" id="search-' . $arrayParams['autocomplete'] . '" title="' . $searchMethods . '">
                    <button type="submit" class="btn sort" id="search">Rechercher</button>
                     
                </div>
            </div>';
    }

    /**
     * Efface les critères pré-définis
     */
    public function buildEffacerCriteres() {

        $html = '
            <div id="moreItems">';

        if (isset($this->actionButtons['delete']) && 
                (
                    (isset($this->actionButtons['delete']['level']) && 
                            ($this->objAdmin->getPrivilege()->getLevel() >= $this->actionButtons['delete']['level'] || $this->actionButtons['delete']['level'] == 'false')
                    ) 
                    || !isset($this->actionButtons['delete']['level'])
                )
            )
            $html .= '
                <a id="linkDelete" class="btn btn-small" title="Supprimer les objets sélectionnés">
                    <i class="hand icon-trash"></i>
                </a>
                ';

        if ($this->exportCsv)
            $html .= $this->buildExportCsv();

        $html .= '
            </div>
            <div id="effacer" class="sort hand" title="Effacer les critères">
                <i class="icon-chevron-right" ></i> Effacer les critères</i>
            </div>
            <br/>';

        return $html;
    }

    /**
     * Construction des liens edit et delete
     */
    public function drawHiddenInputLink() {

        $value = '';
        foreach ($this->actionButtons as $oneButton => $arrayVals) {
            if (!isset($arrayVals['ajax']) || $arrayVals['ajax'] === true)
                $ajaxEdit = 'true';
            else
                $ajaxEdit = 'false';

            if (isset($arrayVals['level']))
                $level = trim($arrayVals['level']);
            else
                $level = 'false';

            if ($oneButton != "SortSup")
                $value .= $oneButton . '##link=>' . $arrayVals['link'] . '___ajax=>' . $ajaxEdit . '___level=>' . $level . '_#_';
        }


        return '
            <input type="hidden" id="actionButton" value="' . $value . '" />';
    }

    private function buildTotalValues($sizeOfCol, $className, $nbrExisting) {

        if ($nbrExisting != null)
            $sizeOfCol += $nbrExisting;

        $qb = $this->em->createQueryBuilder();
        $qb->select('count(c)')
                ->from((in_array($this->className, $this->fwkClasses) ? 'Resources\\' : '') . 'Entities\\' . ucfirst($className), 'c');

        $totalRows = $qb->getQuery()->getSingleScalarResult();

        $this->totalTop = '
            <br/>

            <div class="pull-right">
                <small><strong>Total : <span id="total-top" data-total="' . $totalRows . '">' . $sizeOfCol . '</span>/<span id="total-top-plus">' . $totalRows . '</span></strong></small>
            </div>';

        $this->totalBottom = '
            <div class="pull-right">
                <small><strong>Total : <span id="total-bottom" data-total="' . $totalRows . '">' . $sizeOfCol . '</span>/<span id="total-bottom-plus">' . $totalRows . '</span></strong></small>
            </div>
            
            ';

        $this->totalNumberOfResults = $sizeOfCol;
        $this->totalNumberOfRows = $totalRows;
    }

    private function buildExportCsv() {

        $html = '
            <a id="exportCsvRefresh" class="btn btn-small" title="Exporter les données en CSV" href="' . SITE_URL . '">
                <i class="export-csv hand"></i>
            </a>';

        return $html;
    }

    /**
     * Retourne le nom du fichier à construire
     * 
     * @param string $class Nom de la classe pour l'export
     * @return string 
     */
    public static function getCsvPath($class) {

        $srcFileTmpName = 'web/files/' . date("Y-m-d") . '_export_' . $class;
        $srcTmp = '';
        $i = 0;
        while (file_exists('../' . $srcFileTmpName . $srcTmp . '.csv')) {
            $i++;
            $j = sprintf("%03d", $i);
            $srcTmp = '_' . $j;
        }
        return $srcFileTmpName . $srcTmp . '.csv';
    }

    /**
     * Créer le header et le contenu général du CSV
     * 
     * @param array $arrayContent
     * @return string
     */
    public static function getCsvHead($arrayContent) {

        $csvHeaderContent = '';
        foreach (current($arrayContent) as $key => $value) {
            $csvHeaderContent .= self::getCleanTextForCsv($key) . ';';
        }

        return $csvHeaderContent . self::$jumpLine;
    }

    /**
     * Créer, à partir de l'ensemble des résultats connus, le contenu du CSV
     * 
     * @param array $colObject
     * @param array $arrayContent
     * @return string
     */
    public static function getCsvBody($colObject, $arrayContent) {

        $csvBodyContent = '';
        foreach ($arrayContent as $class => $value) {

            if (sizeof($colObject) === 0) {
                return $csvBodyContent;
            }

            foreach ($colObject as $objUnique) {
                foreach ($value as $key => $oneMethod) {

                    if (!is_array($oneMethod)) {
                        $oneMethod = 'get' . ucfirst($oneMethod);
                        $csvBodyContent .= self::getCleanTextForCsv($objUnique->$oneMethod()) . ';';
                    } else {
                        $getter = 'get' . ucfirst($oneMethod['getter']);
                        if (strtolower($class) == strtolower($oneMethod['class'])) {
                            $getterClass = 'get' . ucfirst($oneMethod['sort']);
                            $csvBodyContent .= self::getCleanTextForCsv($objUnique->$getterClass()) . ';';
                        } else {
                            $getterClass = 'get' . ucfirst($oneMethod['sort']);
                            $csvBodyContent .= self::getCleanTextForCsv($objUnique->$getter()->$getterClass()) . ';';
                        }
                    }
                }

                $csvBodyContent .= self::$jumpLine;
            }
        }

        return $csvBodyContent;
    }

    private static function getCleanTextForCsv($text) {

        $text = utf8_decode($text);
        $text = preg_replace('#(\r\n|\r|\n)#', ' ', $text);
        return $text;
    }

    private function getSortSupButtons() {

        $html = '
            <div class="pull-right">
            ';

        foreach ($this->actionButtons['SortSup'] as $oneActionSup => $arrayVals) {

            $html .= '
                <select style="width: 150px;" class="sortSelect" id="sortSelect" data-method="' . $arrayVals['classMethod'] . '" data-class="' . $arrayVals['class'] . '" >
                    <option value="">' . $oneActionSup . '</option>';

            $html .= $this->em->getRepository('Entities\\' . $arrayVals['class'])->$arrayVals['repositoryMethod']();

            $html .= '
                </select>';
        }


        $html .= '
            </div>';

        return $html;
    }

}

?>
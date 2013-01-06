<?php

/**
 * Classe de cron de base
 *
 * @author f.mithieux
 */
class cron extends SecuredClass {

    /**
     *
     * @var PDO $pdo
     */
    private $pdo;

    public function execute() {

        $this->startLog();
        /*
         * Code right here
         */
        echo 'Cron en pause car en cours de developpement.';
        //$this->pdoConnect();
        //$val = $this->pdoParseTables();
        //$fp = fopen(PATH_TO_IMPORTANT_FILES . 'lib/Resources/Backup/'.date('Y-m-d').'.bkp', 'w');
        //fwrite($fp, $val);
        //fclose($fp);
        //$this->doctrineParseTables();

        $this->endLog();
    }
    
    /**
     * Fonction de Doctrine retournant l'ensemble des données SQL présentes en base
     */
    private function doctrineParseTables(){
        
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);

        /*
         * Ajout incremental des classes
         */
        /* $classes = array(
          $em->getClassMetadata('Entities\User')
          ); */

        /*
         * Ajout automatique
         */
        $entitiesDir = PATH_TO_IMPORTANT_FILES . 'lib/Entities/';
        $dir = opendir($entitiesDir);
        $classes = array();

        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && !is_dir($entitiesDir . $file) && !preg_match('#Repository#', $file)) {
                $file = preg_replace('#\.php#', '', $file);
                $classes[] = $this->em->getClassMetadata('Entities\\' . $file);
            }
        }

        closedir($dir);
        
        $entitiesDirFwk = PATH_TO_IMPORTANT_FILES . 'lib/Resources/Entities/';
        $dir2 = opendir($entitiesDirFwk);

        while ($file = readdir($dir2)) {
            if ($file != '.' && $file != '..' && !is_dir($entitiesDirFwk . $file) && !preg_match('#Repository#', $file)) {
                $file = preg_replace('#\.php#', '', $file);
                $classes[] = $this->em->getClassMetadata('Resources\Entities\\' . $file);
            }
        }

        closedir($dir2);
        
        $arraySql = $tool->getCreateSchemaSql($classes);
        
        var_dump($arraySql);
        
    }

    private function pdoConnect() {
        $message = 'Connecting to bdd ...';
        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');

        $dsn = preg_replace('#pdo_#', '', PDO_DRIVER) . ':host=' . PDO_HOST . ';dbname=' . PDO_DATABASE_NAME;
        $this->pdo = new PDO($dsn, PDO_USER, PDO_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $message2 = 'Connection complete !';
        FwkLog::add($message2, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');
    }

    private function pdoParseTables() {

        $query = "SHOW tables FROM " . PDO_DATABASE_NAME;
        $results = $this->pdo->query($query);
        
        $str = '
-- PDO SQL Dump --
 
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
 
--
-- Database: `'.PDO_DATABASE_NAME.'`
--
 
-- --------------------------------------------------------

SET foreign_key_checks = 0
';

        while ($aTable = $results->fetch(PDO::FETCH_ASSOC)) {

            $sTable = $aTable['Tables_in_' . PDO_DATABASE_NAME];

            $query = "SHOW CREATE TABLE $sTable";

            $sResult2 = $this->pdo->query($query);

            $aTableInfo = $sResult2->fetch(PDO::FETCH_ASSOC);

            $str .= "\n\n--
-- Donnees de la table `$sTable`
--\n\n";


            $sQuery = "SELECT * FROM $sTable\n";

            $sResult3 = $this->pdo->query($sQuery);

            while ($aRecord = $sResult3->fetch(PDO::FETCH_ASSOC)) {

                // Insert query per record
                $str .= "INSERT INTO $sTable VALUES (";
                $sRecord = "";
                foreach ($aRecord as $sField => $sValue) {
                    $sRecord .= "'$sValue',";
                }
                $str .= substr($sRecord, 0, -1);
                $str .= ");\n";
            }
        }
        
        $str .= '
            SET foreign_key_checks = 1';
        
        return $str;
    }

    private function startLog() {

        $message = '
    

--------------------------------------------------
           LANCEMENT DU CRON - EXPORT BDD
--------------------------------------------------

';

        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');
    }

    private function endLog() {

        $message = '
    

--------------------------------------------------
           CRON TERMINE AVEC SUCCES
--------------------------------------------------

';
        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'cron/');

        echo 'BATCH TERMINE.';
    }

}

?>
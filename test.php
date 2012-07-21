<?php
/*
 * Définition de l'emplacement de la librairie Doctrine
 */
require 'lib/Doctrine/ORM/Tools/Setup.php';

$lib = "/lib/";
Doctrine\ORM\Tools\Setup::registerAutoloadDirectory($lib);


/*
 * Génération de l'entity manager
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("/lib/");

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'port'     => '',
    'dbname'   => 'foo',
    'user'     => 'root',
    'password' => '',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, false);
$em = EntityManager::create($dbParams, $config);
?>
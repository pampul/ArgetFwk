<?php
/*
 * Définition de l'emplacement de la librairie Doctrine
 */
require PATH_TO_IMPORTANT_FILES.'lib/Doctrine/ORM/Tools/Setup.php';

$lib = "/lib/";
Doctrine\ORM\Tools\Setup::registerAutoloadDirectory($lib);


/*
 * Génération de l'entity manager
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("/lib/");

// Récupération de la configuration de PDO (config.php)
$dbParams = array(
    'driver'   => PDO_DRIVER,
    'port'     => PDO_PORT,
    'dbname'   => PDO_DATABASE_NAME,
    'user'     => PDO_USER,
    'password' => PDO_PASSWORD,
);

$config = Setup::createAnnotationMetadataConfiguration($paths, ENV_DEV);
$em = EntityManager::create($dbParams, $config);


?>
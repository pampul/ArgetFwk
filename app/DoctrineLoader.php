<?php

/*
 * Définition de l'emplacement de la librairie Doctrine
 * Génération de l'Entity Manager
 */

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ApcCache,
    Entities\User;

require '/lib/Doctrine/Common/ClassLoader.php';

$doctrineClassLoader = new ClassLoader('Doctrine', '/lib');
$doctrineClassLoader->register();

$entitiesClassLoader = new ClassLoader('Entities', '/lib');
$entitiesClassLoader->register();

$config = new Configuration;
$cache = new ApcCache;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(array('/lib/Entities'));
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir('/lib/Proxies');
$config->setProxyNamespace('Proxies');

$config->setQueryCacheImpl($cache);

$dbParams = array(
    'driver'   => PDO_DRIVER,
    'port'     => PDO_PORT,
    'dbname'   => PDO_DATABASE_NAME,
    'user'     => PDO_USER,
    'password' => PDO_PASSWORD,
);

$em = EntityManager::create($dbParams, $config);

$user = new User();

?>
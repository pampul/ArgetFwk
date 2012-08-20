<?php

/*
 * Définition de l'emplacement de la librairie Doctrine
 * Génération de l'Entity Manager
 */

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager;

require PATH_TO_IMPORTANT_FILES.'lib/Doctrine/Doctrine/Common/ClassLoader.php';

$doctrineClassLoader = new ClassLoader('Doctrine', PATH_TO_IMPORTANT_FILES.'lib/Doctrine');
$doctrineClassLoader->register();

$entitiesClassLoader = new ClassLoader('Entities', PATH_TO_IMPORTANT_FILES.'lib');
$entitiesClassLoader->register();

$config = new Configuration;
//$cache = new ApcCache;
//$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(array(PATH_TO_IMPORTANT_FILES.'lib/Entities'));
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir(PATH_TO_IMPORTANT_FILES.'lib/Doctrine/Doctrine/Proxies');
$config->setProxyNamespace('Proxies');
//$config->setQueryCacheImpl($cache);

$dbParams = array(
    'driver'   => PDO_DRIVER,
    'port'     => PDO_PORT,
    'dbname'   => PDO_DATABASE_NAME,
    'user'     => PDO_USER,
    'password' => PDO_PASSWORD,
);

$em = EntityManager::create($dbParams, $config);

/*
$tool = new \Doctrine\ORM\Tools\SchemaTool($em);

$classes = array(
  $em->getClassMetadata('Entities\User')
);
//$tool->createSchema($classes);
//$tool->dropSchema($classes);
$tool->updateSchema($classes);
*/
/*
$user = new User();
$user->setName("Bobby");
$em->persist($user);
$em->flush();
*/
?>
<?php

define('BACKOFFICE_ACTIVE', 'gestion/');
define('PATH_TO_IMPORTANT_FILES',  __DIR__ . '/../../../../../');
define('PATH_TO_BACKOFFICE_FILES', '../');


/**
 * Appel de la classe HTTP
 */
require_once PATH_TO_IMPORTANT_FILES . 'lib/Resources/Core/HttpCore.php';
require_once PATH_TO_IMPORTANT_FILES . 'app/config.php';
require_once PATH_TO_IMPORTANT_FILES . 'lib/Resources/Core/FwkManager.php';
require_once PATH_TO_IMPORTANT_FILES . 'lib/Resources/Utils/FwkUtils.php';
require_once PATH_TO_IMPORTANT_FILES . 'lib/Resources/Core/FwkUpload.php';


$uploadManager = new FwkUpload( PATH_TO_IMPORTANT_FILES . 'gestion/web/uploads/images/');
$uploadManager->setFileType('image');
$uploadManager->setMaxSize(10000000);
$uploadManager->setValidFormats('jpg,jpeg,png,gif');
$arrayResult = $uploadManager->upload($_FILES['file']);

if (isset($arrayResult['error'])) {
  echo '<span style="color: red;">Erreur lors de l\'upload : ' . $arrayResult['error'] . '</span>';
} else {
  echo '<img src="' . SITE_URL_BASE . 'gestion/web/uploads/images/' . $arrayResult['success'] . '" alt="image" />';
}
?>
<?php

define('BACKOFFICE_ACTIVE', 'gestion/');
define('PATH_TO_IMPORTANT_FILES', '../../');
define('PATH_TO_BACKOFFICE_FILES', '../');

require_once __DIR__ . '/../../../../../app/config.php';
require_once __DIR__ . '/../../../../../lib/Resources/Core/FwkManager.php';
require_once __DIR__ . '/../../../../../lib/Resources/Core/FwkUpload.php';


$uploadManager = new FwkUpload(__DIR__ . '/../../../../web/uploads/files/');
$uploadManager->setFileType('file');
$uploadManager->setMaxSize(10000000);
$uploadManager->setValidFormats('pdf,doc,docx');
$arrayResult = $uploadManager->upload($_FILES['file']);

if (isset($arrayResult['error'])) {
    echo '<span style="color: red;">Erreur lors de l\'upload : ' . $arrayResult['error'] . '</span>';
} else {
    echo '<a href="' . SITE_URL_BASE . 'gestion/web/uploads/files/' . $arrayResult['success'] . '" title="fichier joint">Fichier joint</a>';
}
?>
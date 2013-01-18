<?php

define('BACKOFFICE_ACTIVE', 'gestion/');
define('PATH_TO_IMPORTANT_FILES', '../../');
define('PATH_TO_BACKOFFICE_FILES', '../');

require_once '../../../../../app/config.php';
require_once '../../../../../lib/Resources/Core/FwkManager.php';
require_once '../../../../../lib/Resources/Utils/FwkUtils.php';
require_once '../../../../../lib/Resources/Core/FwkUpload.php';


$uploadManager = new FwkUpload('../../../../web/uploads/images/');
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
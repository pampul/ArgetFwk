<?php
require_once '../../../../AppConfig/config.php';

// files storage folder
$dir = '../../../Web/img/upload/';

$_FILES['file']['type'] = strtolower($_FILES['file']['type']);

if ($_FILES['file']['type'] == 'image/png'
        || $_FILES['file']['type'] == 'image/jpg'
        || $_FILES['file']['type'] == 'image/gif'
        || $_FILES['file']['type'] == 'image/jpeg'
        || $_FILES['file']['type'] == 'image/pjpeg') {
    // setting file's mysterious name
    $nomTemporaire = md5(date('YmdHis')) . '.jpg';
    $file = $dir . $nomTemporaire;

    // copying
    copy($_FILES['file']['tmp_name'], $file);

    // displaying file
    echo '<img src="'.$siteURL.'gestion/Web/img/upload/' . $nomTemporaire . '" />';
}else{
    echo 'Erreur type de fichier incompatible.';
}
?>
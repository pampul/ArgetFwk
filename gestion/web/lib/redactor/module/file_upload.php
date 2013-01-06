<?php

require_once '../../../../AppConfig/config.php';

$dir = '../../../Web/files/upload/';

$extensions = array('.pdf', '.doc', '.docx', '.xls', '.xlsx');
$extension = strrchr($_FILES['file']['name'], '.');

if (in_array($extension, $extensions)) {

    $nomtemporaire = str_replace($extension, "", $_FILES['file']['name']);
    $nameFile = $nomtemporaire;
    $i = 0;
    while (file_exists($dir . $nameFile . $extension)) {

        $nameFile = $nomtemporaire;
        $nameFile = $nameFile . "__" . $i;

        $i++;
    }

    $file = $dir . $nameFile . $extension;

    copy($_FILES['file']['tmp_name'], $file);

    echo '<a href="' . $siteURL . 'gestion/Web/files/upload/' . $_FILES['file']['name'] . '" title="Fichier joint" id="fichier-joint">' . $_FILES['file']['name'] . '</a>';
} else {
    echo 'Erreur type de fichier incompatible.';
}
?>
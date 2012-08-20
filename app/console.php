<?php
session_start();

define('PATH_TO_IMPORTANT_FILES', '../');

require_once 'config.php';
require_once 'DoctrineLoader.php';

if (isset($_POST['login'])) {
    if ($_POST['login'] === ADMIN_LOGIN && $_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['webmaster'] = true;
    } else {
        echo '<br/>Erreur : login et MDP incorrects.';
    }
}


if (isset($_SESSION['webmaster']) && $_SESSION['webmaster'] == true) {

    $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

    /*
     * Ajout incremental des classes
     */
    /* $classes = array(
      $em->getClassMetadata('Entities\User')
      ); */

    /*
     * Ajout automatique
     */
    $entitiesDir = PATH_TO_IMPORTANT_FILES.'lib/Entities/';
    $dir = opendir($entitiesDir);
    $classes = array();
    
    while ($file = readdir($dir)) {
        if ($file != '.' && $file != '..' && !is_dir($entitiesDir . $file)) {
            $file = preg_replace('#\.php#', '', $file);
            $classes[] = $em->getClassMetadata('Entities\\'.$file);
        }
    }

    closedir($dir);

    if (isset($_GET['createschema'])) {
        $tool->createSchema($classes);
        echo '--------------------------------<br/><br/>Schema cree avec succes.<br/><br/>--------------------------------<br/><br/>';
    } elseif (isset($_GET['deleteschema'])) {
        $tool->dropSchema($classes);
        echo '--------------------------------<br/><br/>Schema supprime avec succes.<br/><br/>--------------------------------<br/><br/>';
    } elseif (isset($_GET['updateschema'])) {
        $tool->updateSchema($classes);
        echo '--------------------------------<br/><br/>Schema mis a jour avec succes.<br/><br/>--------------------------------<br/><br/>';
    }else{
        echo 'Bienvenue admin.<br/>
            Vous avez maintenant acces a la generation de la BDD.<br/><br/>--------------------------------<br/>';
    }
    ?>

    <br/>

    - <a href="?createschema=1" title="Creer le schema">Creer le schema</a><br/><br/>
    - <a href="?deleteschema=1" title="Supprimer le schema">Supprimer le schema</a><br/><br/>
    - <a href="?updateschema=1" title="Mettre a jour le schema">Mettre a jour le schema</a><br/>

    <?php
} else {
    ?>

    <form action="" method="POST">
        <br/><br/>
        Merci de vous connecter :<br/>
        <input type="text" name="login" placeholder="Login ..." /><br/><br/>
        <input type="password" name="password" placeholder="Mot de passe ..." /><br><br/>
        <input type="submit" value="Valider" />
    </form>

    <?php
}
?>


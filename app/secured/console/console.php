<?php

/**
 * Classe de console
 *
 * @author f.mithieux
 */
class console extends SecuredClass {

  public function execute() {

    $html = $this->buildHeader();
    $html .= $this->checkLogin();

    if (isset($_SESSION['webmaster']) && $_SESSION['webmaster'] == true) {
      $html .= $this->sessionOpen();
    } else {
      $html .= $this->sessionClosed();
    }
    $html .= $this->buildFooter();
    echo $html;
  }

  private function buildHeader() {

    $html = '
<!DOCTYPE html>
<html>
    <head>
        <title>App Console</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="' . SITE_URL . 'gestion/web/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href="' . SITE_URL . 'gestion/web/css/style.css" rel="stylesheet">
    </head>
    <body>
        <section id="main" class="container">
            <br/>
            <div class="hero-unit">';
    return $html;
  }

  private function checkLogin() {

    if (isset($_POST['login'])) {
      if ($_POST['login'] === ADMIN_EMAIL && $_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['webmaster'] = true;
        return '';
      } else {
        return '
                    <div class="alert alert-error">Erreur : login et MDP incorrects.</div>';
      }
    }
  }

  private function sessionOpen() {

    $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);

    $entitiesDir = PATH_TO_IMPORTANT_FILES . 'lib/Entities/';
    $dir = opendir($entitiesDir);
    $classes = array();

    while ($file = readdir($dir)) {
      if ($file != '.' && $file != '..' && !is_dir($entitiesDir . $file) && !preg_match('#Repository#', $file)) {
        $file = preg_replace('#\.php#', '', $file);
        $classes[] = $this->em->getClassMetadata('Entities\\' . $file);
      }
    }

    closedir($dir);

    $entitiesDirFwk = PATH_TO_IMPORTANT_FILES . 'lib/Resources/Entities/';
    $dir2 = opendir($entitiesDirFwk);

    while ($file = readdir($dir2)) {
      if ($file != '.' && $file != '..' && !is_dir($entitiesDirFwk . $file) && !preg_match('#Repository#', $file)) {
        $file = preg_replace('#\.php#', '', $file);
        $classes[] = $this->em->getClassMetadata('Resources\Entities\\' . $file);
      }
    }

    closedir($dir2);

    $html = '';

    if (isset($_GET['req'])) {

      switch ($_GET['req']) {

        case 'createschema':
          $tool->createSchema($classes);
          $html .= '
                    <pre><br/><br/><div class="alert alert-info">Schéma créé avec succès.</div><br/><br/></pre>';
          break;
        case 'deleteschema':
          try{
            $tool->dropSchema($classes);
            $html .= '
                    <pre><br/><br/><div class="alert alert-info">Schéma supprimé avec succès.</div><br/></pre>';
          }catch(Exception $e){
            if(preg_match('#Unknown database#', $e->getMessage()))
              $html .= '
                    <pre><br/><div class="alert alert-error">La base de donnée "'.PDO_DATABASE_NAME.'" n\'existe pas.</div></pre>';
            else
              $html .= '
                    <pre><br/><div class="alert alert-error">Une exception est apparue :<br/>'.$e->getMessage().'</div></pre>';
          }
          break;

        case 'updateschema':
          try{
            $tool->updateSchema($classes);
            $html .= '
                    <pre><br/><br/><div class="alert alert-info">Schéma mis à jour avec succès.</div><br/><br/></pre>';
          }catch(Exception $e){
            if(preg_match('#Unknown database#', $e->getMessage()))
              $html .= '
                    <pre><br/><div class="alert alert-error">La base de donnée "'.PDO_DATABASE_NAME.'" n\'existe pas.</div></pre>';
            else
              $html .= '
                    <pre><br/><div class="alert alert-error">Une exception est apparue :<br/>'.$e->getMessage().'</div></pre>';
          }

          break;

        case 'createfirstschema':
          try{
            $tool->createSchema($classes);
            $html .= '
                    <pre><br/><br/><div class="alert alert-info">Schéma créé avec succès.<br/>Ajout du premier utilisateur : <strong>OK</strong>.<br/>Vous pouvez maintenant <a href="'.SITE_URL.'gestion/"><span style="text-decoration: underline;">vous connecter au back office</span></a> avec vos identifiants</div><br/></pre>';

            // Creation d'un privilege
            $privilege = new Resources\Entities\Privilege;
            $privilege->setId(1);
            $privilege->setNom('Administrateur');
            $privilege->setLevel(9);
            $this->em->persist($privilege);

            $privilege2 = new Resources\Entities\Privilege;
            $privilege2->setId(2);
            $privilege2->setNom('WebMaster');
            $privilege2->setLevel(10);
            $this->em->persist($privilege2);

            $privilege3 = new Resources\Entities\Privilege;
            $privilege3->setId(3);
            $privilege3->setNom('Normal');
            $privilege3->setLevel(5);
            $this->em->persist($privilege3);

            // Creation d'utilisateur
            $admin = new Resources\Entities\Admin;
            $admin->setId(1);
            $admin->setEmail(ADMIN_EMAIL);
            $admin->setFonction('Développeur Web');
            $admin->setNom(ADMIN_NOM);
            $admin->setPrenom(ADMIN_PRENOM);
            $admin->setPassword(FwkSecurity::encryptPassword(ADMIN_PASSWORD));
            $admin->setPrivilege($privilege2);

            $this->em->persist($admin);
            $this->em->flush();
          }catch(Exception $e){
            if(preg_match('#Unknown database#', $e->getMessage()))
              $html .= '
                    <pre><br/><div class="alert alert-error">La base de donnée "'.PDO_DATABASE_NAME.'" n\'existe pas.</div></pre>';
            else
              $html .= '
                    <pre><br/><div class="alert alert-error">Le schéma est déjà créé, vous ne pouvez pas répéter cette action.</div></pre>';
          }
          break;
      }
    } else {
      $html .= '
                    <h4>Bienvenue admin.</h4><br/>

                    Vous avez maintenant accès a la génération de la BDD.<br/><br/>--------------------------------<br/>';
    }

    $html .= '
                    <br/>

                    <!--- <a href="'.SITE_URL.'apps/console/createschema" title="Creer le schema">Générer le schéma</a><br/><br/>-->
                    - <a href="'.SITE_URL.'apps/console/createfirstschema" title="Creer le schema">Générer le schéma avec un premier enregistrement</a><br/><br/>
                    - <a href="'.SITE_URL.'apps/console/deleteschema" title="Supprimer le schema" onclick="if(confirm(\'Voulez-vous vraiment supprimer le schema ?\')) return true; else return false;">Supprimer le schéma</a><br/><br/>
                    - <a href="'.SITE_URL.'apps/console/updateschema" title="Mettre a jour le schema">Mettre a jour le schéma</a><br/>';

    return $html;
  }

  private function sessionClosed() {

    return '
                    <form action="" method="POST">
                        <br/><br/>
                        <strong>Merci de vous connecter : </strong><br/>
                        <small><i>(les identifiants sont les constantes définies dans le fichier app/config.php : "ADMIN_EMAIL" et "ADMIN_PASSWORD")</i></small><br/>
                        <br/>
                        <input type="text" name="login" placeholder="Login ..." /><br/><br/>
                        <input type="password" name="password" placeholder="Mot de passe ..." /><br><br/>
                        <input type="submit" class="btn btn-primary" value="Valider" />
                    </form>
            ';
  }

  private function buildFooter() {

    return '
            

            </div>

            <hr>

            <small><a href="' . SITE_URL . '" title="Go back">- Revenir à la page précédente</a></small>

        </section>
    </body>
</html>

            ';
  }

}

?>
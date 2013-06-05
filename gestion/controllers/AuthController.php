<?php

Use Resources\Entities\Admin;

/**
 *
 * Auth Controller
 * Le controller doit absolument heriter de ControllerManager
 */
class AuthController extends ControllerManager {

  /**
   * Affichage de la page login
   *
   * @return view
   */
  protected function loginController() {

    if (isset($_POST['login']) && isset($_POST['password'])) {

      $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(array('email' => $_POST['login']));

      if ($objAdmin instanceof Admin) {
        if (FwkSecurity::comparePassword($_POST['password'], $objAdmin->getPassword())) {
          $_SESSION['admin']['id']    = $objAdmin->getId();
          $_SESSION['admin']['name']  = $objAdmin->getPrenom() . ' ' . $objAdmin->getNom();
          $_SESSION['admin']['email'] = $objAdmin->getEmail();
          if (isset($_SESSION['site_request_uri']))
            header('Location: ' . $_SESSION['site_request_uri']); else
            header('Location: ' . SITE_URL . 'back-office/home');
        }
      }
    }

    $this->renderView('views/login.html.twig');
  }

  /**
   * Controller de déconnexion
   */
  protected function logoutController() {
    session_destroy();
    header('Location: ' . SITE_URL . 'auth/login');
  }

  protected function forgetPasswordController() {

    $val = false;

    if (isset($_POST['login'])) {

      $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(array('email' => $_POST['login']));

      if ($objAdmin instanceof Admin) {
        $result = FwkSecurity::multiLoginProtect(array());
        if ($result === true) {

          $token = FwkSecurity::generateToken();
          $objAdmin->setToken($token);
          $objAdmin->setTsToken(time());
          $this->em->persist($objAdmin);
          $this->em->flush();

          $emailBody = "
                        <h3>Vous souhaitez reinitialiser votre mot de passe</h3>
                        <br />\n
                        Pour ce faire, cliquez simplement sur le lien ci-dessous : 
                        <br />\n<br />\n
                        <a href='" . SITE_URL . "auth/change-password/" . $objAdmin->getEmail() . "/" . $token . "' title='Modifier mon mot de passe'>Modifier votre mot de passe ici.</a>
                        <br/>\n<br/>\n
                        Il vous sera ensuite demandé de choisir votre nouveau mot de passe.
                        <br />\n
                        Vous avez au maximum une heure pour changer votre mot de passe. Une fois ce délai dépassé, il vous faudra réitérer votre demande.
                        <br />\n<br/>\n<br/>\n
                        Dans le cas où vous n'avez pas fait cette demande, cela signifie que quelqu'un tente d'entrer sur votre session.<br />\n
                        Pas de panique, le webmaster est informé en cas de problème.</div>
                        <br />\n<br />\n
                        -----------------------------
                        <br />\n
                        <i style='font-size: 11px;'>Merci de ne pas répondre à ce message.</i>
                        <br />\n<br />\n
                        ";

          $message = Swift_Message::newInstance();
          $mailer  = Swift_MailTransport::newInstance();
          $message->setSubject('Réinitialisation de votre mot de passe');
          $message->setFrom(array(CLIENT_EMAIL => SITE_NOM));
          $message->setTo(array(CLIENT_EMAIL, $objAdmin->getEmail() => $objAdmin->getNom() . ' ' . $objAdmin->getPrenom()));
          $message->setBcc(array(ADMIN_EMAIL => ADMIN_PRENOM . ' ' . ADMIN_NOM));
          $message->setBody($emailBody, 'text/html');

          $result = $mailer->send($message);

          if ($result)
            $val = true;
        }
      }
    }

    $this->renderView('views/forget-password.html.twig', array('displayPopup' => $val));
  }

  protected function changePasswordController() {

    if (isset($_GET['l']) && isset($_GET['t'])) {
      $login = $_GET['l'];
      $token = $_GET['t'];
    } elseif (isset($_POST['l']) && isset($_POST['t'])) {
      $login = $_POST['l'];
      $token = $_POST['t'];
    }

    if (isset($login)) {
      $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(array('email' => $login, 'token' => $token));

      if ($objAdmin instanceof Admin) {

        if ($objAdmin->getTsToken() != null) {

          $tsResult = time() - $objAdmin->getTsToken();
          if ($tsResult > 3600) {
            $objAdmin->setToken(null);
            $objAdmin->setTsToken(null);
            $this->em->persist($objAdmin);
            $this->em->flush();

            $this->error404Controller();
          }
        }

        $formValid = false;

        if (isset($_POST['pwd'])) {

          $objAdmin->setPassword(FwkSecurity::encryptPassword($_POST['pwd']));
          $objAdmin->setToken(null);
          $objAdmin->setTsToken(null);
          $this->em->persist($objAdmin);
          $this->em->flush();

          $formValid = true;
        }


        $this->renderView('views/change-password.html.twig', array('email' => $login, 'token' => $token, 'formValid' => $formValid));
      } else {
        $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(array('email' => $login));

        if ($objAdmin instanceof Admin) {

          $objAdmin->setToken(null);
          $objAdmin->setTsToken(null);
          $this->em->persist($objAdmin);
          $this->em->flush();
          $this->error404Controller();
        } else
          $this->error404Controller();
      }
    } else
      $this->error404Controller();
  }

}

?>

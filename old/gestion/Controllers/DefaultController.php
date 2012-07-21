<?php

if ($content == 'login') {

    if (isset($_POST['login']) && isset($_POST['password'])) {

        $adminManager = new AdminManager();
        $verification = $adminManager->checkAdmin($_POST['login'], $_POST['password']);

        if ($verification) {

            $_SESSION['login'] = $_POST['login'];
            $_SESSION['password'] = $_POST['password'];

            header('Location: index.php');
            exit();
        } else {

            header('Location: index.php?content=login&login=0');
        }
    }
}

if ($content == 'lostpassword') {

    if (isset($_POST['login'])) {

        $adminManager = new AdminManager();
        if ($adminManager->checkEmail($_POST['login'])) {

            $uniqueID = uniqid();
            if ($adminManager->setUniqueID($_POST['login'], $uniqueID)) {

                $mailManager = new MailManager();
                $mailManager->recupMail($_POST['login'], $siteURL."/gestion/index.php?content=changepassword", $uniqueID);
            
                header('Location: index.php?content=lostpassword&wrong=1');
            } else {
                header('Location: index.php?content=lostpassword&wrong=0');
            }
        } else {
            header('Location: index.php?content=lostpassword&wrong=0');
        }
    }
}


if ($content == 'changepassword') {

    if (isset($_GET['ui']) && isset($_GET['email'])) {

        $adminManager = new AdminManager();
        if ($adminManager->checkEmailAndUI($_GET['ui'], $_GET['email'])) {

            $verif = true;
        } else {
            $verif = false;
        }
    }

    if (isset($_POST['ui']) && isset($_POST['email']) && isset($_POST['newpassword'])) {

        $adminManager = new AdminManager();
        $adminManager->updateUiEmailUser($_POST['ui'], $_POST['email'], md5($_POST['newpassword']));

        header('Location: index.php?content=login');
    }
}

if ($content == 'admin-delete') {

    if ($userAdmin->privilege < 9)
        header('Location: index.php?content=home');

    if (isset($_GET['id'])) {

        $id = $_GET['id'];
        $manager = new AdminManager();
        $resultat = $manager->remove($userAdmin, $id);
        if ($resultat)
            header('Location:index.php?content=admin&erreur=0');
        else
            header('Location:index.php?content=admin&erreur=1');
    }else
        header('Location:index.php?content=admin&erreur=1');
}

if ($content == 'admin') {

    if ($userAdmin->privilege < 9)
        header('Location: index.php?content=home');

    $manager = new AdminManager();

    if (isset($_SESSION['tri'])) {
        if ($_SESSION['tri']['content'] != $content) {
            $_SESSION['tri']['content'] = $content;
            $_SESSION['tri']['msgparpage'] = 10;
            $_SESSION['tri']['ordre'] = "DESC";
            $_SESSION['tri']['choix'] = "timestamp";
        }
    } else {
        $_SESSION['tri']['content'] = $content;
        $_SESSION['tri']['msgparpage'] = 10;
        $_SESSION['tri']['ordre'] = "DESC";
        $_SESSION['tri']['choix'] = "timestamp";
    }

    if (isset($_POST['selectmsg'])) {

        $_SESSION['tri']['msgparpage'] = $_POST['selectmsg'];
    }
    if (isset($_POST['select']) && isset($_POST['ordre'])) {

        $_SESSION['tri']['ordre'] = $_POST['ordre'];
        $_SESSION['tri']['choix'] = $_POST['select'];
    }


    // Configuration générale de la page

    $length_max = 100;

    // --------------------------------------------------------


    $nbrLignes = $manager->count();
    $nombreDePages = ceil($nbrLignes / $_SESSION['tri']['msgparpage']);

    if (isset($_GET['page'])) {

        $pageActuelle = intval($_GET['page']);
        $_SESSION['tri']['page'] = $pageActuelle;

        if ($pageActuelle > $nombreDePages) {

            $pageActuelle = $nombreDePages;
        }
    } elseif (isset($_SESSION['tri']['page'])) {
        $pageActuelle = intval($_SESSION['tri']['page']);
        if ($pageActuelle > $nombreDePages) {

            $pageActuelle = $nombreDePages;
        }
    } else {
        $pageActuelle = 1;
    }

    $premiereEntree = ($pageActuelle - 1) * $_SESSION['tri']['msgparpage'];

    $liste = $manager->getTableWithLimitSortedOrdered($_SESSION['tri']['choix'], $_SESSION['tri']['ordre'], $premiereEntree, $_SESSION['tri']['msgparpage']);

    if (isset($_POST['selection'])) {

        if ($_POST['selection'] != null) {

            $selection = $_POST['selection'];
            $nombre = count($selection);

            for ($i = 0; $i < $nombre; $i++) {

                $manager->remove($userAdmin, $selection[$i]);
            }

            header('Location:index.php?content=admin&erreur=0');
        } else {
            echo "<span color:white>Vous n'avez rien s&eacute;lectionn&eacute;</span>";
        }
    }
}


if ($content == 'admin-add') {

    // Code de v?rification des champs
    if ($userAdmin->privilege < 9)
        header('Location: index.php?content=home');

    if (isset($_POST['fonction'])) {


        // Gestion d'une image

        if ($_FILES['image'] != null) {

            $imageManager = new ImageManager();
            $destination = "Web/img/users-avatars/";
            $name = "image";
            $resultSave = $imageManager->saveAndRenameImage($destination, $name, null, null, true, false, false);
        } else {
            $destination = "Web/img/bibliotheque/";
            $resultSave = "image-base.png";
        }

        $imageSauvegarde_champ9 = $destination . $resultSave;

        // Ajout ? la BDD
        $manager = new AdminManager();

        $result = $manager->add($userAdmin, $_POST['fonction'], $_POST['privilege'], $_POST['nom'], $_POST['prenom'], $_POST['email'], md5($_POST['password']), $_POST['tel'], $imageSauvegarde_champ9);

        if ($result)
            header('Location:index.php?content=admin&erreur=0');
        else
            header('Location:index.php?content=admin&erreur=1');
    }
}

if ($content == 'admin-edit') {

    if ($userAdmin->privilege < 9)
        header('Location: index.php?content=home');
    if (!isset($_GET['id']))
        header('Location: index.php?content=admin');
    $manager = new AdminManager();
    $objet = $manager->getTableWithIdPublic($_GET['id']);
    // Code de v?rification des champs

    if (isset($_POST['fonction'])) {


        // Gestion d'une image
        if (isset($_FILES['image']['name'])) {
            if ($_FILES['image']['name'] != null) {

                $imageManager = new ImageManager();
                $destination = "Web/img/users-avatars/";
                $name = "image";
                $resultSave = $imageManager->saveAndRenameImage($destination, $name, null, null, true, false, false);
            } else {
                $destination = $objet->image;
                $resultSave = "";
            }
        } else {
            $destination = $objet->image;
            $resultSave = "";
        }

        $imageSauvegarde_champ9 = $destination . $resultSave;

        // Ajout � la BDD
        $manager = new AdminManager();
        if ($_POST['password'] == "password")
            $pass = $objet->password;
        else
            $pass = md5($_POST['password']);

        $result = $manager->update($userAdmin, $_GET['id'], $_POST['fonction'], $_POST['privilege'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $pass, $_POST['tel'], $imageSauvegarde_champ9);

        if ($result)
            header('Location:index.php?content=admin&erreur=0');
        else
            header('Location:index.php?content=admin&erreur=1');
    }
}

if ($content == 'logs') {

    $manager = new LogManager();
    if ($userAdmin->privilege < 9)
        header('Location: index.php?content=home');

    if (isset($_SESSION['tri'])) {
        if ($_SESSION['tri']['content'] != $content) {
            $_SESSION['tri']['content'] = $content;
            $_SESSION['tri']['msgparpage'] = 10;
            $_SESSION['tri']['ordre'] = "DESC";
            $_SESSION['tri']['choix'] = "timestamp";
        }
    } else {
        $_SESSION['tri']['content'] = $content;
        $_SESSION['tri']['msgparpage'] = 10;
        $_SESSION['tri']['ordre'] = "DESC";
        $_SESSION['tri']['choix'] = "timestamp";
    }

    if (isset($_POST['selectmsg'])) {

        $_SESSION['tri']['msgparpage'] = $_POST['selectmsg'];
    }
    if (isset($_POST['select']) && isset($_POST['ordre'])) {

        $_SESSION['tri']['ordre'] = $_POST['ordre'];
        $_SESSION['tri']['choix'] = $_POST['select'];
    }


    // Configuration générale de la page

    $length_max = 100;

    // --------------------------------------------------------


    $nbrLignes = $manager->count();
    $nombreDePages = ceil($nbrLignes / $_SESSION['tri']['msgparpage']);

    if (isset($_GET['page'])) {

        $pageActuelle = intval($_GET['page']);
        $_SESSION['tri']['page'] = $pageActuelle;

        if ($pageActuelle > $nombreDePages) {

            $pageActuelle = $nombreDePages;
        }
    } elseif (isset($_SESSION['tri']['page'])) {
        $pageActuelle = intval($_SESSION['tri']['page']);
        if ($pageActuelle > $nombreDePages) {

            $pageActuelle = $nombreDePages;
        }
    } else {
        $pageActuelle = 1;
    }

    $premiereEntree = ($pageActuelle - 1) * $_SESSION['tri']['msgparpage'];

    $liste = $manager->getTableWithLimitSortedOrdered($_SESSION['tri']['choix'], $_SESSION['tri']['ordre'], $premiereEntree, $_SESSION['tri']['msgparpage']);

    if (isset($_POST['selection'])) {

        if ($_POST['selection'] != null) {

            $selection = $_POST['selection'];
            $nombre = count($selection);

            for ($i = 0; $i < $nombre; $i++) {

                $manager->remove($selection[$i]);
            }

            header('Location:index.php?content=logs&erreur=0');
        } else {
            echo "<span color:white>Vous n'avez rien s&eacute;lectionn&eacute;</span>";
        }
    }
}
?>
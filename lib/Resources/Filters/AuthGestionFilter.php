<?php

/**
 * Filtre global
 */
class AuthGestionFilter extends FilterManager {

    /**
     * Contenu permi sans être loggé
     */
    private $acceptedContent = array('login', 'forget-password', 'change-password');

    /**
     * Execution du filtre et application des paramètres
     */
    public function execute() {

        if (!isset($_SESSION['admin']) && !in_array(GET_CONTENT, $this->acceptedContent)) {
            if (!preg_match('#auth/login#', SITE_CURRENT_URI))
                $_SESSION['site_request_uri'] = SITE_CURRENT_URI;
            header('Location: ' . SITE_URL . 'auth/login');
        }elseif (isset($_SESSION['admin']['email']) && !in_array(GET_CONTENT, $this->acceptedContent)) {
            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(array('id' => $_SESSION['admin']['id'], 'email' => $_SESSION['admin']['email']));
            if (is_object($objAdmin))
                $this->checkPrivilegeFilter($objAdmin);
            else {
                session_destroy();
                header('Location: ' . SITE_URL . 'auth/login');
            }
        }
    }

    /**
     * Fonction vérifiant si les privilèges utilisateurs sont suffisants pour poursuivre
     * 
     * @param $objAdmin Resources\Entities\Admin
     */
    private function checkPrivilegeFilter(Resources\Entities\Admin $objAdmin) {

        /*
         * Gestion des différents cas
         */
        switch (GET_PATTERN) {

            case 'private':
                if ($objAdmin->getPrivilege()->getLevel() < 9) {
                    header('Location: ' . SITE_URL . 'url-error/error404');
                }
                break;
        }
    }

}

?>

<?php

/**
 * Classe du FrameWork essentielle pour les traitements
 *
 * @author f.mithieux
 */
class FwkManager {

  /**
   * Administrateur connecté actuellement
   *
   * @var Resources\Entities\Admin
   */
  protected $objAdmin;

  protected function setObjAdmin() {
    $this->objAdmin = $this->em->getRepository('Resources\Entities\Admin')->findOneBy(array('id' => $_SESSION['admin']['id'], 'email' => $_SESSION['admin']['email']));;
  }

}


?>
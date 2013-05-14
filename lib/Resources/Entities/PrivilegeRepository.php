<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

class PrivilegeRepository extends EntityRepository {

  /**
   * Génération de td pour l'affichage d'univers dans d'autres classes
   *
   * @param \Resources\Entities\Privilege $objPrivilege
   * @return string
   */
  public function getTableName(\Resources\Entities\Privilege $objPrivilege) {

    $html = '
                    <td data-id="' . $objPrivilege->getId() . '">
                        ' . $objPrivilege->getNom() . '
                    </td>';

    return $html;
  }

}

?>

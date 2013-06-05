<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

class AdminRepository extends EntityRepository {

  /**
   * Génération de td pour l'affichage d'univers dans d'autres classes
   *
   * @param \Resources\Entities\Admin $objAdmin
   * @return string
   */
  public function getAdminName(\Resources\Entities\Admin $objAdmin) {

    $html = '
                    <td data-id="' . $objAdmin->getId() . '">
                        ' . $objAdmin->getPrenom() . ' ' . $objAdmin->getNom() . '
                    </td>';

    return $html;
  }

}


?>

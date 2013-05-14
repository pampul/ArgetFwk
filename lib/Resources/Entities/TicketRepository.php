<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

class TicketRepository extends EntityRepository {

  /**
   * Génération de td pour l'affichage d'univers dans d'autres classes
   *
   * @param \Resources\Entities\Ticket $objTicket
   * @return string
   */
  public function getTypeTicket(\Resources\Entities\Ticket $objTicket) {

    $html = '
                    <td data-id="' . $objTicket->getId() . '">
                        ' . $objTicket->getTypeTicketPerso() . '
                    </td>';

    return $html;
  }

  /**
   * Génération de td pour l'affichage d'univers dans d'autres classes
   *
   * @param \Resources\Entities\Ticket $objTicket
   * @return string
   */
  public function getStatut(\Resources\Entities\Ticket $objTicket) {

    switch($objTicket->getStatut()){

      case 'ferme':
        $statut = '<div class="alert alert-error alert-nopadding">fermé</div>';
        break;

      case 'ouvert':
      default:
        $statut = '<div class="alert alert-success alert-nopadding">ouvert</div>';
        break;

    }

    $html = '
                    <td data-id="' . $objTicket->getId() . '">
                        ' . $statut . '
                    </td>';

    return $html;
  }

}

?>

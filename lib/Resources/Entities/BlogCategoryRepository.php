<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

class BlogCategoryRepository extends EntityRepository {

    /**
     * Génération de td pour l'affichage d'univers dans d'autres classes
     * 
     * @param \Resources\Entities\BlogCategory $objCategory
     * @return string
     */
    public function getTableName(\Resources\Entities\BlogCategory $objCategory) {

        $html = '
                    <td data-id="' . $objCategory->getId() . '">
                        ' . $objCategory->getNom() . '
                    </td>';
        
        return $html;
    }
    
}

?>

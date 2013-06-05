<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

class BlogPostRepository extends EntityRepository {

  public function getSelectStatut() {

    $html = '
            <option value="publish">Publié</option>
            <option value="draft">Brouillon</option>
            <option value="trash">Corbeille</option>';

    return $html;
  }

  /**
   * Retourne l'élément du tableau nécessaire à l'affichage du statut
   *
   * @param \Resources\Entities\BlogPost $objBlogPost
   * @return string
   */
  public function getStatut(\Resources\Entities\BlogPost $objBlogPost) {
    switch ($objBlogPost->getStatut()) {

      case 'draft':
        return '
                    <td data-id="' . $objBlogPost->getId() . '">
                        <i class="icon-color icon-question"></i> Brouillon
                    </td>';
        break;
      case 'trash':
        return '
                    <td data-id="' . $objBlogPost->getId() . '">
                        <i class="icon-color icon-critical-error"></i> Corbeille
                    </td>';
        break;
      case 'publish':
        return '
                    <td data-id="' . $objBlogPost->getId() . '">
                        <i class="icon-color icon-amelioration"></i> Publié
                    </td>';
        break;

    }

    return;

  }

  /**
   * Retourne l'URL
   *
   * @param \Resources\Entities\BlogPost $objBlogPost
   * @return string
   */
  public function getUrl(\Resources\Entities\BlogPost $objBlogPost) {
    return '
                    <td data-id="' . $objBlogPost->getId() . '">
                        <span style="color:#9b060c;">' . $objBlogPost->getSeoUrl() . '</span>
                    </td>';

  }

  /**
   * Retourne l'URL
   *
   * @param \Resources\Entities\BlogPost $objBlogPost
   * @return string
   */
  public function getDate(\Resources\Entities\BlogPost $objBlogPost) {
    return '
                    <td data-id="' . $objBlogPost->getId() . '">
                        ' . $objBlogPost->getDateAdd() . '
                    </td>';

  }

}

?>

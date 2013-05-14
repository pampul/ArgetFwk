<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\BlogCategoryRepository") @Table(name="blog_category")
 */
class BlogCategory {

  /**
   * @Id @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @Column(type="string", length=100)
   */
  private $nom;



  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getNom() {
    return $this->nom;
  }

  public function setNom($nom) {
    $this->nom = $nom;
  }

  public function __toString() {
    return $this->nom;
  }

}
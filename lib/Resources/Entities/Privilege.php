<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\PrivilegeRepository") @Table(name="privilege")
 */
class Privilege {

  /**
   * @Id @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @Column(type="string", length=100)
   */
  private $nom;

  /**
   * @Column(type="integer", unique=false)
   */
  private $level;

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

  public function getLevel() {
    return $this->level;
  }

  public function setLevel($level) {
    $this->level = $level;
  }


  public function __toString() {
    return $this->nom;
  }

}
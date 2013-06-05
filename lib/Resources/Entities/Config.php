<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\ConfigRepository") @Table(name="config")
 */
class Config {

  /**
   * @Id @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @Column(type="string", length=100)
   */
  private $name;

  /**
   * @Column(type="string", length=250)
   */
  private $value;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }


  public function __toString() {
    return $this->name;
  }

}
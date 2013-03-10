<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Entities\TestRepository") @Table(name="test")
 */
class Test
{

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
   * @Column(type="string", length=100, name="code_cegid")
   */
  private $codeCegid;

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getNom()
  {
    return $this->nom;
  }

  public function setNom($nom)
  {
    $this->nom = $nom;
  }

  public function __toString()
  {
    return $this->nom;
  }

}
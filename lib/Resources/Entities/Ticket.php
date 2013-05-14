<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\TicketRepository") @Table(name="ticket")
 */
class Ticket {

  /**
   * @Id @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ManyToOne(targetEntity="Admin")
   * @JoinColumn(name="admin")
   */
  private $admin;

  /**
   * @Column(type="string", length=50)
   */
  private $typeTicket;

  /**
   * @Column(type="string", length=20)
   */
  private $statut;

  /**
   * @Column(type="datetime", name="date")
   */
  private $date;

  /**
   * @Column(type="string", length=100)
   */
  private $titre;

  /**
   *
   * @Column(type="text", name="texte")
   */
  private $texte;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getAdmin() {
    return $this->admin;
  }

  public function setAdmin($admin) {
    $this->admin = $admin;
  }

  public function getTypeTicket() {
    return $this->typeTicket;
  }

  public function getTypeTicketPerso(){
    switch(strtolower($this->typeTicket)){

      case 'erreur critique':
        $typeTicket = '<i class="icon-color icon-critical-error"></i> Erreur Critique';
        break;

      case 'question':
        $typeTicket = '<i class="icon-color icon-question"></i> Question';
        break;

      case 'amelioration':
        $typeTicket = '<i class="icon-color icon-amelioration"></i> Amélioration';
        break;

      case 'bug':
      default:
        $typeTicket = '<i class="icon-color icon-bug"></i> Bug';
        break;

    }

    return $typeTicket;
  }

  public function setTypeTicket($typeTicket) {
    $this->typeTicket = $typeTicket;
  }

  public function getStatut() {
    return $this->statut;
  }

  public function setStatut($statut) {
    $this->statut = $statut;
  }

  public function getDate() {
    return \DateUtils::getDateText($this->date->format('Y-m-d H:i'));
  }

  public function setDate($date) {
    $this->date = $date;
  }

  public function getTitre() {
    return $this->titre;
  }

  public function setTitre($titre) {
    $this->titre = $titre;
  }

  public function getTexte() {
    return $this->texte;
  }

  public function getTexteHtml(){
    return nl2br($this->texte);
  }

  public function setTexte($texte) {
    $this->texte = $texte;
  }

}
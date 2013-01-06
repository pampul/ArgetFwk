<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\ReponseRepository") @Table(name="reponse") 
 */
class Reponse {

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
     * @ManyToOne(targetEntity="Ticket")
     * @JoinColumn(name="ticket")
     */
    private $ticket;

    /**
     * @Column(type="datetime", name="date")
     */
    private $date;

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

    public function getTicket() {
        return $this->ticket;
    }

    public function setTicket($ticket) {
        $this->ticket = $ticket;
    }

    public function getDate() {
        return \DateUtils::getDateText($this->date->format('Y-m-d H:i'));
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getTexte() {
        return $this->texte;
    }
    
    public function getTexteHtml() {
        return nl2br($this->texte);
    }

    public function setTexte($texte) {
        $this->texte = $texte;
    }

}
<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\SeoRepository") @Table(name="seo") 
 */
class Seo {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=100) 
     */
    private $url;

    /**
     * @Column(type="string", length=100)
     */
    private $titre;

    /**
     * @Column(type="string", length=100)
     */
    private $h1;

    /**
     * @Column(type="string", length=200)
     */
    private $description;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function getH1() {
        return $this->h1;
    }

    public function setH1($h1) {
        $this->h1 = $h1;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function __toString() {
        return $this->titre;
    }

}
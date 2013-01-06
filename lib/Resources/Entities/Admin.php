<?php

namespace Resources\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Resources\Entities\AdminRepository") @Table(name="admin") 
 */
class Admin {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=100, unique=false, nullable=true)
     */
    private $token;

    /**
     * @Column(type="string", length=15, unique=false, name="ts_token", nullable=true)
     */
    private $tsToken;

    /**
     * @Column(type="string", length=100) 
     */
    private $nom;

    /**
     * @Column(type="string", length=100) 
     */
    private $prenom;

    /**
     * @Column(type="string", length=100) 
     */
    private $fonction;

    /**
     * @ManyToOne(targetEntity="Privilege")
     * @JoinColumn(name="privilege", nullable=true)
     */
    private $privilege;

    /**
     * @Column(type="string", length=150, unique=true) 
     */
    private $email;

    /**
     * @Column(type="string", length=150) 
     */
    private $password;

    /**
     * @Column(type="string", length=100, unique=false, nullable=true)
     */
    private $avatar;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function getTsToken() {
        return $this->tsToken;
    }

    public function setTsToken($tsToken) {
        $this->tsToken = $tsToken;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function getFonction() {
        return $this->fonction;
    }

    public function setFonction($fonction) {
        $this->fonction = $fonction;
    }

    public function getPrivilege() {
        return $this->privilege;
    }

    public function setPrivilege($privilege) {
        $this->privilege = $privilege;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

}
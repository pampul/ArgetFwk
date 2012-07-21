<?php

    class Admin{
    
	    public $id;
	    public $timestamp;
	    public $adminlvl;
	    public $fonction;
	    public $privilege;
	    public $nom;
	    public $prenom;
	    public $email;
	    public $password;
	    public $tel;
	    public $date_inscription;
	    public $image;
	
	    function __construct($id, $timestamp, $adminlvl, $fonction, $privilege, $nom, $prenom, $email, $password, $tel, $date_inscription, $image){
	
		    $this->id = $id;
		    $this->timestamp = $timestamp;
		    $this->adminlvl = $adminlvl;
		    $this->fonction = $fonction;
		    $this->privilege = $privilege;
		    $this->nom = $nom;
		    $this->prenom = $prenom;
		    $this->email = $email;
		    $this->password = $password;
		    $this->tel = $tel;
		    $this->date = $date_inscription;
		    $this->image = $image;
	
	    }
	
	    public function getId() { return $this->id; }
	    public function getTimestamp() { return $this->timestamp; }
	    public function getAdminLvl() { return $this->adminlvl; }
	    public function getfonction() {  return $this->fonction; }
	    public function getprivilege() {  return $this->privilege; }
	    public function getnom() {  return $this->nom; }
	    public function getprenom() {  return $this->prenom; }
	    public function getemail() {  return $this->email; }
	    public function getpassword() {  return $this->password; }
	    public function gettel() {  return $this->tel; }
	    public function getdate_inscription() {  return $this->date; }
	    public function getimage() {  return $this->image; }
	
    }
    
?>
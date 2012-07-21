<?php

    class Log{
    
	    public $id;
	    public $timestamp;
	    public $adminlvl;
	    public $date;
	    public $administrateur;
	    public $type;
	    public $categorie;
	    public $nom;
	
	    function __construct($id, $timestamp, $adminlvl, $date, $administrateur, $type, $categorie, $nom){
	
		    $this->id = $id;
		    $this->timestamp = $timestamp;
		    $this->adminlvl = $adminlvl;
		    $this->date = $date;
		    $this->administrateur = $administrateur;
		    $this->type = $type;
		    $this->categorie = $categorie;
		    $this->nom = $nom;
	
	    }
	
	    public function getId() { return $this->id; }
	    public function getTimestamp() { return $this->timestamp; }
	    public function getAdminLvl() { return $this->adminlvl; }
	    public function getdate() {  return $this->date; }
	    public function getadministrateur() {  return $this->administrateur; }
	    public function gettype() {  return $this->type; }
	    public function getcategorie() {  return $this->categorie; }
	    public function getnom() {  return $this->nom; }
	
    }
    
?>
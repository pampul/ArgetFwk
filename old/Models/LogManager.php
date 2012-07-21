<?php

    require_once 'PdoConnect.php';
    
    class LogManager extends PdoConnect{
    
    
	    public function getTable(){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
                                         ORDER BY timestamp DESC");
	    
            $query->execute();
		
            $result = array();
		
		    while($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
		
		    return $result;
            
        }
	
	
	    public function getTableWithIdPublic($id){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
										WHERE id=:id
                                         ORDER BY timestamp DESC");
		    $query->bindValue(':id', $id);
            $query->execute();
		
            $result = array();
		
		    if($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    return $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
            
        }
	
	    public function getTableSorted($selection){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
                                         ORDER BY ".$selection." DESC");
	    
            $query->execute();
		
            $result = array();
		
		    while($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
		
		    return $result;
            
        }
	
	    public function getTableSortedOrdered($selection, $ordre){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
                                         ORDER BY ".$selection." ".$ordre."");
	    
            $query->execute();
		
            $result = array();
		
		    while($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
		
		    return $result;
            
        }
	
	    public function getTableWithLimit($premiereEntree, $messagesParPage){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
                                         ORDER BY timestamp DESC
									    LIMIT ".$premiereEntree.", ".$messagesParPage."");
	    
            $query->execute();
		
            $result = array();
		
		    while($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
		
		    return $result;
            
        }
	
	    public function getTableWithLimitSorted($selection, $premiereEntree, $messagesParPage){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
                                         ORDER BY ".$selection." DESC
									    LIMIT ".$premiereEntree.", ".$messagesParPage."");
	    
            $query->execute();
		
            $result = array();
		
		    while($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
		
		    return $result;
            
        }
	
	    public function getTableWithLimitSortedOrdered($selection, $ordre, $premiereEntree, $messagesParPage){
            
            $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_log
                                         ORDER BY ".$selection." ".$ordre."
									    LIMIT ".$premiereEntree.", ".$messagesParPage."");
	    
            $query->execute();
		
            $result = array();
		
		    while($row = $query->fetch(PDO::FETCH_OBJ)) {
		
			    $result[] = new Log($row->id, $row->timestamp, $row->adminlvl, $row->date, $row->administrateur, $row->type, $row->categorie, $row->nom);
		
		    }
		
		    return $result;
            
        }
	
	    public function add($administrateur, $type, $categorie, $nom){
	
		    $time = time();
	
		    $query = $this->pdo->prepare('INSERT INTO base_log(timestamp, date, administrateur, type, categorie, nom)
									VALUES(:time, NOW(), :administrateur, :type, :categorie, :nom)');
	
	
		    $query->bindValue(':time', $time);
		    $query->bindValue(':administrateur', $administrateur);
		    $query->bindValue(':type', $type);
		    $query->bindValue(':categorie', $categorie);
		    $query->bindValue(':nom', $nom);
	
	
		    return $query->execute();
	
	    }
	
	    public function update($id, $administrateur, $type, $categorie, $nom){
	
	
		    $query = $this->pdo->prepare('UPDATE base_log
								    SET administrateur=:administrateur, type=:type, categorie=:categorie, nom=:nom
									WHERE id=:id');
	
		    $query->bindValue(':id', $id);
		    $query->bindValue(':administrateur', $administrateur);
		    $query->bindValue(':type', $type);
		    $query->bindValue(':categorie', $categorie);
		    $query->bindValue(':nom', $nom);
	
	
		    return $query->execute();
	
	    }
	
	
	public function remove($id){
            
	    
            $query = $this->pdo->prepare("DELETE FROM base_log WHERE id=:id");
	    $query->bindValue(':id', $id);
		    
		    
            return $query->execute();

            
        }
	
	
	public function count(){
            
	    
	    $row = 0;
	    
	    $query = $this->pdo->prepare("SELECT COUNT(*) as cpt FROM base_log");
	    
	    $query->execute();
	    
	    $row = $query->fetch(PDO::FETCH_OBJ);
	    
	    if($row) return $row->cpt;
		else return 0;

            
        }
	
    }
    
?>
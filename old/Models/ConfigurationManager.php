<?php

    require_once 'PdoConnect.php';
    
    class ConfigurationManager extends PdoConnect {
        
        
        public function getTable() {
            
            $query = $this->pdo->prepare("SELECT *
                                         FROM base_configuration
                                         ORDER BY id DESC");
	    
            $query->execute();
		
            $result = array();
		
	    if($row = $query->fetch(PDO::FETCH_OBJ)) {
		
		return $result[] = new Configuration($row->id, $row->config, $row->value);
		
	    }
            
        }
	
	public function getTableWithConfig($config) {
            
            $query = $this->pdo->prepare("SELECT *
                                         FROM base_configuration
					 WHERE config=:config 
                                         ORDER BY id DESC");
	    
	    $query->bindValue(':config', $config);
	    
            $query->execute();
		
            $result = array();
		
	    if($row = $query->fetch(PDO::FETCH_OBJ)) {
		
		return $result[] = new Configuration($row->id, $row->config, $row->value);
		
	    }
            
        }

        public function update($config, $value){
            
            $query = $this->pdo->prepare('UPDATE base_configuration
                                         SET value=:value
					 WHERE config = :config
                                         ');
            
	    $query->bindValue(':value', $value);
	    $query->bindValue(':config', $config);
            
            return $query->execute();
            
        }
        
        
        
    }
    
?>
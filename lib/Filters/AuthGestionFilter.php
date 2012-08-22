<?php

/**
 * Filtre par défaut
 */
class AuthGestionFilter extends FilterManager{
    
    /**
     * Execution du filtre et application des paramètres
     */
    public function execute(){
        
        if(!isset($_SESSION['admin']) && GET_CONTENT != 'login'){
            header('Location: fr-auth/login');
        }
        
    }
    
}
?>

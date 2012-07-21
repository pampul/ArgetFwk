<?php

    if(isset($_POST['pseudo'])){
        
        $chaine = addslashes($_POST['pseudo']);
        
        if (!empty($chaine) && strlen($chaine) > 3 && preg_match("#^[a-zA-Z0-9-_]+$#", $chaine)){
            
            // Inclure ici une fonction pour checker si le pseudo existe ou pas
            echo "ok";
            
        }
        
    }elseif(isset($_POST['text'])){
        
        $chaine = addslashes($_POST['text']);
        
        if (!empty($chaine) && strlen($chaine) > 3 && preg_match("/^([\p{L}a-zA-Z- ]+)$/ui", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['repassword']) && isset($_POST['password'])){
        
        $chaine = addslashes($_POST['repassword']);
        $password = addslashes($_POST['password']);
        
        if (!empty($chaine) && strlen($chaine) > 5 && preg_match("#^[a-zA-Z0-9-_]+$#", $chaine)){
            
            if($chaine == $password){
                
                echo "ok";
                
            }
            
        }
        
    }elseif(isset($_POST['password'])){
        
        $chaine = addslashes($_POST['password']);
        
        if (!empty($chaine) && strlen($chaine) > 5 && preg_match("#^[a-zA-Z0-9-_]+$#", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['reemail']) && isset($_POST['email'])){
        
        $chaine = addslashes($_POST['reemail']);
        $email = addslashes($_POST['email']);
        
        if (!empty($chaine) && strlen($chaine) > 5 && preg_match("#^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$#", $chaine) && preg_match("#\.([a-z]{2,4})$#", $chaine)){
            
            if($chaine == $email){
                
                echo "ok";
                
            }
            
        }
        
    }elseif(isset($_POST['email'])){
        
        $chaine = addslashes($_POST['email']);
        
        if (!empty($chaine) && strlen($chaine) > 5 && preg_match("#^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$#", $chaine) && preg_match("#\.([a-z]{2,4})$#", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['url'])){
        
        $chaine = addslashes($_POST['url']);
        
        if (!empty($chaine) && strlen($chaine) > 5 && preg_match("#^(http:\/\/)?(www\.)?(.*?)$#", $chaine) && preg_match("#\.([a-z]{2,4})$#", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['telephone'])){
        
        $chaine = addslashes($_POST['telephone']);
        
        if (!empty($chaine) && strlen($chaine) == 10 && preg_match("#^[0-9-]+$#", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['nombre'])){
        
        $chaine = addslashes($_POST['nombre']);
        
        if (!empty($chaine) && strlen($chaine) > 3 && preg_match("#^[0-9]+$#", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['adresse'])){
        
        $chaine = stripslashes($_POST['adresse']);
        
        if (!empty($chaine) && strlen($chaine) > 5 && preg_match("/^([\p{L}a-zA-Z0-9-, ]+)$/ui", $chaine)){
            
            echo "ok";
            
        }
        
    }elseif(isset($_POST['ville'])){
        
        $chaine = addslashes($_POST['ville']);
        
        if (!empty($chaine) && strlen($chaine) > 3 && preg_match("/^([\p{L}a-zA-Z0-9- ]+)$/ui", $chaine)){
            
            echo "ok";
            
        }
        
    }

?>

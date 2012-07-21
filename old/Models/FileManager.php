<?php

    require_once 'PdoConnect.php';
    
    class FileManager extends PdoConnect {
        
	
	// $destination sans le nom du fichier et son extension
	// $fichier correspond au $_FILES[]
	// $boolRename correspond au choix dans le cas ou vous souhaitez renommer le fichier
        public function saveFile($destination, $fichier, $boolRename, $extensions){
            
	    $arrayExtensions = array();
                    
            foreach(explode(';', $extensions) AS $ext){
            
                if($ext != ""){
		    $arrayExtensions[] = ".".$ext;
		}
        
            }

            // r�cup�re la partie de la chaine � partir du dernier . pour conna�tre l'extension.
	    $extension_upload = strrchr($_FILES[$fichier]['name'], '.');
	    
	    if(!is_dir($destination)){
		FileManager::createDir($destination);
	    }
            
            if (in_array($extension_upload, $arrayExtensions) ) {
                
		$nameFile = str_replace($extension_upload, "", $_FILES[$fichier]['name']);
		
                if($boolRename == true){
		    
		    $nameFile = utf8_decode($nameFile);
		    $nameFile = strtr($nameFile, '��������������������������', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
		    $nameFile = strtr($nameFile, '���������������������������', 'aaaaaaceeeeiiiinooooouuuuyy');
		    $nameFile = strtr($nameFile, ' ', '-');
		    $nameFile = utf8_encode($nameFile);
		    $nomtemporaire = $nameFile;
                    $file = $destination.$nameFile.$extension_upload;
		    $i = 0;
                    while(file_exists($destination.$nameFile.$extension_upload)){
                        
			$nameFile = $nomtemporaire;
			$nameFile = $nameFile."__".$i;
			
			$i++;
                        
                    }
                    
                }
		
                if($boolRename == true){
		    
		    $finalPath = $destination.$nameFile.$extension_upload;
		    $resultat = move_uploaded_file($_FILES[$fichier]['tmp_name'], $finalPath);
		
		}else {
		    
		    $resultat = move_uploaded_file($_FILES[$fichier]['tmp_name'], $destination.$nameFile.$extension_upload);
		    
		}
                
                if($resultat) {
		    
		    return $nameFile.$extension_upload;
		
		}else return "erreur";
                
            }else return "erreur";
            
            
        }
	

	public function createDir($dir){
        
	    $tmp = '';
                    
            foreach(explode('/',$dir) AS $k){
            
                $tmp .= $k.'/';
        
                if(!file_exists($tmp)){
                
		    mkdir($tmp, 0755);
                
		}
        
            }
	
	}
	
        
        
    }

?>
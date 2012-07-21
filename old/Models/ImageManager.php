<?php

    require_once 'PdoConnect.php';
    //require_once 'TraitementImages.php';
    
    class ImageManager extends PdoConnect {
        
	
	// $destination sans le nom du fichier et son extension
	// $image correspond au $_FILES[]
	// $boolRename correspond au choix dans le cas ou vous souhaitez renommer le fichier
	// $boolChangeExt Dans le cas ou vous souhaitez modifier l'extension du fichier
	// $boolResize dans le cas ou vous souhaitez reduire la taille d'une image
        public function saveAndRenameImage($destination, $image, $width, $height, $boolRename, $boolChangeExt, $boolResize){
            
            $newExtension = '.jpg';
            $extensions = array('.png', '.gif', '.jpg', '.jpeg');
            // r�cup�re la partie de la chaine � partir du dernier . pour conna�tre l'extension.
	    $extension_upload = strtolower(strrchr($_FILES[$image]['name'], '.'));
	    
	    if(!is_dir($destination)){
		ImageManager::createDir($destination);
	    }
            
            if (in_array($extension_upload, $extensions)) {
                
		$nameFile = str_replace($extension_upload, "", $_FILES[$image]['name']);
		$type = $_FILES[$image]['type'];
		
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
                
                if($boolChangeExt == true){
                        
                    $extension_upload = $newExtension;
                        
                }
		
                if($boolRename == true){
		    
		    $finalPath = $destination.$nameFile.$extension_upload;
		    $resultat = move_uploaded_file($_FILES[$image]['tmp_name'], $finalPath);
		
		}else {
		    
		    $resultat = move_uploaded_file($_FILES[$image]['tmp_name'], $destination.$nameFile.$extension_upload);
		    
		}
                
                if($resultat) {
		    
		    if($boolResize == true){
			
			$image = new TraitementImages($destination.$nameFile.$extension_upload);
			
			$image->resize($width, $height);
			$image->save($destination.$nameFile.$extension_upload);
			
			return $nameFile.$extension_upload;
			
		    }else {
		    
			return $nameFile.$extension_upload;
		    
		    }
		
		}else return "erreur";
                
            }else return "erreur";
            
            
        }
	
	public function resizeImage($filepath, $x, $y, $type, $istmp = false, $forceWidth = false, $forceHeight = false){
	    
    
	    $homeImageWidth = $x;
	    $homeImageHeight = $y;
    
	    ///////// Start the thumbnail generation//////////////
	    $n_width=$homeImageWidth;          // Fix the width of the thumb nail images
	    $n_height=$homeImageHeight;         // Fix the height of the thumb nail imaage
    
	    //$tsrc="../upload/$userfile_name";   // Path where thumb nail image will be stored
	    $tsrc = $filepath;
    
	    if( $istmp )
	    {
		    $filepath .= ".tmp";
	    }
    
	    if (!($type =="image/pjpeg" || $type =="image/jpeg" || $type=="image/gif"))
	    {
		    $report["error"] = "format";
		    return $report;
	    }
    
	    if( $istmp && file_exists($tsrc) )
	    {
		    unlink($tsrc);
	    }
    
	    /////////////////////////////////////////////// Starting of GIF thumb nail creation///////////
	    if (@$type=="image/gif")
	    {
		    $im=ImageCreateFromGIF($filepath);
		    $width=ImageSx($im);              // Original picture width is stored
		    $height=ImageSy($im);                  // Original picture height is stored
    
		    $ratio = $width / $height;
    
		    if( false && $height > $width )
		    {
			    $ratio = $height / $width;
			    $n_height= ( $height > $homeImageWidth ) ? $homeImageWidth : $height;
			    $n_width = round($n_height / $ratio);
    
		    }
		    else
		    {
			    $ratio = $width / $height;
			    /*
			    $n_width= ( $width > $homeImageWidth ) ? $homeImageWidth : $width;
			    $n_height= round($n_width / $ratio);
			    */
			    $n_height = ( $height > $homeImageHeight ) ? $homeImageHeight : $height;
			    $n_width  = round($n_height*$ratio);
			    
		    }
    
    
		    $newimage=imagecreatetruecolor($n_width,$n_height);
		    //imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
		    imagecopyresampled ($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
		    if (function_exists("imagegif"))
		    {
			    //Header("Content-type: image/gif");
			    ImageGIF($newimage,$tsrc);
		    }
		    elseif (function_exists("imagejpeg"))
		    {
			    //Header("Content-type: image/jpeg");
			    ImageJPEG($newimage,$tsrc, 100);
		    }
		    chmod("$tsrc",0777);
	    }////////// end of gif file thumb nail creation//////////
    
	    ////////////// starting of JPG thumb nail creation//////////
	    if($type=="image/pjpeg" || $type=="image/jpeg")
	    {
		    $im=ImageCreateFromJPEG($filepath);
		    $width=ImageSx($im);              // Original picture width is stored
		    $height=ImageSy($im);             // Original picture height is stored
    
		    $ratio = $width / $height;
    
		    if( $height > $width && !$forceWidth )
		    {
			    $ratio = $height / $width;
			    $n_height= ( $height > $homeImageHeight ) ? $homeImageHeight : $height;
			    $n_width = round($n_height / $ratio);
		    }
		    else
		    {
			    $ratio = $width / $height;
			    $n_width= ( $width > $homeImageWidth ) ? $homeImageWidth : $width;
			    $n_height= round($n_width / $ratio);
		    }
    
		    $newimage=imagecreatetruecolor($n_width,$n_height);
		    imagecopyresampled($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
		    ImageJpeg($newimage,$tsrc);
		    chmod("$tsrc",0777);
	    }
    
	    ////////////////  End of JPG thumb nail creation //////////
	    
	    
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
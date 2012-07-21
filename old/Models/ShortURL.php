<?php

    require_once 'PdoConnect.php';
    
    class ShortURL extends PdoConnect {
        
        
        function makeShorter($url) {
            //bit.ly defaults
            $bitly_version 	= '2.0.1';
            $bitly_history	= 1;
            $bitly_login = "argetloum";
            $bitly_apiKey = "R_4d3de30bde2909ca97137c255976bbf2";
            //url à interroger pour le retour via XML
            $connectURL = 'http://api.bit.ly/shorten?version='.$bitly_version.'&amp;longUrl='.$url.'&amp;login='.$bitly_login.'&amp;apiKey='.$bitly_apiKey.'&amp;history='.$bitly_history.'&amp;format=xml&amp;callback=?';
            
            //lire le contenu retourné par l'URL
            $content = file_get_contents($connectURL);
            
            if ($content != false) {
                    //créer l'object avec SimpleXML (PHP 5)
                    $bitly = new SimpleXMLElement($content);
                    //s'assurer qu'il n'y a pas d'erreur
                    if($bitly->errorCode == 0)
                            return $bitly->results[0]->nodeKeyVal->shortUrl;
            }
        
        }
    
    }
    
?>
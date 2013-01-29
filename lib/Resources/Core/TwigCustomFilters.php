<?php
/**
 * Description of TwigCustomFilters
 *
 * @author Florian
 */
class TwigCustomFilters {
    
    /**
     * Récupère les filtres custom
     * 
     * @param array $filters
     * @return array $filters
     */
    public static function getCustomFilters($filters){
        
        $filters['urlAlize'] = new Twig_Filter_Function("urlAlize");
        $filters['couperTexte'] = new Twig_Filter_Function("couperTexte");
        $filters['pictureFromTxt'] = new Twig_Filter_Function("pictureFromTxt");
        $filters['resizeImage'] = new Twig_Filter_Function("resizeImage");
        
        return $filters;
    }
    
}

function urlAlize($str){
    return FwkUtils::urlAlize($str);
}

function couperTexte($str, $length){
    return FwkUtils::couperTexte($length, $str);
}

function pictureFromTxt($str){
    return FwkUtils::getPictureFromText($str);
}

function resizeImage($image, $width = 0, $height = 0, $color = false, $cropratio = false, $quality = false){
    $imageResizer = new ImageResizer($image, $width, $height, $color, $cropratio, $quality);
    return $imageResizer->initialize();
}

?>
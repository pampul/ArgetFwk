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
        
        return $filters;
    }
    
}

function urlAlize($str){
    return FwkUtils::urlAlize($str);
}

?>

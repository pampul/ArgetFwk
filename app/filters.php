<?php

/*
 * ArgetFilters :
 * Appel d'un filtre en fonction du pattern
 * -----
 * Si pas de pattern : on est en homepage
 */
switch(GET_PATTERN){
    
    case 'mon-pattern-pour-filtre' :
        //require_once PATH_TO_IMPORTANT_FILES.'app/lib/Filters/MonFilter.php';
        continue;
    /*
     * Le passage par default est necessaire d'où le continue présent precedemment
     */
    default :
        break;
    
}

?>

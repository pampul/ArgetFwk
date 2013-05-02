<?php

/*
 * ArgetFilters :
 * Appel d'un filtre en fonction du pattern
 * -----
 * Si pas de pattern : on est en homepage
 */
switch(GET_PATTERN){

  case 'test' :
    // require + new Filter()
    continue;
  /*
   * Le passage par default est necessaire d'où le continue présent precedemment
   */
  default :
    require_once PATH_TO_IMPORTANT_FILES.'lib/Resources/Filters/AuthGestionFilter.php';
    new AuthGestionFilter();
    break;

}

?>

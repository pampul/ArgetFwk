<?php

/**
 * Classe de batch de base
 *
 * @author f.mithieux
 */
class batch extends SecuredClass {

    public function execute() {

        $this->startLog();
        /*
         * Code right here
         */
        
        
    }

    private function startLog() {

        $message = '
    

--------------------------------------------------
              LANCEMENT DU BTACH - TEST
--------------------------------------------------

';

        FwkLog::add($message, PATH_TO_IMPORTANT_FILES . 'logs/', 'batch/');
    }

}
?>
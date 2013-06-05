<?php

/**
 *
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */
class TestController extends ControllerManager {

  protected function testPageController() {

    if (isset($_POST['test'])) {

      $objFwkUploader = new FwkUpload(PATH_TO_BACKOFFICE_FILES . 'web/uploads/tests/');
      $objFwkUploader->setFileType('fichier');
      $objFwkUploader->setMaxSize(9999999);
      $objFwkUploader->setValidFormats('pdf');
      $arrayResult = $objFwkUploader->upload($_FILES['myupload']);
      var_dump($arrayResult);
      die();

    }

    $this->renderView('views/test-page.html.twig');
  }

}

?>

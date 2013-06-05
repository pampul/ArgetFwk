<?php

/**
 * Classe de génération de corbeille en json
 *
 * @author f.mithieux
 */
class FwkUpload extends FwkManager {

  /**
   * @var string chemin des uploads
   */
  private $pathUploads;

  /**
   * @var string type de fichier
   */
  private $fileType = 'image';

  /**
   * @var array formats valides
   */
  private $validFormats;

  /**
   * @var integer $maxSize
   */
  private $maxSize = 5000;

  /**
   * @var $_FILES fichier à uploader
   */
  private $file;

  /**
   * @var string
   */
  private $fileName;

  /**
   * @var string
   */
  private $fileExt;

  /**
   * @var integer
   */
  private $fileSize;

  /**
   * @var boolean
   */
  private $redimensionner = false;

  /**
   * @var array
   */
  private $mimeTypeBlackList = array(# HTML may contain cookie-stealing JavaScript and web bugs
    'text/html', 'text/javascript', 'text/x-javascript', 'application/x-shellscript', # PHP scripts may execute arbitrary code on the server
    'application/x-php', 'text/x-php', 'text/x-php', # Other types that may be interpreted by some servers
    'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh', 'text/x-c++', 'text/x-c',# Windows metafile, client-side vulnerability on some systems
    # 'application/x-msmetafile',
    # A ZIP file may be a valid Java archive containing an applet which exploits the
    # same-origin policy to steal cookies
    # 'application/zip',
  );

  public function __construct($pathUploads) {

    $this->pathUploads = $pathUploads;
  }

  /**
   * Type de fichier à définir : "image" ou "fichier"
   *
   * @param string $type
   */
  public function setFileType($type) {
    $this->fileType = $type;
  }

  /**
   * Définit les formats valides
   *
   * @param string $formats ex : 'jpg,jpeg,gif'
   */
  public function setValidFormats($formats) {
    $formats            = preg_replace('#\s#', '', trim($formats));
    $this->validFormats = explode(',', $formats);
  }

  /**
   * Définit la taille maximum
   *
   * @param integer $maxSize
   */
  public function setMaxSize($maxSize) {
    $this->maxSize = $maxSize;
  }

  /**
   * Redimmensionner l'image
   *
   * @param boolean $bool
   */
  public function setRedimensionner($bool) {
    $this->redimensionner = $bool;
  }

  /**
   * Upload du fichier
   *
   * @param array $FILE
   * @return array
   */
  public function upload($FILE) {

    $this->file = $FILE;

    if (!strlen($this->file['name']))
      return array('error' => 'Fichier vide.');

    $arrayFileName  = explode(".", $this->file['name']);
    $this->fileExt  = array_pop($arrayFileName);
    $this->fileName = implode('.', $arrayFileName);
    $this->fileSize = $this->file['size'];

    if ($this->checkMimeType()) {

      if (in_array($this->fileExt, $this->validFormats)) {
        if ($this->fileSize < $this->maxSize) {

          if (!is_dir($this->pathUploads))
            FwkUtils::Mkpath('', $this->pathUploads);
          if (is_dir($this->pathUploads)) {

            switch ($this->fileType) {
              case 'fichier':
                return $this->uploadFile();
                break;

              case 'image':
              default:
                return $this->uploadImage();
                break;
            }
          } else
            return array('error' => 'Chemin incorrect. (' . $this->pathUploads . ')');
        } else
          return array('error' => 'Fichier trop volumineux. (' . $this->fileSize . 'o)');
      } else
        return array('error' => 'Format ".' . $this->fileExt . '" invalide.');
    } else
      return array('error' => 'Type MIME invalide : Le type de fichier est incorrect.');
  }

  /**
   * Upload d'une image
   *
   * @return array
   */
  private function uploadImage() {

    if ($this->redimensionner) {
      // On redimensionne l'image et on retourne son nom
      // Not set yet
    }

    return $this->uploadOver();
  }

  /**
   * Upload d'un fichier
   *
   * @return array
   */
  private function uploadFile() {
    return $this->uploadOver();
  }

  /**
   * Fin de l'upload du fichier
   *
   * @return array
   */
  private function uploadOver() {
    $this->fileName = FwkUtils::checkFileName($this->pathUploads, FwkUtils::urlAlize($this->fileName), $this->fileExt);
    if (move_uploaded_file($this->file['tmp_name'], $this->pathUploads . $this->fileName . '.' . $this->fileExt))
      return array('success' => $this->fileName . '.' . $this->fileExt); else
      return array('error' => 'Upload error.');
  }

  /**
   * On vérifie si le type MIME n'est pas dans la blacklist
   *
   * @return bool
   */
  private function checkMimeType() {

    $finfo = new finfo(FILEINFO_MIME);

    if ($finfo)
      $mime = $finfo->file($this->file['tmp_name']);

    else
      $mime = $this->file['tmp_name'];

    $mime = explode(" ", $mime);
    $mime = $mime[0];

    if (substr($mime, -1, 1) == ";")
      $mime = trim(substr($mime, 0, -1));

    return (in_array($mime, $this->mimeTypeBlackList) == false);

  }

}

?>

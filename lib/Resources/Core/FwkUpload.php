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
    private $fileType;

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
        $formats = preg_replace('#\s#', '', trim($formats));
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
     * @param boolean $bool
     */
    public function setRedimensionner($bool) {
        $this->redimensionner = $bool;
    }

    /**
     * Upload du fichier
     * 
     * @param $_FILES $FILE
     * @return array
     */
    public function upload($FILE) {

        $this->file = $FILE;

        if (!strlen($this->file['name']))
            return array('error' => 'Fichier vide.');

        $arrayFileName = explode(".", $this->file['name']);
        $this->fileExt = array_pop($arrayFileName);
        $this->fileName = implode('.', $arrayFileName);
        $this->fileSize = $this->file['size'];

        if (in_array($this->fileExt, $this->validFormats)) {
            if ($this->fileSize < $this->maxSize) {

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
                }
                else
                    return array('error' => 'Chemin incorrect. (' . $this->pathUploads . ')');
            }
            else
                return array('error' => 'Fichier trop volumineux. (' . $this->fileSize . 'ko)');
        }
        else
            return array('error' => 'Format ".' . $this->fileExt . '" invalide.');
    }

    private function uploadImage() {

        if ($this->redimensionner) {
            // On redimensionne l'image et on retourne son nom
        }
        return $this->uploadOver();
    }

    private function uploadFile() {
        return $this->uploadOver();
    }

    private function uploadOver() {
        $this->fileName = FwkUtils::checkFileName($this->pathUploads, FwkUtils::urlAlize($this->fileName), $this->fileExt);
        if (move_uploaded_file($this->file['tmp_name'], $this->pathUploads . $this->fileName . '.' . $this->fileExt))
            return array('success' => $this->fileName . '.' . $this->fileExt);
        else
            return array('error' => 'Upload error.');
    }

}

?>

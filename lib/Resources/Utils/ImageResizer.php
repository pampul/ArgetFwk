<?php

/**
 * Image Resizer
 * Resize une image, crop dynamiquement
 * 
 * 
 * ##############
 * REQUIREMENTS
 * ##############
 * 
 * PHP 5.3+ et librairie GD
 * TWIG
 * 
 * ##############
 * PARAMETERS
 * ##############
 * Les paramètres sont à donner dans une string en get
 * image            chemin ABSOLU de l'image existente (e.g. /images/toast.jpg)
 * width            maximum width pour l'image (e.g. 700)
 * height           maximum height pour l'image (e.g. 700)
 * color            (optionnel) background hex color for filling transparent PNGs (e.g. 900 or 16a942)
 * cropratio        (optionnel) ratio width-height pour crop l'image (e.g. 1:1 or 3:2)
 * quality          (optionnel, 0-100, default: 90) qualité de l'image de sortie
 * 
 * 
 * ##############
 * EXEMPLES
 * ##############
 * Resize d'une jpeg :
 * <img src="/image.php/image-name.jpg?width=100&amp;height=100&amp;image=/path/to/image.jpg" alt=" />
 * Resize et crop d'une jpeg:
 * <img src="/image.php/image-name.jpg?width=100&amp;height=100&amp;cropratio=1:1&amp;image=/path/to/image.jpg" alt="" />
 * Rendre mat une png avec #990000:
 * <img src="/image.php/image-name.png?color=900&amp;image=/path/to/image.png" alt="" />
 * 
 * 
 *
 * @author f.mithieux
 */
class ImageResizer {

    private $memoryToAllocate = '100M';
    private $defaultQuality = 90;
    private $cacheDir;
    private $documentRoot;
    private $image;
    private $currentImageWidth;
    private $currentImageHeight;
    private $requestImageWidth;
    private $requestImageHeight;
    private $requestColor;
    private $requestCropRatio;
    private $requestQuality;

    
    /**
     * Constructeur de la classe ImageResizer
     * 
     * @param string $image - chemin de l'image d'origine
     * @param int $width - taille max
     * @param int $height - hauteur max
     * @param string $color - couleur rgba
     * @param string $cropratio - ratio de coupage
     * @param int $quality - qualité image
     */
    public function __construct($image, $width = 0, $height = 0, $color = false, $cropratio = false, $quality = false) {

        if (!isset($image) || strlen($image) < 5) {
            FwkLog::add('ImageResizer :: Image inexistante en paramètre GET.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
            header('HTTP/1.1 400 Bad Request');
            exit();
        }

        $this->cacheDir = __DIR__ . '/../Twig/Cache/_Images/';
        $this->documentRoot = __DIR__ . '/../../../';
        // Suppression du http (si existant)
        $this->image = preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', (string) $image);
        $this->requestImageWidth = (int)$width;
        $this->requestImageHeight = (int)$height;
        if ($color)
            $this->requestColor = preg_replace('/[^0-9a-fA-F]/', '', (string) $color);
        else
            $this->requestColor = false;
        $this->requestCropRatio = $cropratio;
        $this->requestQuality = ($quality) ? (int) $quality : $this->defaultQuality;
    }

    /**
     * Fonction générant l'image
     * 
     * @return Image
     */
    public function initialize() {

        // Vérification de l'existance de l'image
        if (!$this->image) {
            FwkLog::add('ImageResizer :: Image inexistante.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
            return null;
        }

        // Suppression des slashs en trop
        $this->documentRoot = preg_replace('/\/$/', '', $this->documentRoot);

        if (!file_exists($this->documentRoot . $this->image)) {
            header('HTTP/1.1 404 Not Found');
            FwkLog::add('ImageResizer :: Image inexistante au chemin : ' . $this->documentRoot . $this->image . '.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
            return null;
        }

        // On récupère la taille et le type mime de l'image
        $size = GetImageSize($this->documentRoot . $this->image);
        $mime = $size['mime'];

        // On vérifie que le fichier est une image
        if (substr($mime, 0, 6) != 'image/') {
            header('HTTP/1.1 400 Bad Request');
            FwkLog::add('ImageResizer :: Le fichier demandé n\'est pas une image.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
            return null;
        }

        $this->currentImageWidth = $size[0];
        $this->currentImageHeight = $size[1];

        /*
         * Si la max width et max height ne sont pas spécifiés
         * On ne donne pas de contrainte de taille
         */
        if (!$this->requestImageWidth && $this->requestImageHeight) {
            $this->requestImageWidth = 99999999999999;
        } elseif ($this->requestImageWidth && !$this->requestImageHeight) {
            $this->requestImageHeight = 99999999999999;
        } elseif ($this->requestColor && !$this->requestImageWidth && !$this->requestImageHeight) {
            $this->requestImageWidth = $this->currentImageWidth;
            $this->requestImageHeight = $this->currentImageHeight;
        }

        /*
         * Si on a pas de max width ou de max height, ou que l'image est plus petite que les deux,
         * on n'a pas besoin de la rétrécir, on affiche donc l'originale
         */
        if ((!$this->requestImageWidth && !$this->requestImageHeight) || (!$this->requestColor && $this->requestImageWidth >= $this->currentImageWidth && $this->requestImageHeight >= $this->currentImageHeight)) {
            $data = file_get_contents($this->documentRoot . $this->image);

            $lastModifiedString = gmdate('D, d M Y H:i:s', filemtime($this->documentRoot . $this->image)) . ' GMT';
            $etag = md5($data);

            $this->doConditionalGet($etag, $lastModifiedString);

            header("Content-type: $mime");
            header('Content-Length: ' . strlen($data));
            return $data;
        }

        /*
         * Ratio de crop
         */
        $offsetX = 0;
        $offsetY = 0;

        if ($this->requestCropRatio) {
            $cropRatio = explode(':', (string) $this->requestCropRatio);
            if (count($cropRatio) == 2) {
                $ratioComputed = $this->currentImageWidth / $this->currentImageHeight;
                $cropRatioComputed = (float) $cropRatio[0] / (float) $cropRatio[1];

                if ($ratioComputed < $cropRatioComputed) {
                    // L'image est trop petite, elle va être crop du top au bottom
                    $origHeight = $this->currentImageHeight;
                    $this->currentImageHeight = $this->currentImageWidth / $cropRatioComputed;
                    $offsetY = ($origHeight - $this->currentImageHeight) / 2;
                } else if ($ratioComputed > $cropRatioComputed) {
                    // Image est trop large elle va être crop de droite à gauche
                    $origWidth = $this->currentImageWidth;
                    $this->currentImageWidth = $this->currentImageHeight * $cropRatioComputed;
                    $offsetX = ($origWidth - $this->currentImageWidth) / 2;
                }
            }
        }

        /*
         * Mise en place des ratios nécessaires au redimmensionnement
         * On va déterminer s'il faut resize en largeur ou hauteur
         */
        $xRatio = $this->requestImageWidth / $this->currentImageWidth;
        $yRatio = $this->requestImageHeight / $this->currentImageHeight;

        if ($xRatio * $this->currentImageHeight < $this->requestImageHeight) { // Resize the image based on width
            $tnHeight = ceil($xRatio * $this->currentImageHeight);
            $tnWidth = $this->requestImageWidth;
        } else {
            // Redimensionnement basé sur la hauteur
            $tnWidth = ceil($yRatio * $this->currentImageWidth);
            $tnHeight = $this->requestImageHeight;
        }


        /*
         * Avant de travailler avec la librairie GD, on travaille avec le CACHE !
         * On va donc stocker nos images avec un hash md5 pour qu'elles soient légères
         */
        $resizedImageSource = $tnWidth . 'x' . $tnHeight . 'x' . $quality;
        if ($this->requestColor)
            $resizedImageSource .= 'x' . $this->requestColor;
        if ($this->requestCropRatio)
            $resizedImageSource .= 'x' . (string) $this->requestCropRatio;
        $resizedImageSource .= '-' . $this->image;

        $resizedImage = md5($resizedImageSource);

        $resized = $this->cacheDir . $resizedImage;

        /*
         * Dans le cas où on a le cache activé et que le fichier existe déjà ...
         */
        if (IMAGE_RESIZER_CACHE && file_exists($resized)) {
            $imageModified = filemtime($this->documentRoot . $this->image);
            $thumbModified = filemtime($resized);

            if ($imageModified < $thumbModified) {
                $data = file_get_contents($resized);

                $lastModifiedString = gmdate('D, d M Y H:i:s', $thumbModified) . ' GMT';
                $etag = md5($data);

                $this->doConditionalGet($etag, $lastModifiedString);

                header("Content-type: $mime");
                header('Content-Length: ' . strlen($data));
                return $data;
            }
        }

        // On ne veut pas abuser de la mémoire serveur
        ini_set('memory_limit', $this->memoryToAllocate);

        // Set up a blank canvas for our resized image (destination)
        $dst = imagecreatetruecolor($tnWidth, $tnHeight);

        // Met en place le handler approprié pour le type de l'image
        switch ($size['mime']) {
            case 'image/gif':
                // On converti les gif en png pour obtenir une transparence
                $creationFunction = 'ImageCreateFromGif';
                $outputFunction = 'ImagePng';
                // On change le type du mime
                $mime = 'image/png';
                $doSharpen = FALSE;
                $quality = round(10 - ($quality / 10));
                break;

            case 'image/x-png':
            case 'image/png':
                $creationFunction = 'ImageCreateFromPng';
                $outputFunction = 'ImagePng';
                $doSharpen = FALSE;
                $quality = round(10 - ($quality / 10));
                break;

            default:
                $creationFunction = 'ImageCreateFromJpeg';
                $outputFunction = 'ImageJpeg';
                $doSharpen = TRUE;
                break;
        }

        // On lie l'image originale
        $src = $creationFunction($this->documentRoot . $this->image);

        if (in_array($size['mime'], array('image/gif', 'image/png'))) {
            if (!$this->requestColor) {
                // Si c'est une gif ou png, il faut définir une transparence
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            } else {
                // Défini la couleur de fond
                if ($this->requestColor[0] == '#')
                    $this->requestColor = substr($this->requestColor, 1);

                $background = FALSE;

                if (strlen($this->requestColor) == 6)
                    $background = imagecolorallocate($dst, hexdec($this->requestColor[0] . $this->requestColor[1]), hexdec($this->requestColor[2] . $this->requestColor[3]), hexdec($this->requestColor[4] . $this->requestColor[5]));
                else if (strlen($this->requestColor) == 3)
                    $background = imagecolorallocate($dst, hexdec($this->requestColor[0] . $this->requestColor[0]), hexdec($this->requestColor[1] . $this->requestColor[1]), hexdec($this->requestColor[2] . $this->requestColor[2]));
                if ($background)
                    imagefill($dst, 0, 0, $background);
            }
        }

        // Réécriture de l'image en canvas souhaité
        ImageCopyResampled($dst, $src, 0, 0, $offsetX, $offsetY, $tnWidth, $tnHeight, $this->currentImageWidth, $this->currentImageHeight);

        if ($doSharpen) {
            // Sharpen the image based on two things:
            //	(1) the difference between the original size and the final size
            //	(2) the final size
            $sharpness = $this->findSharp($this->currentImageWidth, $tnWidth);

            $sharpenMatrix = array(
                array(-1, -2, -1),
                array(-2, $sharpness + 12, -2),
                array(-1, -2, -1)
            );
            $divisor = $sharpness;
            $offset = 0;
            imageconvolution($dst, $sharpenMatrix, $divisor, $offset);
        }

        // Vérification de la dir /cache/
        if (!file_exists($this->cacheDir))
            mkdir($this->cacheDir, 0755);

        // Test d'ecriture/lecture de la directory cache
        if (!is_readable($this->cacheDir)) {
            header('HTTP/1.1 500 Internal Server Error');
            FwkLog::add('ImageResizer :: Le chemin du cache est inaccessible.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
            return null;
        } else if (!is_writable(CACHE_DIR)) {
            header('HTTP/1.1 500 Internal Server Error');
            FwkLog::add('ImageResizer :: Le chemin du cache n\'est pas ouvert à l\'écriture.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
            return null;
        }

        // Créé l'image resizé à la destination choisie
        $outputFunction($dst, $resized, $quality);

        // Utilisation du cache
        ob_start();
        $outputFunction($dst, null, $quality);
        $data = ob_get_contents();
        ob_end_clean();

        // Libération de la mémoire
        ImageDestroy($src);
        ImageDestroy($dst);

        // Envoi de l'image finale
        $lastModifiedString = gmdate('D, d M Y H:i:s', filemtime($resized)) . ' GMT';
        $etag = md5($data);

        $this->doConditionalGet($etag, $lastModifiedString);

        // Send the image to the browser with some delicious headers
        header("Content-type: $mime");
        header('Content-Length: ' . strlen($data));
        return $data;
    }

    private function findSharp($orig, $final) {
        // function from Ryan Rud (http://adryrun.com)
        $final = $final * (750.0 / $orig);
        $a = 52;
        $b = -0.27810650887573124;
        $c = .00047337278106508946;

        $result = $a + $b * $final + $c * $final * $final;

        return max(round($result), 0);
    }

    private function doConditionalGet($etag, $lastModified) {
        header("Last-Modified: $lastModified");
        header("ETag: \"{$etag}\"");

        $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
                stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) :
                false;

        $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
                stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
                false;

        if (!$if_modified_since && !$if_none_match)
            return;

        if ($if_none_match && $if_none_match != $etag && $if_none_match != '"' . $etag . '"')
            return; // etag is there but doesn't match

        if ($if_modified_since && $if_modified_since != $lastModified)
            return; // if-modified-since is there but doesn't match


            
        // Nothing has changed since their last request - serve a 304 and exit
        header('HTTP/1.1 304 Not Modified');
        FwkLog::add('ImageResizer :: Non modifié.', PATH_TO_IMPORTANT_FILES . 'logs/' . 'errors');
        exit();
    }

}

?>

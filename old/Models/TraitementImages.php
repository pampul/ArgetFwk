<?php
    /**
    * Classe ImgTraitement
    *
    * Class De traitement d'image
    * <code>
    * $img = new Imgtraitement('nen.jpg');
    * $img->resize(320,0);
    * $img->save('test2.jpg');
    * </code>
    *
    * @version 1.1.0
    * @author Olivier ROGER <>
    *
    * 
    *
    *
    */
    
    
    class TraitementImages
    {
    /**
    * @access private
    * @var int
    */
    private $width;
    /**
    * @var int
    * @access private
    */
    private $height;
    /**
    * Type de l image : 1 = gif ; 2 = jpeg; 3= png
    * @var string
    * @access private
    *
    */
    private $type;
    /**
    * @var string $mime Type mime de l image
    * @access private
    */
    private $mime;
    /**
    * @var img Fichier image source
    * @access private
    */
    private $source;
    private $copie;
    /**
    * @var string Police d'ecriture selectionnee. Defaut verdanna.
    * @access private
    */
    private $font;
    /**
    * @var ressource Couleur choisi (par defaut bordeau)
    * @access private
    */
    private $couleur;
    /**
    * @var ressource autre image a coller sur l image d origine
    * @access private
    */
    private $logo;
    /**
    * @var ressource Largeur du logo
    * @access private
    */
    private $widthL;
    /**
    * @var ressource Hauteur du logo
    * @access private
    */
    private $heightL;
    /**
    * @var ressource Type du logo
    * @access private
    */
    private $typeL;
    /**
    * @var array Info de l'image
    * @access private
    */
    private $infoImage;
    /**
    * @var int Poids de l'image
    * @access private
    */
    private $poids;
    /**
    * Construteur
    */
    public function __construct($img)
    {
    if(!empty($img) && file_exists($img))
    {
    $info = getimagesize($img);
    $this->width = $info[0];
    $this->height = $info[1];
    $this->type = $info[2]; // 1:gif; 2:jpg; 3:png; 4:swf; 5:psd; 6:bmp; 7:tiff
    $this->mime = $info['mime'];
    $this->copie = null;
    $this->source = $this->createFromType($img);
    if($this->source == false)
    {
    echo 'Format d\'image non supporté';
    exit;
    }
    $this->couleur = imagecolorallocate($this->source,187,3,33);
    $this->poids = filesize($img);
    $this->infoImage = array();
    $this->infoImage['extension'] = strchr($img, '.');
    $this->infoImage['extension'] = substr($this->infoImage['extension'], 1); // Récupère l'extension après le .
    $this->infoImage['extension'] = strtolower($this->infoImage['extension']);
    }
    }
    /**
    * Récupère les informations de l'image
    * @access public
    * @return array Information de l'image (reso,poids,extension,mime)
    */
    public function getInfo()
    {
    $this->infoImage['width'] = $this->width;
    $this->infoImage['height'] = $this->height;
    $this->infoImage['mime'] = $this->mime;
    $this->infoImage['poids'] = round($this->poids/1024,2);
    return $this->infoImage;
    }
    /**
    * Ajoute une image en tant que logo
    * @access public
    * @param string chemin vers l'image
    */
    public function addLogo($logo)
    {
    list($this->widthL,$this->heightL,$this->typeL) = getimagesize($logo);
    $this->logo = $this->createFromType($logo);
    imagealphablending($this->logo,TRUE); // Activation de la transparence
    }
    /**
    * Ajoute le logo à l'image principale
    * @access public
    * @param string $pos Position du logo : ct(centre),hg(haut gauche),hd,bg(bas gauche),bd(défaut)
    * @param int $opacite % d'opacité du logo , par défaut 75
    * @return bool
    */
    public function mergeLogo($pos='bd',$opacite=75)
    {
    if($pos =='hg')
    {
    $posX = 0;
    $posY = 0;
    }
    elseif($pos=='hd')
    {
    $posX = ($this->width - $this->widthL);
    $posY = 0;
    }
    elseif($pos == 'bg')
    {
    $posX = 0;
    $posY = ($this->height - $this->heightL);
    }
    elseif($pos == 'bd')
    {
    $posX = ($this->width - $this->widthL);
    $posY = ($this->height - $this->heightL);
    }
    elseif($pos == 'ct')
    {
    $posX = (($this->width/2) - ($this->widthL/2));
    $posY = (($this->height/2) - ($this->heightL/2));
    }
    if(imagecopymerge($this->source,$this->logo,$posX,$posY,0,0,$this->widthL,$this->heightL,$opacite))
    return true;
    else
    return false;
    }
    /**
    * Créer une ressource image selon son type
    * @access protected
    * @param string image
    * @return ressource image
    */
    protected function createFromType($img)
    {
    if($this->type==1)
    $crea = imagecreatefromgif($img);
    elseif($this->type == 2)
    $crea = imagecreatefromjpeg($img);
    elseif($this->type == 3)
    $crea = imagecreatefrompng($img);
    else
    $crea = false;
    return $crea;
    }
    /**
    * Convertit l'image en niveau de gris. Imagefilter remplace les calcul via matrice. 10x plus rapide
    * @access public
    */
    public function greyScale()
    {
    imagefilter($this->source,IMG_FILTER_GRAYSCALE);
    }
    /**
    * Ajoute du flou à l'image. Imagefilter remplace les calcul via matrice. 10x plus rapide
    * @access public
    * @param int $factor Facteur de flou
    */
    public function blur($factor)
    {
    imagefilter($this->source,IMG_FILTER_GAUSSIAN_BLUR,$factor);
    }
    /**
    * Ajoute du bruit à l'image. (relativement long a executer puisque traitement px par px)
    * @access public
    * @param int $factor paramètre de bruit (0-255)
    * @return bool
    */
    public function addNoise($factor)
    {
    for($x=0;$x<$this->width;$x++)
    {
    for($y=0;$y<$this->height;$y++)
    {
    $rand = mt_rand(-$factor,$factor);
    $rgb = imagecolorat($this->source,$x,$y);
    $r = (($rgb>>16)& 0xFF)+$rand;
    $g = (($rgb>>8)& 0xFF)+$rand;
    $b = ($rgb & 0xFF)+$rand;
    $color = imagecolorallocate($this->source,$r,$g,$b);
    if(imagesetpixel($this->source,$x,$y,$color))
    return true;
    else
    return false;
    }
    }
    }
    /**
    * Netteté
    * @access public
    */
    public function sharppen()
    {
    imagefilter($this->source,IMG_FILTER_MEAN_REMOVAL);
    }
    /**
    * Modifie le contraste de l'image. Imagefilter remplace les calcul via matrice. 10x plus rapide
    * @access public
    * @param int $factor Facteur de flou
    */
    public function contrast($factor)
    {
    imagefilter($this->source,IMG_FILTER_CONTRAST,$factor);
    }
    /**
    * Modifie la luminosité. Imagefilter remplace les calcul via matrice. 10x plus rapide
    * @access private
    * @param int $factor Facteur de flou
    */
    public function brightness($factor)
    {
    imagefilter($this->source,IMG_FILTER_BRIGHTNESS,$factor);
    }
    /**
    * Créer une copie de l image original pour restauration ulterieur.
    * @access public
    */
    public function duplicate()
    {
    $this->copie = imagecreatetruecolor($this->width,$this->height);
    imagecopy($this->copie,$this->source,0,0,0,0,$this->width,$this->height);
    }
    /**
    * Restaure la copie de sauvegarde de l'image
    * @access public
    */
    public function restore()
    {
    $this->source = $this->copie;
    }
    /**
    * Permet le changement de police. indiquer le chemin vers le fichier ttf
    * @access public
    * @param string $path Chemin vers la police
    */
    public function setFont($path)
    {
    $this->font = $path;
    }
    /**
    * Permet de définir une couleur à utiliser
    * @access public
    * @param int $r composante rouge
    * @param int $v composante verte
    * @param int $b composante bleue
    */
    public function setColor($r,$v,$b)
    {
    $this->couleur = imagecolorallocate($this->source,$r,$v,$b);
    }
    /**
    * Ecrit un texte sur l image aux positions données
    * @access public
    * @param string $texte Texte à afficher
    * @param int $size Taille du texte
    * @param int $x Position en X
    * @param int $y Position en Y
    * @param bool $rect Ajout ou non d'un rectangle blanc sous le texte
    */
    public function setText($texte,$size,$x,$y,$rect)
    {
    if($rect)
    {
    $rectText = imageftbbox($size,0,$this->font,$texte); // Retourne les coordoonées du text
    $wText = abs($rectText[4]-$rectText[0]); // Largeur du texte
    $hText = abs($rectText[1]-$rectText[5]); // hauteur du texte
    $this->setColor(255,255,255); // Fond blanc
    imagefilledrectangle($this->source,$x,($y-$hText),($x+$wText+5),($y+5),$this->couleur); //($y-$htext) permet de placer
    // le rectangle au bon endroit
    $this->setColor(0,0,0); // Texte noir
    imagettftext($this->source,$size,0,$x,$y,$this->couleur,$this->font,$texte);
    }
    else
    {
    imagettftext($this->source,$size,0,$x,$y,$this->couleur,$this->font,$texte);
    }
    }
    /**
    * Redimensionne l'image. Si une des deux dimension = 0. Redimensionnement proportionnel sur celle donnée
    * @param int $newW Largeur souahitée
    * @param int $newH Hauteur souhaitée
    * @access public
    * @return bool
    */
    public function resize($newW,$newH)
    {
    if($newW == 0) // Largeur non spécifiée donc dimension basé sur hauteur
    {
    $scale = $newH/$this->height;
    $newW = $this->width * $scale;
    }
    elseif($newH == 0) // Hauteur non spécifiée donc dimension basé sur largeur
    {
    $scale = $newW / $this->width;
    $newH = $this->height * $scale;
    }
    $tempImg = imagecreatetruecolor($newW,$newH);
    if(imagecopyresampled($tempImg,$this->source,0,0,0,0,$newW,$newH,$this->width,$this->height))
    {
    $this->source = $tempImg;
    $this->width = $newW;
    $this->height = $newH;
    return true;
    }
    else
    {
    return false;
    }
    }
    /**
    * Crop une image aux dimensions voulues et à partir de l'endroit voulu
    *
    * @param int $cropW Largeur de la zone de crop
    * @param int $cropH Hauteur de la zone de crop
    * @param int $cropStartX Coordonnées en X de départ
    * @param int $cropStartY Coordonnées en Y de départ
    * @return bool
    */
    public function crop($cropW,$cropH,$cropStartX,$cropStartY)
    {
    $tempImg = imagecreatetruecolor($cropW,$cropH);
    if(imagecopyresized($tempImg, $this->source, 0, 0, $cropStartX, $cropStartY, $cropW, $cropH, $cropW, $cropH))
    {
    $this->source = $tempImg;
    $this->width = $cropW;
    $this->height = $cropH;
    return true;
    }
    else
    {
    return false;
    }
    }
    /**
    * Sauvegarde l'image sur le disque
    * @access public
    * @param string $file Nom et chemin de fichier
    * @return bool
    */
    public function save($file,$qualite=95)
    {
    if($this->type==1)
    {
    if(imagegif($this->source,$file))
    return true;
    else
    return false;
    }
    elseif($this->type == 2)
    {
    if(imagejpeg($this->source,$file,$qualite))
    return true;
    else
    return false;
    }
    elseif($this->type == 3)
    {
    if(imagepng($this->source,$file))
    return true;
    else
    return false;
    }
    }
    /**
    * Affiche l'image sur la sortie standard
    * @access public
    * @return img
    */
    public function display($qualite=100)
    {
    if($this->type==1)
    {
    header("Content-type: image/gif");
    return imagegif($this->source);
    }
    elseif($this->type == 2)
    {
    header("Content-type: image/jpeg");
    return imagejpeg($this->source,'',$qualite);
    }
    elseif($this->type == 3)
    {
    header("Content-type: image/png");
    return imagepng($this->source);
    }
    }
    /**
    * Destructeur
    */
    public function __destruct()
    {
    imagedestroy($this->source);
    @imagedestroy($tempImg);
    if($this->copie!=null)
    @imagedestroy($this->copie);
    }
    }
?>


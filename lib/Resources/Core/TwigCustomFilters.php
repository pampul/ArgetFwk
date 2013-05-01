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
  public static function getCustomFilters($filters) {

    $filters['urlAlize'] = new Twig_Filter_Function("urlAlize");
    $filters['couperTexte'] = new Twig_Filter_Function("couperTexte");
    $filters['pictureFromTxt'] = new Twig_Filter_Function("pictureFromTxt");
    $filters['resizeImage'] = new Twig_Filter_Function("resizeImage");
    $filters['getGravatar'] = new Twig_Filter_Function("getGravatar");

    return $filters;
  }

}

function urlAlize($str) {
  return FwkUtils::urlAlize($str);
}

function couperTexte($str, $length) {
  return FwkUtils::couperTexte($length, $str);
}

function pictureFromTxt($str) {
  return FwkUtils::getPictureFromText($str);
}

function resizeImage($imageUrl, $complUrl = '', $width = null, $height = null, $folder = 'Thumbs') {

  $ext = FwkUtils::getExtension($imageUrl);
  $imageName = md5($imageUrl . $width . $height) . '.' . $ext;
  $partialDir = __DIR__ . '/../Twig/Cache/_Images/';
  $fullDir = $partialDir . $folder . '/' . $imageName;

  if (!file_exists($fullDir)) {
    if(!is_dir($partialDir . $folder))
      mkdir($partialDir . $folder);

    if (strlen($imageUrl) > 5) {
      if(preg_match('#http#', $complUrl . $imageUrl))
        $image = WideImage::load($complUrl . $imageUrl);
      else
        $image = WideImage::load(__DIR__ . '/../../../' . $complUrl . $imageUrl);
      $newImage = $image->resize($width, $height);
      $newImage->saveToFile($fullDir);
    }
  }

  return SITE_URL . 'lib/Resources/Twig/Cache/_Images/' . $folder . '/' . $imageName;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function getGravatar( $email, $s = 80, $d = 'mm', $img = false, $atts = array() ) {
  $url = 'http://www.gravatar.com/avatar/';
  $url .= md5( strtolower( trim( $email ) ) );
  $url .= "?s=$s&d=$d";
  if ( $img ) {
    $url = '<img src="' . $url . '"';
    foreach ( $atts as $key => $val )
      $url .= ' ' . $key . '="' . $val . '"';
    $url .= ' />';
  }
  return $url;
}

?>
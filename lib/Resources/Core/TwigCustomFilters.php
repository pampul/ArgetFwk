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
            $image = WideImage::load(__DIR__ . '/../../../' . $complUrl . $imageUrl);
            $newImage = $image->resize($width, $height);
            $newImage->saveToFile($fullDir);
        }
    }

    return SITE_URL . 'lib/Resources/Twig/Cache/_Images/' . $folder . '/' . $imageName;
}

?>
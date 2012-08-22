<?php

/**
 * 
 * Classe regroupant des fonctions essentielles et pratiques
 */
class FwkUtils {

    /**
     * Retourne un "s" si count > 1
     *
     * @param int $count
     * @param boolean $Upper
     * @return String
     */
    public static function getS($count, $Upper = false) {
        if ($Upper) {
            return ($count >= 2) ? 'S' : '';
        } else {
            return ($count >= 2) ? 's' : '';
        }
    }

    /**
     * Formate un N° de telephone : retourne un nombre de 10 chiffres
     *
     * @param String $number
     * @param String $separateur
     * @return String
     */
    public static function FormatTelephone($number, $separateur = ' ') {
        if (isset($number)) {
            $number = str_replace(' ', '', $number);
            $number = str_replace('-', '', $number);
            $number = str_replace('.', '', $number);
            $number = str_replace('/', '', $number);

            if (substr($number, 0, 3) != '082'
                    && substr($number, 0, 3) != '080') {
                $num = substr($number, 0, 2) . $separateur;
                $num.= substr($number, 2, 2) . $separateur;
                $num.= substr($number, 4, 2) . $separateur;
                $num.= substr($number, 6, 2) . $separateur;
                $num.= substr($number, 8, 2);
            } else {
                $num = substr($number, 0, 4) . $separateur;
                $num .= substr($number, 4, 3) . $separateur;
                $num .= substr($number, 7, 3);
            }
        } else {
            $num = $number;
        }

        return trim($num);
    }

    /**
     * Suppression d'accents
     *
     * @param string $str
     * @return string
     */
    public static function removeAccent($str) {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        return preg_replace('/[^A-Za-z0-9_-\s]/', '', $str);
    }

    public static function getDirList($directory, $deep = false) {
        //create an array to hold directory list
        $results = array();

        //create a handler for the directory
        $handler = opendir($directory);

        //keep going until all files in directory have been read
        while (false !== ($file = readdir($handler))) {

            $path = realpath($directory . '/' . $file);
            $notAllowed = array('.', '..', 'CVS');

            //if $file isn't this directory or its parent, 
            //add it to the results array
            if (!in_array($file, $notAllowed)) {

                if ($deep && is_dir($path)) {
                    $results[$file] = self::getDirList($path, $deep);
                } else {
                    $results[$file] = null;
                }
            }
        }

        //tidy up: close the handler
        closedir($handler);

        //done!
        return $results;
    }

    /**
     * retourne l'extention d'un fichier
     */
    public static function getExtension($file) {
        $tab = explode('.', $file);
        return array_pop($tab);
    }

    /**
     * 
     * Looks for a key in a multi-dimensional array
     * 
     * example: $myarray = array(
     *     'Personne'        => array(0 => 'RIB', 1 => 'Ville'),
     *     'Animal'        => array(2 => 'Puce', 3 => 'Date de naissance exacte', 4 => 'Date de naissance approximative'),
     *     'Contrat'        => array(5 => 'N° Police'),
     *     6 => 'Bidon'
     * );
     * Shortcut::multiDimensionalArrayKeyExists(intval('1'), $myarray) returns true
     * 
     * @internal 05/08/2011 - Damien - Création
     * @author Damien
     * @param mixed $key
     * @param array $searchArray
     * @return boolean true if found, false if not found
     */
    public static function multiDimensionalArrayKeyExists($key, $searchArray) {
        foreach ($searchArray as $curKey => $curValue) {
            if ($key === $curKey) {
                return true;
            }
            if (is_array($curValue)) {
                if (self::multiDimensionalArrayKeyExists($key, $curValue))
                    return true;
            }
        }
        return false;
    }

    /**
     * 
     * Returns value for a key in a multi-dimensional array
     * 
     * example: $myarray = array(
     *     'Personne'        => array(0 => 'RIB', 1 => 'Ville'),
     *     'Animal'        => array(2 => 'Puce', 3 => 'Date de naissance exacte', 4 => 'Date de naissance approximative'),
     *     'Contrat'        => array(5 => 'N° Police'),
     *     6 => 'Bidon'
     * );
     * Shortcut::multiDimensionalArrayGetValue(intval('2'), $myarray) returns 'Puce'
     * 
     * @internal 05/08/2011 - Damien - Création
     * @author Damien
     * @param mixed $key
     * @param array $searchArray
     * @return mixed FALSE if key not found, or value if found
     */
    public static function multiDimensionalArrayGetValue($key, $searchArray) {
        foreach ($searchArray as $curKey => $curValue) {
            if ($key === $curKey) {
                return $curValue;
            }
            if (is_array($curValue)) {
                $return = self::multiDimensionalArrayGetValue($key, $curValue);
                if ($return !== false)
                    return $return;
            }
        }

        return false;
    }

}

?>

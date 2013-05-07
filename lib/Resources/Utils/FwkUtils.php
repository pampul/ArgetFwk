<?php

/**
 * Classe regroupant des fonctions essentielles et pratiques
 *
 * @author f.mithieux
 */
class FwkUtils
{

  /**
   * Valeurs non-autorisées pour lire un dossier
   *
   * @var array $notAllowed
   */
  private static $notAllowed = array('.', '..', 'CVS');

  /**
   * Retourne un "s" si count > 1
   *
   * @param int     $count
   * @param boolean $Upper
   * @return String
   */
  public static function getS($count, $Upper = false)
  {
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
  public static function FormatTelephone($number, $separateur = ' ')
  {
    if (isset($number)) {
      $number = str_replace(' ', '', $number);
      $number = str_replace('-', '', $number);
      $number = str_replace('.', '', $number);
      $number = str_replace('/', '', $number);

      if (substr($number, 0, 3) != '082' && substr($number, 0, 3) != '080') {
        $num = substr($number, 0, 2) . $separateur;
        $num .= substr($number, 2, 2) . $separateur;
        $num .= substr($number, 4, 2) . $separateur;
        $num .= substr($number, 6, 2) . $separateur;
        $num .= substr($number, 8, 2);
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
  public static function removeAccent($str)
  {
    $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    return preg_replace('/[^A-Za-z0-9_-\s]/', '', $str);
  }

  /**
   * Suppression d'accents plus safe
   *
   * @param string $str
   * @return string
   */
  public static function removeAccentsLonger($str, $charset = 'utf-8')
  {
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

    return $str;
  }

  /**
   * Retourne une url propre
   *
   * @param string $str
   * @return string
   */
  public static function urlAlize($str, $charset = 'utf-8')
  {

    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    $str = preg_replace('#([^A-Za-z0-9]+)([\.]*)#', '-', $str);
    $str = strtolower($str);

    return $str;
  }

  /**
   * Retourne une url propre
   *
   * @param string $str
   * @return string
   */
  public static function urlAlizeAllowSlash($str, $charset = 'utf-8')
  {

    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    $str = preg_replace('/([^A-Za-z0-9\/]+)/', '-', $str);
    $str = strtolower($str);

    return $str;
  }

  /**
   * Lis un dossier et retourne dans un tableau ses fichiers / sous-dossiers etc...
   *
   * @param string  $directory - Chemin à inspecter
   * @param boolean $deep      - Lire les sous dossiers ?
   * @param boolean $notArray  - Pas de tableau associatif ?
   * @return array
   */
  public static function getDirList($directory, $deep = false, $notArray = true)
  {
    $results = array();
    $handler = dir($directory);
    while ($file = $handler->read()) {

      $path = realpath($directory . '/' . $file);

      if (!in_array($file, self::$notAllowed)) {

        if ($deep && is_dir($path)) {
          $results[$file] = self::getDirList($path, $deep);
        } elseif ($notArray) {
          $results[] = $file;
        } else {
          $results[$file] = null;
        }
      }
    }
    $handler->close();
    return $results;
  }

  /**
   * Version avancée du getDirList
   * Lis un dossier et retourne dans un tableau ses fichiers / sous-dossiers etc...
   *
   * @param string  $directory  - Chemin à inspecter
   * @param boolean $deep       - Lire les sous dossiers ?
   * @param boolean $notArray   - Pas de tableau associatif ?
   * @param string  $pregFile   - Recherche par exepression régulière dans le nom de fichier // '' si pas de recherche
   * @param integer $nbrResults - Nombre de résultats à trouver // 0 si illimité
   * @return array
   */
  public static function getDirListComplex($directory, $deep = false, $notArray = true, $pregFile = '', $nbrResults = 0)
  {
    $results = array();
    $handler = dir($directory);
    while ($file = $handler->read()) {

      $path = realpath($directory . '/' . $file);

      if (!in_array($file, self::$notAllowed) && preg_match('#' . $pregFile . '#', $file)) {

        if ($deep && is_dir($path)) {
          $results[$file] = self::getDirList($path, $deep);
          if ($nbrResults != 0 && sizeof($results) >= $nbrResults)
            break;
        } elseif ($notArray) {
          $results[] = $file;
          if ($nbrResults != 0 && sizeof($results) >= $nbrResults)
            break;
        } else {
          $results[$file] = null;
          if ($nbrResults != 0 && sizeof($results) >= $nbrResults)
            break;
        }
      }
    }
    $handler->close();
    return $results;
  }

  /**
   * retourne l'extention d'un fichier
   */
  public static function getExtension($file)
  {
    $tab = explode('.', $file);
    return array_pop($tab);
  }

  /**
   * retourne le nom d'un fichier
   *
   * @param string $file
   */
  public static function getNameFile($file)
  {
    $tab = explode('.', $file);
    array_pop($tab);
    $str = implode('.', $tab);
    return $str;
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
   * @author   Damien
   * @param mixed $key
   * @param array $searchArray
   * @return boolean true if found, false if not found
   */
  public static function multiDimensionalArrayKeyExists($key, $searchArray)
  {
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
   * @author   Damien
   * @param mixed $key
   * @param array $searchArray
   * @return mixed FALSE if key not found, or value if found
   */
  public static function multiDimensionalArrayGetValue($key, $searchArray)
  {
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

  /**
   * Crée un Path à partir d'une racine
   * possibilité de dynamiser le path avec de mois / Annee / date (aOptions)
   *
   * @param String $strRootPath
   * @param String $strSubPath
   * @param array  $tabOptions
   * @return String
   */
  public static function CreatePath($strRootPath, $strSubPath, $tabOptions = '')
  {
    if (empty($strRootPath)) {

      $path = getenv('TEMP');
    } else {

      if (!is_dir($strRootPath)) {
        echo 'Le répertoire ' . $strRootPath . ' n\'existe pas';
        return false;
      }

      if (!empty($strSubPath)) {
        $tmtp = (isset($tabOptions['date']) ? DateUtils::letterToTimeStamp($tabOptions['date']) : time());
        if (is_array($tabOptions)) {
          foreach ($tabOptions as $Tag => $value) {
            $strSubPath = str_replace('[' . $Tag . ']', $value, $strSubPath);
          }
        }

        $strSubPath = str_replace('[MOIS]', DateUtils::getNumMonthToFrenchLetter(date('n', $tmtp)), $strSubPath);
        $strSubPath = str_replace('[ANNEE]', date('Y', $tmtp), $strSubPath);
        $strSubPath = str_replace('[DATE]', date('Y-m-d', $tmtp), $strSubPath);
        $strSubPath = str_replace('[JOUR]', DateUtils::getStrJourSemaine(date('Y-m-d', $tmtp)) . ' ' . date('j', $tmtp), $strSubPath);
        $strSubPath = preg_replace('/[^A-Za-z0-9_-\s\\\.]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $strSubPath));

        if (!isset($tabOptions['createDirs']) || (isset($tabOptions['createDirs']) && $tabOptions['createDirs'])) {
          self::Mkpath($strRootPath, $strSubPath);
        }

        $path = $strRootPath . $strSubPath;
      } else {
        $path = $strRootPath;
      }
    }
    return $path;
  }

  /**
   * Here's a script to create a recursive directory path on NAS or another server using Common Internet File System (CIFS)
   * e.g. you already have a directory on a server
   * \\server/share/dir1/dir2
   * you want to create some more directories
   * \\server/share/dir1/dir2/dir3/dir4
   *
   * @param unknown_type $strRootPath
   * @param unknown_type $path
   * @return none
   */
  public static function Mkpath($strRootPath, $strSubPath)
  {
    $strSubPath = preg_replace('/(\/){2,}|(\\\){1,}/', '/', $strSubPath);
    $tabDirs    = explode("/", $strSubPath);
    $strPath    = $strRootPath;

    $i = 0;
    foreach ($tabDirs as $element) {
      $strPath .= $element . "/";
      if (!is_dir($strPath)) {
        if (!mkdir($strPath)) {
          echo 'Erreur à : ' . $strPath;
          return false;
        }
      }
      $i++;
    }

    return true;
  }

  /**
   * Retourne le nombre en francais
   *
   * @param int    $number
   * @param String $genre
   * @return String
   */
  public static function NumberToLetter($number, $genre = 'M')
  {
    $aLetter['M']    = array(1  => 'premier',
                             2  => 'deuxième',
                             3  => 'troisième',
                             4  => 'quatrième',
                             5  => 'cinquième',
                             6  => 'sixième',
                             7  => 'septième',
                             8  => 'huitième',
                             9  => 'neuivème',
                             10 => 'dixième');
    $aLetter['F']    = $aLetter['M'];
    $aLetter['F'][1] = 'première';

    if (isset($aLetter[$genre][$number])) {
      return $aLetter[$genre][$number];
    }
  }

  /**
   * Parse du texte pour le rendre valide en URL
   *
   * @param String $string
   * @return String
   */
  public static function ParseStringToUrl($string)
  {

    $string = self::removeAccentsLonger($string);
    $string = preg_replace('/([^.A-Za-z0-9]+)/', '-', $string);
    $string = strtolower($string);

    return $string;
  }

  /**
   * Repousse les limites du serveur en ram et execution time
   *
   * @param int     $memory_limit        (Mo) - Mémoire max en mo
   * @param boolean $bUnsetExecutionTime - Annule le temps d'execution
   * @param int     $dureeHeure          - Si unset true : temps max à définir en heure
   */
  public static function DebrideServeur($memory_limit, $bUnsetExecutionTime = true, $dureeHeure = 1)
  {
    if ($bUnsetExecutionTime) {
      $Met = 3600 * $dureeHeure;
      ini_set('max_execution_time', $Met);
    }
    if (!is_null($memory_limit)) {
      $memory_limit = $memory_limit . 'M';
      //	ini_set('memory_limit', $memory_limit);
    }
  }

  /**
   * Dé-Camel-Case une chaine
   * ex : IAmTheBest => i am the best
   *
   * @param String $String
   * @return String
   */
  public static function Uncamelize($string)
  {
    $string = preg_replace("/([A-Z])/", "_$1", $string);
    return sfInflector::humanize($string);
  }

  /**
   * Camel-Case une chaine
   * ex : je veux du café => JeVeuxDuCafe
   *
   * @param String $String
   * @return String
   */
  public static function Camelize($string)
  {
    $string = self::removeAccent($string);
    $string = strtolower($string);
    $string = preg_replace('#[\s-]+#', '_', $string);
    return self::SfCamelize($string);
    return self::humanize($string);
  }

  /**
   * Returns a camelized string from a lower case and underscored string by replaceing slash with
   * double-colol and upper-casing each letter preceded by an underscore.
   *
   * @param string String to camelize.
   *
   * @return string Camelized string.
   */
  public static function SfCamelize($lower_case_and_underscored_word)
  {
    $tmp = $lower_case_and_underscored_word;
    $tmp = self::pregtr($tmp, array('#/(.?)#e'    => "'::'.strtoupper('\\1')",
                                    '/(^|_)(.)/e' => "strtoupper('\\2')"));

    return $tmp;
  }

  /**
   * Returns a human-readable string from a lower case and underscored word by replacing underscores
   * with a space, and by upper-casing the initial characters.
   *
   * @param string String to make more readable.
   *
   * @return string Human-readable string.
   */
  public static function humanize($lower_case_and_underscored_word)
  {
    if (substr($lower_case_and_underscored_word, -3) === '_id')
      $lower_case_and_underscored_word = substr($lower_case_and_underscored_word, 0, -3);
    return ucfirst(str_replace('_', ' ', $lower_case_and_underscored_word));
  }

  /**
   * Returns subject replaced with regular expression matchs
   *
   * @param mixed subject to search
   * @param array array of search => replace pairs
   */
  public static function pregtr($search, $replacePairs)
  {
    return preg_replace(array_keys($replacePairs), array_values($replacePairs), $search);
  }

  /**
   * Equivalent du json_encode, mais avec possibilité de ne pas utiliser de quotes (json invalide que json_encode ne peut pas faire)
   *
   * @param $tab
   * @param $bWithNumericalKeys
   * @param $bWithoutQuotes
   * @return string
   */
  public static function tabToJson($tab, $bWithNumericalKeys = false, $bWithoutQuotes = false)
  {
    if ($bWithoutQuotes) {

      $str = '{';
      foreach ($tab as $key => $value) {

        $strKey = (string)$key . ':';
        if (!$bWithNumericalKeys && is_numeric($key)) {
          $strKey = '';
        }

        if (is_array($value)) {
          $str .= $strKey . self::tabToJson($value, $bWithNumericalKeys, $bWithoutQuotes) . ',';
        } else {
          $str .= $strKey . (string)$value . ',';
        }
      }

      $str = trim($str, ',');
      $str .= '}';
      return $str;
    } else {

      return json_encode($tab);
    }
  }

  /**
   * Traitement inverse de la fonction ci-dessus
   *
   * @param string $str
   * @return array
   */
  public static function jsonToTab($str)
  {
    $str = str_replace(array('[', ']'), array('{', '}'), trim($str));

    $start = strpos($str, '{');
    $end   = strrpos($str, '}');

    $str = ($start === 0 ? substr($str, $start + 1, $end - 1) : $str);
    $str = str_replace(array('\'', '"'), '', $str);

    $tabTemp = explode(',', $str);
    $tab     = array();

    unset($str, $start, $end);

    $nbStarts = 0;
    $nbEnds   = 0;
    $w        = '';

    foreach ($tabTemp as $v) {
      $start = strpos($v, '{');
      $end   = strrpos($v, '}');

      if ($start !== false)
        $nbStarts++;
      if ($end !== false)
        $nbEnds++;

      $w .= $v . ',';

      if ($nbStarts === $nbEnds) {
        if ($nbStarts === 0 && $nbEnds === 0) {
          $tab[] = trim($v);
        } else {
          $tab[] = trim($w, ',');
        }
        $nbStarts = 0;
        $nbEnds   = 0;
        $w        = '';
      }
    }

    unset($tabTemp, $nbStarts, $nbEnds, $v, $w);

    $tabFinal = array();

    foreach ($tab as $x) {
      if (strstr($x, ':')) {
        $k = substr($x, 0, strpos($x, ':'));
        $y = substr($x, strpos($x, ':') + 1);
      } else {
        $k = null;
        $y = $x;
      }
      if (strstr($y, '{')) {
        $y = self::jsonToTab($y);
      }
      if (is_null($k)) {
        $tabFinal[] = $y;
      } else {
        $tabFinal[$k] = $y;
      }
    }

    return $tabFinal;
  }

  /**
   * Applati un tableau multidimmensionnel en un seul tableau
   *
   * /!\ => perte des clef obligatoire !
   *
   * ex : $a = array('t' => array('0' => 1,
   *                  '1' => 2),
   *           'u' => array('0' => 1,
   *                    '1' => 3),
   *           'v' => 5);
   * return array(1, 2, 3, 5);
   */
  public static function flatten($array, $deep)
  {

    $deep--;
    $aTemp = array();
    foreach ($array as $mixedItem) {
      if (is_array($mixedItem)) {
        if ($deep >= 1) {
          $aSub  = self::flatten($mixedItem, $deep);
          $aTemp = array_merge($aSub, $aTemp);
        }
      } else {
        $aTemp[] = $mixedItem;
      }
    }
    return $aTemp;
  }

  /**
   * Raccourcis sur test si chaine est vide
   *
   * @param string $str
   * @return bool
   */
  public static function estVide($v)
  {
    if (is_null($v) || (is_string($v) && trim($v) == '') || (is_array($v) && !count($v)))
      return true;
    else
      return false;
  }

  /**
   * Vérifie une directory et la créé si non-existante
   *
   * @param string  $directory - Directory de base (ex: logs/)
   * @param string  $subDirectory
   * @param boolean $createIfNotExists
   * @return string
   */
  public static function getDir($directory, $subDirectory, $createIfNotExists = true)
  {

    $completeDirectory = $directory . $subDirectory . DIRECTORY_SEPARATOR;

    //on verifie l'existance du répertoire
    if (!is_dir($completeDirectory)) {
      if (!$createIfNotExists) {
        return false;
      } else {

        if (strpos($subDirectory, "/") != 0) {

          $root  = $directory;
          $aPath = explode('/', $subDirectory);

          foreach ($aPath as $subDirectory) {

            $newDir = $root . DIRECTORY_SEPARATOR . $subDirectory;

            if (!is_dir($newDir)) {
              mkdir($newDir);
            }

            $root = $newDir;
          }
        } else {
          mkdir($directory);
        }
      }
    }

    return $completeDirectory;
  }

  /**
   * Retourne la mémoire utilisée par PHP à l'instant "t"
   *
   * @return string
   */
  public static function getMemoryUsage()
  {

    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');

    $size = memory_get_usage(true);
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
  }

  /**
   * Coupe du texte qui est trop long sans abimer la chaine
   */
  public static function couperTexte($longueurMax, $texte)
  {

    $length = strlen(preg_replace("#[\n|\r|\n\r]+#", " ", strip_tags($texte)));
    $string = wordwrap(preg_replace("#[\n|\r|\n\r]+#", " ", strip_tags($texte)), $longueurMax);
    $string = substr($string, 0, strpos($string, "\n"));

    $var    = " ...";
    if ($length > $longueurMax)
      return $string . $var;
    else
      return $string;
  }

  public static function checkFileName($path, $fileName, $ext)
  {

    $completeFileName = $fileName;
    $i                = 0;
    while (file_exists($path . $fileName . '.' . $ext)) {
      $i++;
      $fileName = $completeFileName . '__' . $i;
    }
    return $fileName;
  }

  /**
   * Récupère la première image d'un texte
   *
   * @param string $texte
   * @return string
   */
  public static function getPictureFromText($texte)
  {

    if (preg_match("#<img(.*?)>#s", stripslashes($texte), $matches)) {
      preg_match('#src="((.*?)")#', $matches[0], $resultat);
      if ($resultat[0] == null) {
        preg_match("#src='((.*?)')#", $matches[0], $resultat);
        if (!preg_match("#src='/gestion/#", $resultat[0])) {
          $newSRC = str_replace("src='", "src='gestion/", $resultat[0]);
        } else {
          $newSRC = $resultat[0];
        }
      } else {
        if (!preg_match('#src="/gestion/#', $resultat[0])) {
          $newSRC = str_replace('src="', 'src="gestion/', $resultat[0]);
        } else {
          $newSRC = $resultat[0];
        }
      }
    } else {
      $newSRC = null;
    }

    if (isset($newSRC) && FwkUtils::checkhttp($newSRC))
      return $resultat[0];
    elseif (isset($newSRC))
      return $newSRC; else
      return null;
  }

  public static function checkhttp($lien)
  {

    if (!preg_match("#http://#", $lien)) {
      return "http://" . $lien;
    } else {
      return $lien;
    }
  }

  /**
   * Vérifie la validité d'une adresse email
   *
   * @param string $email
   * @return boolean
   */
  public static function isValidEmail($email)
  {
    return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
  }

  /**
   * Vérifie la validité d'un téléphone
   *
   * @param int $tel
   * @return boolean
   */
  public static function isValidTel($tel)
  {
    return preg_match("#^0[0-9]{9,9}$#", $tel);
  }

  /**
   * Vérifie la validité d'un nom/prénom
   *
   * @param string $name
   * @return boolean
   */
  public static function isValidName($name)
  {
    return preg_match("#^[-a-zA-Z0-9àâäçèéêëìíîïòóôùúûü ]{2,}$#", $name);
  }

  /**
   * Teste la validité d'une string
   *
   * @param string $str
   * @param int    $min
   * @param int    $max
   * @return boolean
   */
  public static function isValidString($str, $min = 1, $max = '')
  {
    return preg_match('#^[-a-zA-Z0-9àâäçèéêëìíîïòóôùúûü_ \'\.!?:" ]{' . $min . ',' . $max . '}$#', $str);
  }

  /**
   * Teste la validité d'un mot de passe
   *
   * @param string $password
   * @return boolean
   */
  public static function isValidPassword($password)
  {
    return preg_match("#^[a-zA-Z0-9àâäçèéêëìíîïòóôùúûü&@-]{5,15}$#", $password);
  }


}

?>

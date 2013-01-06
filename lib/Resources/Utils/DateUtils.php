<?php

/**
 * Classe permettant des calculs de dates
 */
class DateUtils {
    /**
     * Expression reguliere pour valider une date de format FR
     * @var string
     */

    const regexFr = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';

    /**
     * Expression reguliere pour valider une date de format EN
     * @var unknown_type
     */
    const regexEn = '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/';

    // Test si une date est une date, et la renvois en US si possible
    public static function tryToConvert($str) {
        if (!is_null(self::getDateFormat($str))) {
            return self::toUs($str);
        }
        return NULL;
    }

    /**
     * Retourne le nombre de jour dans un mois
     * @param $mois
     * @param $annee
     */
    public static function GetNbJoursDansUnMois($mois = null, $annee = null) {
        $mois = ($mois) ? $mois : date('m');
        $annee = ($annee) ? $annee : date('Y');

        return intval(date("t", strtotime("$annee-$mois-01")));
    }

    /**
     * Retourne la langue utilisee pour la date
     * @param $strDate
     * @return unknown_type
     */
    public static function getDateFormat($strDate) {
        //si ya l'heure indiquee, on la vire
        if (strstr(trim($strDate), ' ')) {
            list($strDate, $strTime) = explode(' ', $strDate);
        }
        //si on trouve rien avec le format fr on test le format en
        if (!preg_match(self::regexFr, $strDate, $tabMatch)) {
            //si ça donne rien non plus
            if (!preg_match(self::regexEn, $strDate, $tabMatch)) {
                return NULL;
            } else {
                return 'en';
            }
        } else {
            return 'fr';
        }
        return NULL;
    }

    public static function getDate($date, $format = 'Y-m-d') {
        $dateUs = self::tryToConvert($date);
        $tmpStp = self::letterToTimeStamp($dateUs);
        return date($format, $tmpStp);
    }

    /**
     * Retourne un tableau avec le jour, mois et l'année (et l'heure) selon son format
     * @param $strDate
     * @return array
     */
    public static function explodeDate($strDate, $bZeroInitial = true, $bCast = true) {
        if (FwkUtils::estVide($strDate)) {
            return NULL;
        }

        //si il y a l'heure indiquee, on la separe de la date a transformer
        if (strstr(trim($strDate), ' ')) {
            list($strDate, $strTime) = explode(' ', $strDate);
        }

        //eclatement de la date selon le format (regep self::regexFr et self::regexEn)
        switch (self::getDateFormat($strDate)) {
            case 'en': list($year, $month, $day) = explode('-', $strDate);
                break;
            case 'fr': list($day, $month, $year) = explode('/', $strDate);
                break;
            default: return NULL;
                break;
        }

        //resultat
        $tab = array('day' => $day, 'month' => $month, 'year' => $year);

        //facultatif, mais permet d'eviter des tests isset en plus par la suite
        $tabTime = array('hour' => 0, 'minute' => 0, 'second' => 0);

        //eclatement de l'heure
        if (isset($strTime)) {
            $tabTimeBuffer = explode(':', $strTime);
            if (count($tabTimeBuffer) >= 2) {
                //des fois, seulement l'heure et les minutes sont indique
                $tabTime['hour'] = $tabTimeBuffer[0];
                $tabTime['minute'] = $tabTimeBuffer[1];
                if (count($tabTimeBuffer) == 3) {
                    $tabTime['second'] = $tabTimeBuffer[2];
                }
            }
            unset($tabTimeBuffer);
        }

        //fusion de la date et de l'heure
        if (isset($tabTime))
            $tab = array_merge($tabTime, $tab);

        //cast sur les données : string (avec zero initial) ou int
        if ($bCast || $bZeroInitial) {
            foreach ($tab as $k => $val) {
                if ($bZeroInitial) {
                    if ($k != 'year')
                        $tab[$k] = self::ZeroInitial($val);
                    else
                        $tab[$k] = ($bCast ? (string) $val : $val);
                } else {
                    $tab[$k] = ($bCast ? (int) $val : $val);
                }
            }
        }

        return $tab;
    }

    /**
     * Conversion en US, sans se preoccuper du type d'origine
     * @param $strDate
     * @param $bWithTime
     * @return unknown_type
     */
    public static function toUs($strDate, $bWithTime = false) {
        $tab = self::explodeDate($strDate);
        $strDate = $tab['year'] . '-' . $tab['month'] . '-' . $tab['day'];
        if ($bWithTime) {
            $strDate .= ' ' . $tab['hour'] . ':' . $tab['minute'] . ':' . $tab['second'];
        }
        return $strDate;
    }

    /**
     * Conversion en FR, sans se preoccuper du type d'origine
     * @param $strDate
     * @param $bWithTime
     * @return unknown_type
     */
    public static function toFr($strDate, $bWithTime = false, $bWithoutScds = false) {
        $tab = self::explodeDate($strDate);
        $strDate = $tab['day'] . '/' . $tab['month'] . '/' . $tab['year'];
        if ($bWithTime) {
            $strDate .= ' ' . $tab['hour'] . ':' . $tab['minute'] . ($bWithoutScds ? '' : ':' . $tab['second']);
        }
        return $strDate;
    }

    /**
     * Retourne une chaine litterale selon une chaine aaaammjjhhmmss
     *
     * @param string $dateTime 
     * @param bool $isGmt
     * @return string
     */
    public static function getStrDateTime($concatDateTime, $isGmt = false, $showScds = true) {
        if (FwkUtils::estVide($concatDateTime)) {
            return '';
        } else {
            $date = substr($concatDateTime, 0, 8);
            $time = substr($concatDateTime, 8, 14);
            $mktime = mktime(
                    substr($time, 0, 2), substr($time, 2, 2), substr($time, 4, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)
            );

            if ($isGmt) {
                $gmmktime = gmmktime(
                        substr($time, 0, 2), substr($time, 2, 2), substr($time, 4, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)
                );
                return 'Le ' . date('d/m/Y', $gmmktime)
                        . ' à ' . date('H', $gmmktime) . 'h' . date('i', $gmmktime) . ($showScds ? ' et ' . date('s', $gmmktime) . 's' : '')
                        . ' (GMT : ' . date('d/m/Y', $mktime) . ' ' . date('H:i' . ($showScds ? ':s' : ''), $mktime) . ')';
            } else {
                return 'Le ' . date('d/m/Y', $mktime)
                        . ' à ' . date('H', $mktime) . 'h' . date('i', $mktime) . ($showScds ? ' et ' . date('s', $mktime) . 's' : '');
            }
            return '';
        }
    }

    /**
     * Transforme un dateTime en timeStamp
     *
     * @param date('Y-m-d H:i:s') $datetime
     * @param Boolean $bGmt
     * @return timeStamp
     */
    public static function concatToTimestamp($datetime, $bGmt = false) {
        $date = substr($datetime, 0, 8);
        $time = substr($datetime, 8, 14);
        $fct = (!$bGmt ? 'mktime' : 'gmmktime');
        return $fct(
                substr($time, 0, 2), substr($time, 2, 2), substr($time, 4, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)
        );
    }

    /**
     * Transforme un timestamp en date YmdHis
     *
     * @param timestamp $datetime
     * @param Boolean $bGmt
     * @return String
     */
    public static function timestampToConcat($timeStamp, $bGmt = false) {
        if ($bGmt) {
            $date = gmdate("YmdHis", $timeStamp);
        } else {
            $date = date("YmdHis", $timeStamp);
        }

        return $date;
    }

    /**
     * Retourne le jour de la semaine en français
     *
     * @param unknown_type $timestamp
     * @return unknown
     */
    public static function getStrJourSemaine($date = NULL) {
        if ($date == NULL) {
            $date = date('Y-m-d');
        }
        $tmpStp = self::letterToTimeStamp($date);
        $numJour = date('N', $tmpStp);

        $tabJour = array(1 => 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
        return $tabJour[$numJour];
    }

    /**
     * Retourne le jour de la semaine d'une date
     *
     * @param date('Y-m-d') $date
     * @return int
     */
    public static function getJourSemaine($date) {
        list($year, $month, $day) = explode('-', $date);
        return date('N', mktime(0, 0, 0, $month, $day, $year));
    }

    /**
     * Retourne le jour de fin du mois d'une date
     */
    public static function getJourFinMois($date = '') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        list($year, $month, $day) = explode('-', $date);
        return date('t', mktime(0, 0, 0, $month, $day, $year));
    }

    /**
     * Retourne le nom du mois en francais
     *
     * @param int $MonthNumber
     * @param Boolean $Short
     * @return String
     */
    public static function getNumMonthToFrenchLetter($MonthNumber, $Short = false) {
        $MonthTab = array('',
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre');
        return ($Short) ? substr($MonthTab[intval($MonthNumber)], 0, 4) : $MonthTab[intval($MonthNumber)];
    }

    /**
     * Retourne une date Y-m-d sous forme littérale "le d mois Y"
     *
     * @param date('Y-m-d') $usDate
     * @return String
     */
    public static function getStrDate($usDate) {
        list($year, $month, $day) = explode('-', $usDate);
        return 'le ' . $day . ' ' . self::getNumMonthToFrenchLetter((int) $month) . ' ' . $year;
    }

    /**
     * Verifie qu'une strin gsoit un date/time
     */
    public static function IsValideDateTime($str) {
        list($date, $time) = explode(' ', $str);
        if ($time == '') {
            return false;
        }
        if (!self::isDate($date)) {
            return false;
        }
        if (!self::isTime($time)) {
            return false;
        }
        return true;
    }

    /**
     * Vérifie qu'une string soit une Heure
     * @param $strdate date('H:i:s')
     */
    public static function isTime($strDate) {
        $tabTemp = explode(':', $strDate);
        if (count($tabTemp) <> 3) {
            return false;
        }
        list($h, $i, $s) = $tabTemp;
        if ($h > 23 || $i > 59 || $s > 59) {
            return false;
        }
        return true;
    }

    /**
     * Vérifie qu'une string soit une date
     *
     * @param date $date
     * @param boolean $usFormat
     * @param string $sep
     * @return boolean
     */
    public static function isDate($strDate, $bReturnDate = false) {
        $tabDate = self::explodeDate($strDate, false, false);

        if (is_array($tabDate) && isset($tabDate['year']) && isset($tabDate['month']) && isset($tabDate['day'])) {

            $day = $tabDate['day'];
            $month = $tabDate['month'];
            $year = $tabDate['year'];

            if ($year < 1900) {
                return false;
            }

            $intDay = (int) $day;
            $intMonth = (int) $month;
            $intYear = (int) $year;

            if (
                    (string) $intDay != (string) $day || (string) $intMonth != (string) $month || (string) $intYear != (string) $year
            ) {
                return false;
            }

            $bCheckedDate = @checkdate($month, $day, $year);

            return ($bReturnDate) ? $year . '-' . $month . '-' . $day : $bCheckedDate;
        }

        return false;
    }

    /**
     * Vérifie qu'une date soit au formet d/m/Y
     *
     * @param String $date
     * @return boolean
     */
    public static function isFrDate($strDate) {
        return (self::isDate($strDate) && self::getDateFormat($strDate) == 'fr');
    }

    /**
     * Vérifie qu'une date soit au formet Y-m-d
     *
     * @param String $date
     * @return boolean
     */
    public static function isUsDate($strDate) {
        return (self::isDate($strDate) && self::getDateFormat($strDate) == 'en');
    }

    /**
     * retourne le timestamp d'un date (Y-M-D) au format GMT
     *
     * @param date('Y-m-d') $letter
     * @return int timestamp
     */
    public static function letterToTimeStamp($dateLetter, $sep = '-', $gmt = TRUE) {
        list($year, $month, $day) = explode($sep, $dateLetter);
        $year = (int) $year;
        $month = (int) $month;
        $day = (int) $day;

        if ($gmt)
            return gmmktime(0, 0, 0, $month, $day, $year);
        else
            return mktime(0, 0, 0, $month, $day, $year);
    }

    /**
     * retourne le timestamp d'un date au format SQL
     *
     * @param date $sqlDate
     * @return int timestamp
     */
    public static function sqlDateToTimeStamp($sqlDate, $GMT = TRUE) {
        $annee = substr($sqlDate, 0, 4);
        $mois = substr($sqlDate, 5, 2);
        $jour = substr($sqlDate, 8, 2);
        $heure = substr($sqlDate, 11, 2);
        $minute = substr($sqlDate, 14, 2);
        $seconde = substr($sqlDate, 17, 2);

        return ($GMT) ? gmmktime($heure, $minute, $seconde, $mois, $jour, $annee) : mktime($heure, $minute, $seconde, $mois, $jour, $annee);
    }

    /**
     * Vérifie si une date est comprise dans une période
     * @param date A tester
     * @param date('Y-m-d') $dateMin
     * @param date('Y-m-d') $dateMax
     * @return boolean
     */
    public static function isBeetWeen($dateTest, $dateMin, $dateMax, $bStrict = false) {
        return ($bStrict) ?
                self::GreaterThan($dateTest, $dateMin) && self::GreaterThan($dateMax, $dateTest) :
                self::GreaterEqual($dateTest, $dateMin) && self::GreaterEqual($dateMax, $dateTest);
    }

    /**
     * Verifie que la date $dateSup soit > $dateInf
     *
     * @param date('Y-m-d') $dateSup
     * @param date('Y-m-d') $dateInf
     * @return boolean
     */
    public static function GreaterThan($dateSup, $dateInf = '') {
        return (self::dateDiff($dateSup, $dateInf) > 0);
    }

    /**
     * Verifie que la date $dateSup soit > $dateInf
     *
     * @param date('Y-m-d') $dateSup
     * @param date('Y-m-d') $dateInf
     * @return boolean
     */
    public static function GreaterEqual($dateSup, $dateInf = '') {
        if ($dateSup == '') {
            return true;
        }
        return (self::dateDiff($dateSup, $dateInf) >= 0);
    }

    /**
     * Retourne la différence entre $dateSup et $dateInf (Y-m-d)
     * calcule $dateSup - $dateInf
     * (Possibilité de passer une unité)
     * @param (Y-m-d) $date1
     * @param (Y-m-d) $date2
     * @param (Y-m-d) $Unite
     * @return integer
     */
    public static function dateDiff($dateSup, $dateInf = '', $Unite = 'd') {
        /*
         * Unite :	d  = jours
         * 		 	m  = mois
         * 			y  = années
         * 			mv = mois avec virgule
         */

        $dbg = (false/*
                  && $Unite == 'd'
                  && ($dateInf == '2011-12-31')
                 */);

        //$dbg = false;
        if ($dbg) {
            $str = "\n\n<hr />
$dateSup - $dateInf<br />";
        }

        if (empty($dateInf)) {
            $dateInf = date('Y-m-d');
        }

        if ($dateSup == $dateInf) {
            return 0;
        }

        $tmpstpDateSup = self::letterToTimeStamp($dateSup, '-', false);
        $tmpstpDateInf = self::letterToTimeStamp($dateInf, '-', false);

        if ($tmpstpDateSup == $tmpstpDateInf) {
            return 0;
        }

        if ($dbg) {
            $str .= "\n\n<hr />
$tmpstpDateSup > $tmpstpDateInf<br />";
        }

        switch ($Unite) {
            case 'y' :
            case 'm' :
            case 'mv':

                list($yearS, $monthS, $dayS) = explode('-', $dateSup);
                list($yearI, $monthI, $dayI) = explode('-', $dateInf);

                $yearS = (int) $yearS;
                $monthS = (int) $monthS;
                $dayS = (int) $dayS;
                $yearI = (int) $yearI;
                $monthI = (int) $monthI;
                $dayI = (int) $dayI;

                $yearDiff = $yearS - $yearI;

                if ($dbg) {
                    $str .= "$yearDiff = $yearS - $yearI<br />";
                }

                if (($monthS > $monthI) || ($monthS == $monthI && $yearS == $yearI)) {
                    $monthDiff = $monthS - $monthI;
                } else {
                    $monthDiff = 12 + $monthS - $monthI;
                    $yearDiff--;
                }

                if ($dbg) {
                    $str .= "$monthDiff = 12 + $monthS - $monthI; -> $yearDiff<br />";
                }

                // enlevé le +1 le 2009-10-22 pour cause 2009-08-28 - 1999-08-29 rendait 10 ans.
                //$ecartAvant = (date('t',$tmpstpDateInf) - $dayI + 1) / date('t',$tmpstpDateInf);
                $ecartAvant = (self::getJour(self::getDateFinMois($dateInf)) - $dayI + 1) / self::getJour(self::getDateFinMois($dateInf));
                $ecartApres = $dayS / date('t', $tmpstpDateSup);

                if ($dbg) {
                    $str .= '$ecartAvant / $ecartApres : ' . "$ecartAvant / $ecartApres<br />";
                }
                if ($dbg) {
                    $str .= "$dayS > $dayI<br />";
                }

                //Calcul des jours d'ecart
                if ($dayS > $dayI) {
                    $dayDiff = $dayS - $dayI + 1;
                } elseif ($dayS == $dayI) {
                    $dayDiff = 1;
                } else {

                    $nbJourMoisDateInf = date('t', $tmpstpDateInf);
                    $dayDiff = ($nbJourMoisDateInf - $dayI) + $dayS + 1;

                    if ($dbg) {
                        $str .= "$dayDiff # ($nbJourMoisDateInf - $dayI) + $dayS<br />";
                    }

                    if ($dayDiff > $nbJourMoisDateInf) {
                        $monthDiff++;
                        $dayDiff -= $nbJourMoisDateInf;
                    }
                }

                if ($dbg) {
                    $str .= "$dayDiff # $ecartAvant / $ecartApres<br />";
                }
                if ($dbg) {
                    $str .= "$monthDiff + $yearDiff * 12 + $ecartAvant + $ecartApres<br />";
                }

                $monthDiff = $monthDiff + $yearDiff * 12 + $ecartAvant + $ecartApres;

                $monthDiff--;
                if ($monthDiff <= 0) {
                    $monthDiff++;
                }

                if ($dbg) {
                    $str .= "$monthDiff<br />";
                }

                switch ($Unite) {
                    case 'y': $timedifference = floor($monthDiff / 12);
                        break;
                    case 'm': $timedifference = floor($monthDiff);
                        break;
                    case 'mv':
                        $timedifference = substr($monthDiff, 0, 5);
                    // supprimé au 2010/03/04
                    /* if ($dayS != ($dayI - 1)) {
                      $timedifference = substr($monthDiff,0,5);	break;
                      } else {
                      //$timedifference = round($monthDiff);		break;
                      } */
                }

                break;

            case 'd' :
            default :
                $timedifference = $tmpstpDateSup - $tmpstpDateInf;
                $timedifference = round($timedifference / 86400);

                // correction changement d'heure
                // supprimé au 2010/03/04
                //$correction = date("I",$tmpstpDateInf) - date("I",$tmpstpDateSup);
                //$timedifference += $correction;
                break;
        }
        if ($dbg) {
            $str .= "$dateSup => $dateInf ($Unite) : $timedifference<br />";
        }
        if ($dbg) {
            echo $str;
        }
        return $timedifference;
    }

    /**
     * Calcule la différence de mois entre deux dates (Y-M-01)
     *
     * @param date mois1
     * @param date mois2
     * @return int
     */
    public static function MonthDateDiff($date1, $date2) {
        return self::dateDiff($date1, $date2, 'm');
    }

    /**
     * Transforme une chaine (d/m/Y) en (Y-m-d)
     *
     * @param date $date
     * @return date
     */
    public static function Fr2Us($date) {
        list($day, $month, $year) = explode('/', $date);
        return $year . '-' . self::ZeroInitial($month) . '-' . self::ZeroInitial($day);
    }

    /**
     * Transforme une chaine (Y-m-d) en (d/m/Y)
     *
     * @param date (Y-m-d) $date
     * @return date (d/m/Y)
     */
    public static function Us2Fr($date, $sep = '-', $format = 'Long', $heure = false) {

        if ($date == '') {
            return '';
        }

        if (strstr(trim($date), ' ') && $heure) {
            list($date, $hour) = explode(' ', $date);
            list($hour, $minute, $seconde) = explode(':', $hour);
            $hour = $hour . ':' . $minute;
        } elseif (strstr(trim($date), ' ') && !$heure) {
            list($date, $hour) = explode(' ', $date);
        }

        list($year, $month, $day) = explode($sep, $date);

        if ($format != 'Long') {
            $lgth = strlen($year);
            $year = substr($year, $lgth - 2, $lgth);
        }

        return $day . $sep . $month . $sep . $year . ($heure && $hour ? ' ' . $hour : '');
    }

    /**
     * Décompresse une date Ymd en Y-m-d
     *
     * @param date('Ymd') $date
     * @param boolean $fr
     * @return date('Y-m-d')
     */
    public static function YMD2Us($date, $fr = false) {
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6, 2);
        return $fr ? self::Us2Fr($year . '-' . $month . '-' . $day) : $year . '-' . $month . '-' . $day;
    }

    /**
     * Transforme une chaine (Y-m-d h:m:s) en (d/m/Y h:m:s)
     *
     * @param date (Y-m-d h:m:s) $date
     * @return date (d/m/Y h:m:s)
     */
    public static function Us2FrTime($dateTime) {
        list($date, $time) = explode(' ', $dateTime);
        list($year, $month, $day) = explode('-', $date);
        return $day . '/' . $month . '/' . $year . ' ' . $time;
    }

    /**
     * Retourne la date en fr quel que soit celle en entrée
     *
     * @param date("y-m-d") $String
     * @return date("d/m/y")
     *
     */
    public static function SearchDateUs2FrIn($String) {
        $newString = preg_replace('/^(0-9{2,4})-(0-9{1,2})-(0-9{1,2})$/', '\\3/\\2/\\1', $String);
        if ($newString == $String) {
            return $newString;
        } else {
            return self::SearchDateUs2FrIn($newString);
        }
    }

    /**
     * Retourne la date (Y-M-D) du 1ier jour du mois suivant
     *
     * @param date $date
     * @return date (Y-M-D)
     */
    public static function getNextmonth($date = null, $sep = '-') {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }
        list($year, $month) = explode($sep, $date);
        $month++;
        if ($month == 13) {
            $month = 1;
            $year++;
        }
        if ($month < 10) {
            $month = '0' . $month;
        }
        return $year . $sep . $month . $sep . '01';
    }

    /**
     * Retourne la date du debut du mois de la date demandée
     * Tout en Y-M-D
     * @param string $date
     * return string date
     */
    public static function getDateDebutMois($date = '', $sep = '-') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        list($year, $month, $day) = explode($sep, $date);
        return $year . $sep . $month . $sep . '01';
    }

    /**
     * Retourne le lundi de la semaine en cours
     *
     * @param date('Y-m-d') $date
     * @return date('Y-m-d')
     */
    public static function getDateDebutSemaine($date = '') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        list($year, $month, $day) = explode('-', $date);
        $num_day = date('w', mktime(0, 0, 0, $month, $day, $year));
        $premier_jour = mktime(0, 0, 0, $month, $day - (!$num_day ? 7 : $num_day) + 1, $year);
        $datedeb = date('Y-m-d', $premier_jour);
        return $datedeb;
    }

    /**
     * retourne le lundi vendredi dernier si on est lundi 
     * sinon retourne lundi 
     *
     * @param date('Y-m-d') $date
     * @return date('Y-m-d')
     */
    public static function getDateFinSemaine($date) {
        list($year, $month, $day) = explode('-', $date);
        $num_day = date('w', mktime(0, 0, 0, $month, $day, $year));
        $premierJour = mktime(0, 0, 0, $month, $day + (!$num_day ? 7 : $num_day) + 1, $year);
        $dateFin = date('Y-m-d', $premierJour);
        return self::Lendemain($dateFin);
    }

    /**
     * Retourne la date (Y-m-d) du lendemain d'une date (Y-m-d)
     *
     * @param string (Y-m-d) $date
     * @return string
     */
    public static function Lendemain($date = '', $sep = '-') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        $dateTmpStp = self::letterToTimeStamp($date);
        return date("Y-m-d", mktime(0, 0, 0, date("m", $dateTmpStp), date("d", $dateTmpStp) + 1, date("Y", $dateTmpStp)));
    }

    public static function LendemainOuvrable($date = '') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        $date = self::Lendemain($date);
        list($y, $m, $d) = explode('-', $date);
        $i = 0;
        while (date('N', mktime(0, 0, 0, $m, $d, $y)) > 5 && $i < 7) {
            $date = self::Lendemain($date);
            list($y, $m, $d) = explode('-', $date);
            $i++;
        }
        return $date;
    }

    /**
     * Retourne la date (Y-m-d) de la veille d'une date (Y-m-d)
     *
     * @param string (Y-m-d) $date
     * @return string
     */
    public static function Veille($date = '', $sep = '-') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        $dateTmpStp = self::letterToTimeStamp($date);
        return date("Y-m-d", mktime(0, 0, 0, date("m", $dateTmpStp), date("d", $dateTmpStp) - 1, date("Y", $dateTmpStp)));
    }

    /**
     * Retourne le mois d'une date (Y-m-d)
     *
     * @param unknown_type $date
     * @param unknown_type $sep
     * @return unknown
     */
    public static function getMois($date, $sep = '-', $getFrStr = false) {
        list($year, $month, $day) = explode($sep, $date);
        if (!$getFrStr)
            return $month;
        else
            return self::getNumMonthToFrenchLetter((int) $month);
    }

    /**
     * Retourne le mois d'une date (Y-m-d)
     *
     * @param unknown_type $date
     * @param unknown_type $sep
     * @return unknown
     */
    public static function getNumeroSemaine($date, $sep = '-') {
        return date('W', self::letterToTimeStamp($date));
    }

    /**
     * Retourne le jour d'une date
     *
     * @param date('Y-m-d') $date
     * @param string $sep
     * @return string
     */
    public static function getJour($date, $sep = '-') {
        list($year, $month, $day) = explode($sep, $date);
        return $day;
    }

    /**
     * Retourne l'année d'une date
     *
     * @param date('Y-m-d') $date
     * @param string $sep
     * @return string
     */
    public static function getAnnee($date, $sep = '-') {
        list($year, $month, $day) = explode($sep, $date);
        return $year;
    }

    /**
     * Retourne une date en francais avec le mois en lettre
     * 1950-01-02 => 2 février 1950
     * 1950-01-02 15:16 => 2 février 1950 à 15h16
     */
    public static function getDateText($date, $sep = '-', $giveHour = true, $giveSec = false) {
        $jour = self::getJour($date, $sep);
        $jour = intval($jour);
        if ($jour == 1) {
            $jour .= 'er';
        }
        $mois = self::getMois($date, $sep, true);
        $annee = self::getAnnee($date, $sep);

        $str = $jour . ' ' . $mois . ' ' . $annee;

        if ($giveHour) {
            $arrayTime = explode(':', substr($date, 11));
            $hour = $arrayTime[0];
            $min = $arrayTime[1];
            $str .= ' à ' . $hour . 'h' . $min;
            if ($giveSec)
                $str .= ' et ' . $arrayTime[2] . 's';
        }

        return $str;
    }

    /**
     * Retroune la date de fin du mois
     *
     * @param unknown_type $date
     * @param unknown_type $sep
     * @return unknown
     */
    public static function getDateFinMois($date = '', $sep = '-') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        list($year, $month, $day) = explode($sep, $date);
        $moisTimeStamp = self::letterToTimeStamp($date);
        return $year . $sep . $month . $sep . date('t', $moisTimeStamp);
    }

    /**
     * Cherche clé de la plus petite date (Y-m-d) d'un tableau associatif
     *
     * @param array tabDate
     * @return string
     */
    public static function getIntituleNextDate($tabDate) {
        foreach ($tabDate as $intitule => $date) {
            $tabDate[$intitule] = self::letterToTimeStamp($date);
        }
        $prochaine = min($tabDate);
        foreach ($tabDate as $intitule => $date) {
            $tabDate[$intitule] = date('Y-m-d', $date);
        }
        return array_search(date('Y-m-d', $prochaine), $tabDate);
    }

    /**
     * La fonction renvoie la veille de la date initial au même format.
     * @param    date('Y-m-d') $dateInitiale    
     * @return   date('Y-m-d')
     */
    public static function getDateVeille($date = '') {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        $annee = substr($date, 0, 4);
        $mois = substr($date, 5, 2);
        $jour = substr($date, 8, 2);

        return date('Y-m-d', mktime(0, 0, 0, $mois, $jour - 1, $annee));
    }

    /**
     * Permet de retourner une date ou l'heure suivant si la $sqldate est la date du jour
     * @param    string $sqlDate    
     * @return   string
     */
    public static function getDateOrHourBySQLDate($sqlDate) {
        $dateDuJour = date('Y') . '-' . date('m') . '-' . date('d');
        $jourSqlDate = substr($sqlDate, 0, 10);
        if ($dateDuJour == $jourSqlDate) {
            //Si la date fournie est la date du jour on retourne l'heure
            return substr($sqlDate, 11, 8);
        } else {
            //On retourne la date
            return self::Us2Fr(substr($sqlDate, 0, 10));
        }
    }

    /**
     * Transforme une date du format Atom (flux Xml) en dateTime
     *
     * @param date $dateAtom
     * @return Date('Y-m-d H:i:d')
     */
    public static function AtomToFr($dateAtom) {
        $dateAtom = str_replace('Z', '', $dateAtom);
        list($date, $time) = explode('T', $dateAtom);
        return self::Us2FR($date) . ' ' . substr($time, 0, 5);
    }

    public static function RssDateToFr($dateAtom) {
        $dateAtom = str_replace('Z', '', $dateAtom);
        list($date, $time) = explode('T', $dateAtom);
        return self::Us2FR($date) . ' ' . substr($time, 0, 5);
    }

    /**
     * Retourne la date ("Y-m-d") d'un datetime sql
     *
     * @param datetime $sqlDate
     * @return date("y-m-d")
     */
    public static function getDateNotHourBySQLDate($sqlDate, $FR = false) {
        $date = substr($sqlDate, 0, 10);
        return ($FR) ? self::Us2FrTime($date) : $date;
    }

    /**
     * Retourne l'heure
     *
     * @param datetime $sqlDate
     * @return date("H:i:s")
     */
    public static function getHourBySQLDate($sqlDate, $withSecondes = FALSE, $GMT = FALSE) {
        if (!$GMT) {
            if ($withSecondes == FALSE)
                return substr($sqlDate, 11, 5);
            else
                return substr($sqlDate, 11, 8);
        }
        else {
            $tmpstp = self::sqlDateToTimeStamp($sqlDate, TRUE);
            if ($withSecondes == FALSE)
                return gmdate("H", $tmpstp) . ":" . gmdate("i", $tmpstp);
            else
                return gmdate("H", $tmpstp) + GMT_FRANCE . ":" . gmdate("i", $tmpstp) . ":" . gmdate("s", $tmpstp);
        }
    }

    /**
     * Retourne la prochaine date Anniversaire d'une date sur l'année spécifiée par dateActu
     *
     * @param (Y-m-d) $dateNaiss
     * @param (Y-m-d) $annee
     * 
     * retourne (Y-m-d) date 
     */
    public static function getDateAnniversaire($dateNaiss, $dateActu = '') {
        if ($dateActu == '') {
            $dateActu = date('Y-m-d');
        }

        list($yearNaiss, $monthNaiss, $dayNaiss) = explode('-', $dateNaiss);

        $Diff = self::dateDiff($dateActu, $dateNaiss, 'y');

        $tmpStp = self::letterToTimeStamp($dateNaiss);

        $yearActu = date('Y', $tmpStp);

        $yearActu+=$Diff + 1;

        return $yearActu . '-' . $monthNaiss . '-' . $dayNaiss;
    }

    /**
     * Retourne la date anniversaire à 6 mois de la date donnée
     *
     * @param date("y-m-d") $dateNaiss
     * @return date("y-m-d")
     */
    public static function getDateSixMois($dateNaiss) {
        list($year, $month, $day) = explode($sep, $date);
        $month += 6;
        if ($month > 12) {
            $month = 12 - $month;
            $year++;
        }
        if ($month < 10) {
            $month = '0' . $month;
        }
        return self::getDateVeille($year . '-' . $month . '-' . $day);
    }

    /**
     * Retourne la date anniversaire à 6 mois de la date donnée
     *
     * @param date("y-m-d") $dateNaiss
     * @return date("y-m-d")
     */
    public static function getDateSixMois2($dateNaiss, $sep = '-') {
        list($year, $month, $day) = explode($sep, $dateNaiss);
        $month += 6;
        if ($month > 12) {
            $month = 12 - $month;
            $year++;
        }
        if ($month < 10) {
            $month = '0' . $month;
        }
        return self::getDateVeille($year . '-' . $month . '-' . $day);
    }

    /**
     * Ajout un delai en jour à une date
     * mv = mois avec virgule
     * mp = mois pleins
     * @param date("Y-m-d") $date
     * @param Int $delai
     * @param String (j m y mv) $interval
     * @return date("Y-m-d")
     */
    public static function DateAdd($date, $delai, $interval = 'j') {
        switch ($interval) {
            case 'j' :
                $tmpstpDate = DateUtils::letterToTimeStamp($date);
                $nbScAAjouter = 86400 * $delai + 3600;
                $tmpstpDate = Math::ajouter((int) $tmpstpDate, (int) $nbScAAjouter, 0);
                $returnVal = date('Y-m-d', $tmpstpDate);
                break;
            case 'mv':
                if (preg_match('/\./', $delai)) {
                    list($delai, $days) = explode('.', $delai);
                }
            case 'mp' :
            case 'm' :
                list($year, $month, $day) = explode('-', $date);

                // vient de mv
                if (isset($days)) {
                    $nbJoursMois = date('t', self::letterToTimeStamp($date));

                    //if ($dbg) $str .= $nbJoursMois.' / '.$days.' : '.$day.'<br />';
                    $pc = '0.' . $days;
                    $day += round($pc * $nbJoursMois);
                    //if ($dbg) $str .= $day.'<br />';
                    if ($day > $nbJoursMois) {
                        $day = $day - $nbJoursMois;
                        $month++;
                    }
                    if ($day < 10) {
                        $day = '0' . $day;
                    }
                }

                $month += $delai;

                while ($month > 12) {
                    $month = $month - 12;
                    $year++;
                }
                while ($month <= 0) {
                    $month = 12 + $month;

                    // pour faire propre sur 30 juin - 6 mois = 31/12 et non 30/12
                    if ($day == 30 && date('t', mktime(0, 0, 0, $month, $day, $year)) != 30) {
                        $day = date('t', mktime(0, 0, 0, $month, $day, $year));
                    }
                    $year--;
                }

                if ($interval == 'mp') {
                    if (( $day == 30 && date('t', mktime(0, 0, 0, $month, ($day - 3), $year)) != 30
                            ) || (
                            $day == 31 && date('t', mktime(0, 0, 0, $month, ($day - 3), $year)) != 31
                            )
                    ) {
                        $day -=3; // retourne en arrière pour plus de sureté
                        $day = date('t', mktime(0, 0, 0, $month, $day, $year));
                    }
                }

                if ($month < 10) {
                    $month = '0' . $month;
                }

                $returnVal = $year . '-' . $month . '-' . $day;
                break;
            case 'y' :
                list($year, $month, $day) = explode('-', $date);
                $year += $delai;
                $returnVal = $year . '-' . $month . '-' . $day;
                break;
        }

        return $returnVal;
    }

    /**
     * Suprime un delai en jour à une date
     *
     * @param date("Y-m-d") $date
     * @param Int $delai
     * @param String (j m y mv) $interval
     * @return date("Y-m-d")
     */
    public static function DateSub($date, $delai, $interval = 'j') {
        return self::DateAdd($date, (0 - $delai), $interval);
    }

    /** Calcul un nombre de secondes
     *
     * @param Int $heure
     * @param Int $minute
     * @param Int $secondes
     * @param Int $jours
     * @param Int $semaines
     * @return timestamp $time
     */
    public static function calculNombreSecondes($heures = 0, $minutes = 0, $secondes = 0, $jours = 0, $semaines = 0) {
        $timestamp = 0;
        $timestamp+=$secondes;
        $timestamp+=$minutes * 60;
        $timestamp+=$heures * 60 * 60;
        $timestamp+=$jours * 24 * 60 * 60;
        $timestamp+=$semaines * 7 * 24 * 60 * 60;
        return $timestamp;
    }

    /**
     * Retourne un tableau de timestamp debut et fin en fonction d'un timestamp et d'une periode
     *
     * @param Int $tmpstp
     * @param Int $typePeriode
     * @return Array(tmpstpDebut,tmpstpFin)
     */
    public static function getTmpstpDebutFinByPeriode($tmpstp, $typePeriode) {
        //Initialisation des variables
        $jour = date("j", $tmpstp);
        $mois = date("n", $tmpstp);
        $annee = date("Y", $tmpstp);
        $jourDebut = $jour;
        $moisDebut = $mois;
        $anneeDebut = $annee;
        $jourFin = $jour;
        $moisFin = $mois;
        $anneeFin = $annee;

        if ($typePeriode == AGENDA_PREF_PLANNING_SEMAINE) {
            //Calcul de la date de début
            $numday = date("w", $tmpstp);
            $numJour = array(0 => '7', 1 => '0', 2 => '1', 3 => '2', 4 => '3', 5 => '4', 6 => '5', 7 => '6');
            $numday = $numJour[$numday];
            $jourADeduire = $numday;
            $jourAAjouter = 6 - $numday;
            //Date de début
            if (($jour - $jourADeduire) < 1) {
                //On decalle d'un mois en arriere
                if ($mois > 1) {
                    $moisDebut--;
                } else {
                    //On décrémente d'une année
                    $moisDebut = 12;
                    $anneeDebut = $annee - 1;
                }
                $jourDebut = date("t", mktime(12, 0, 0, $moisDebut, 15, $anneeDebut)) + ($jour - $jourADeduire);
            } else {
                //On décrémente juste le jour
                $jourDebut = $jour - $jourADeduire;
            }
            //Date de fin
            if (($jour + $jourAAjouter) > date("t", $tmpstp)) {
                //On decalle d'un mois en avant
                if ($mois < 12) {
                    $moisFin++;
                } else {
                    //On Ajoute une année
                    $moisFin = 1;
                    $anneeFin = $annee + 1;
                }
                $jourFin = ($jour + $jourAAjouter) - date("t", mktime(12, 0, 0, $moisFin, 15, $anneeFin));
            } else {
                $jourFin = $jour + $jourAAjouter;
            }
        } elseif ($typePeriode == AGENDA_PREF_PLANNING_MOIS) {
            //Affichage au mois
            $jourDebut = 1;
            $jourFin = date("t", $tmpstp);
        } else {
            //Affichage a la journée
            $jour = date("d", $tmpstp);
            $mois = date("n", $tmpstp);
            $annee = date("Y", $tmpstp);
        }
        $timedebut = mktime(0, 0, 0, $moisDebut, $jourDebut, $anneeDebut);
        $timefin = mktime(23, 59, 59, $moisFin, $jourFin, $anneeFin);
        return Array("tmpstpDebut" => $timedebut, "tmpstpFin" => $timefin);
    }

    /**
     * Rajoute le zero initial à un chiffre
     *
     * @param int $dateAtom
     * @return String
     */
    public static function ZeroInitial($chiffre) {
        return str_pad(intval($chiffre), 2, '0', STR_PAD_LEFT);
    }

    public static function getAgeAt($naissance, $dateDuCalcul) {
        $annee = DateUtils::dateDiff($dateDuCalcul, $naissance, 'y');
        $mois = DateUtils::dateDiff($dateDuCalcul, $naissance, 'm');
        $jour = DateUtils::dateDiff($dateDuCalcul, $naissance, 'd');
        $age = '';

        if ($jour < 0) {
            return 'age négatif';
        }

        if ($annee >= 1) {
            $age .= $annee . ' an' . ($annee > 1 ? 's' : '');
            if ($mois > 1 && $mois % 12 != 0) {
                $age .= ' et ' . ($mois % 12) . ' mois';
            }
        } elseif ($mois >= 1 && $mois % 12 != 0) {
            $age .= ($mois % 12) . ' mois';
        } else {
            $age .= $jour . ' jour' . ($jour > 1 ? 's' : '');
        }
        return $age;
    }

    /**
     * Vérifie si l'année est bisextile
     *
     * @param int $annee
     * @return Boolean
     */
    public static function bissextile($annee) {
        return ( (is_int($annee / 4) && !is_int($annee / 100)) || is_int($annee / 400));
    }

    /**
     * Récupére la date du prochain 30/06 => à n'utiliser qu'en dernier recours
     * 				Préférer contratAssurance->getDateFinExercice();
     * @param int $offsetExercice
     * @return date (Y-M-D)
     */
    public static function getFinExercice($date = '', $offsetExercice = 0) {
        if (preg_match('/\//', $date)) {
            $date = self::Fr2Us($date);
        } else {
            if ($date == '') {
                $date = date('Y-m-d');
            }
        }
        $tmpStp = self::letterToTimeStamp($date);
        $year = (1 <= date('n', $tmpStp) && date('n', $tmpStp) <= 6) ? date('Y', $tmpStp) : date('Y', $tmpStp) + 1;
//		$str .= 'getFinExercice : date = '.$date.'  / mois = '.date('n', $tmpStp).' / '.$year.'  / offset = '.$offsetExercice.'<br />';
        //getFinExercice : date = 2008-01-16 / mois =  01                   / 2009       / offset = -1
        $year+= $offsetExercice;
        return $year . '-06-30';
    }

    /** ------------- Manipulation des h:i:s -------------------- */

    /**
     * Ajoute des secondes à un datetime
     *
     * @param int $minutes (négatif autorisés)
     * @param dateTime (YYYY-MM-DD HH:II:SS) $time
     * @return dateTime(YYYY-MM-DD HH:II:SS)
     */
    public static function addSecondeToTime($secondes, $time) {
        list($date, $time) = explode(' ', $time);
        list($h, $i, $s) = explode(':', $time);
        $s+=$secondes;
        //echo '->'."$h, $i, $s<br />";
        while ($s >= 60) {
            $s-=60;
            $i++;
            //echo '#'."$h, $i, $s<br />";
        }

        while ($s <= 0) {
            $s+=60;
            $i--;
            //echo '.'."$h, $i, $s<br />";
        }
        $s = self::ZeroInitial($s);


        // --
        while ($i >= 60) {
            $i-=60;
            $h++;
            //echo '*'."$h, $i, $s<br />";
        }

        while ($i < 0) {
            $i+=60;
            $h--;
            //echo '-'."$h, $i, $s<br />";
        }

        // --
        $i = self::ZeroInitial($i);
        while ($h >= 24) {
            $date = self::Lendemain($date);
            $h -= 24;
        }
        while ($h <= 0) {
            $date = self::Veille($date);
            $h += 24;
        }
        $h = self::ZeroInitial($h);
        return "$date $h:$i:$s";
    }

    /**
     * Ajoute des minutes à un datetime
     *
     * @param int $minutes
     * @param dateTime (YYYY-MM-DD HH:II:SS) $time
     * @return dateTime(YYYY-MM-DD HH:II:SS)
     */
    public static function addMinutesToTime($minutes, $time) {
        list($date, $time) = explode(' ', $time);
        list($h, $i, $s) = explode(':', $time);
        $i+=$minutes;

        // si minutes > 0
        while ($i >= 60) {
            $i-=60;
            $h++;
        }
        // si minutes < 0
        while ($i < 0) {
            $i+=60;
            $h--;
        }

        // si minutes > 0
        while ($i <= 0) {
            $i+=60;
            $h--;
        }
        // si minutes < 0
        while ($i > 60) {
            $i-=60;
            $h++;
        }

        $i = self::ZeroInitial($i);
        while ($h >= 24) {
            $date = self::Lendemain($date);
            $h -= 24;
        }
        while ($h <= 0) {
            $date = self::Veille($date);
            $h += 24;
        }
        $h = self::ZeroInitial($h);
        return "$date $h:$i:$s";
    }

    /**
     * Ajoute des heures à un datetime
     *
     * @param int $hours
     * @param dateTime (YYYY-MM-DD HH:II:SS) $time
     * @return dateTime(YYYY-MM-DD HH:II:SS)
     */
    public static function addHoursToTime($hours, $time = '') {

        if ($time == '') {
            $date = date('Y-m-d');
            $time = date('H:i:s');
        } else {
            list($date, $time) = explode(' ', $time);
        }

        list($h, $i, $s) = preg_split('/:/', $time);

        $resteMinute = $hours - floor($hours);

        $resteMinute = round($resteMinute * 60, 0);


        $i+=$resteMinute;
        if ($i >= 60) {
            $h++;
            $i-=60;
        }

        $h += round($hours, 0);
        while ($h >= 24) {
            $date = self::Lendemain($date);
            $h -= 24;
        }

        return "$date $h:$i:$s";
    }

    /**
     * Retourne la différence entre deux dates
     *
     * @param date('Y-m-d H:i:s') $dateHeureFin
     * @param date('Y-m-d H:i:s') $dateHeureDebut
     * @param Boolean $bToString
     * @param Boolean $inSeconde
     * @return int
     */
    public static function timeDiff($dateHeureFin, $dateHeureDebut = '', $bToString = true, $inSeconde = false) {
        if ($dateHeureDebut == '') {
            $dateHeureDebut = date('Y-m-d H:i:s');
        }
        list($date, $time) = explode(' ', $dateHeureFin);
        list($h, $i, $s) = explode(':', $time);
        list($y, $m, $d) = explode('-', $date);
        $tmpstpFin = mktime($h, $i, $s, $m, $d, $y);

        list($date, $time) = explode(' ', $dateHeureDebut);
        list($h, $i, $s) = explode(':', $time);
        list($y, $m, $d) = explode('-', $date);

        $tmpstpDeb = mktime($h, $i, $s, $m, $d, $y);

        $delai = (!$inSeconde) ? round(($tmpstpFin - $tmpstpDeb) / 60) : $tmpstpFin - $tmpstpDeb;

        //$delai = round(($tmpstpFin - $tmpstpDeb) / 60);
        if (!$bToString) {
            return $delai;
        }

        return self::Minute2String($delai);
    }

    /**
     * retourne un delai en minute en phrase (92 => 1 minute 32 secondes)
     *
     * @param int $delai
     * @return String
     */
    public static function Minute2String($delai) {

        $delai = abs($delai);
        $h = 0;
        if ($delai < 60) {
            return $delai . ' minute' . FwkUtils::getS($delai);
        } else {
            $j = 0;
            while ($delai >= 60) {

                $delai-=60;
                $h++;

                if ($h >= 24) {
                    $h -= 24;
                    $j++;
                }
            }

            $strReturn = '';
            $sep = '';
            if ($j > 0) {
                $strReturn = $j . ' jour' . FwkUtils::getS($j);
                $sep = ' ';
            }

            if ($h > 0 || ($j > 0 && $h > 0)) {
                $strReturn .= $sep . $h . ' heure' . FwkUtils::getS($h);
                $sep = ' ';
            }
            if ($delai > 0 || $strReturn == '') {
                $strReturn .= $sep . round($delai, 2) . ' minute' . FwkUtils::getS($delai);
            }

            return $strReturn;
        }
    }

    /**
     * Retourne l'horaire dateTime corrigé suivant les horaires d'ouverture
     * plageHoraire = 11:00,14:00|17:00,20:00|1,2,3,4,5
     * ou
     * plageHoraire = 09:00,17:00||1,2,3,4,5
     */
    public static function CheckisHoraireOuverture($dateTime, $plageHoraire) {

        // plage horaire
        list($matin, $soir, $jour) = explode('|', $plageHoraire);
        list($mDebut, $mFin) = explode(',', $matin);
        list($mDebutH) = explode(':', $mDebut);
        list($mFinH) = explode(':', $mFin);
        if ($soir != '') {
            list($sDebut, $sFin) = explode(',', $soir);
            list($sDebutH) = explode(':', $sDebut);
            list($sFinH) = explode(':', $sFin);
        }

        $aJTravail = explode(',', $jour);

        list($date, $time) = explode(' ', $dateTime);
        list($h, $i, $s) = explode(':', $time);

        $heure = $time;
        if ($soir != '') {
            // pendant pause midi :
            if ($h >= $mFinH && $h < $sDebutH) {
                $heure = $sDebut . ':00';
            } elseif ($h >= $sFinH || $h < $mDebutH) {
                $heure = $mDebut . ':00';
                $date = self::Lendemain($date);
            }
        } else {
            // Avant le matin
            if ($h < $mDebut) {
                $heure = $mDebut . ':00';
                // Apres fermeture
            } elseif ($h > $mFin) {
                $heure = $mDebut . ':00';
                $date = self::Lendemain($date);
            }
        }

        // Check jour ouverture
        $i = 0;
        list($y, $m, $d) = explode('-', $date);
        while (!in_array(date('N', mktime(0, 0, 0, $m, $d, $y)), $aJTravail) && $i < 7) {
            $date = self::Lendemain($date);
            list($y, $m, $d) = explode('-', $date);

            $i++;
        }

        return $date . ' ' . $heure;
    }

    /**
     * Retourne
     *
     * @param unknown_type $plageHoraire
     * @param unknown_type $bForSelectBox
     * @return unknown
     */
    public static function getPlageHoraire($plageHoraire = null, $bForSelectBox = false) {


        if (is_null($plageHoraire)) {
            $plageHoraire = '09:00,20:00||1,2,3,4,5';
        }

        list($matin, $soir) = explode('|', $plageHoraire);
        list($mDebut, $mFin) = explode(',', $matin);
        list($mDebutH) = explode(':', $mDebut);
        list($mFinH) = explode(':', $mFin);
        if ($soir != '') {
            list($sDebut, $sFin) = explode(',', $soir);
            list($sDebutH) = explode(':', $sDebut);
            list($sFinH) = explode(':', $sFin);
        } else {
            $sFinH = '';
            $sDebutH = '';
        }

        if ($bForSelectBox) {
            $mFinH--;
            if ($soir != '') {
                $sFinH--;
            }
        }

        if ($soir != '') {
            return array(0 => array($mDebutH, $mFinH), 1 => array($sDebutH, $sFinH));
        } else {
            return array(0 => array($mDebutH, $mFinH));
        }
    }

    /**
     * Verifie s'il y a un chevauchement entre un tableau de periode et une periode de référence
     * le tableau doit être du type 
     * array(id1 => array(datedebut, datefin),
     * 		 id2 => array(datedebut, datefin));
     * 
     * /!\ si id1 ou id2 = id la periode n'est pas checkée
     * les datefin peuvent être vide mais pas les datedebut
     * 
     * retourn vide si OK ou id periode en conflit si chevauchement
     * 
     * retourne l'id de la periode à checker si chevauchement trouvé
     * @param array des autres periodes $aPeriodeAChecker
     * @param date('Y-m-d') de la periode $refDateDebut
     * @param date('Y-m-d') de la periode $refDateFin
     * @param int id de la periode $NewIdPeriode
     * @return int
     * 
     * --------------------------------------------------------
     * 
     * Ci-dessous cas traités : 
     * 		--- periode de référence
     * 		... periode à checker
     * 		< infini
     * 		[ cDateDebut
     * 		] cDateFin	
     * 		{ refDateDebut
     * 		} refDateFin
     * 
     * deux cas : 
     * 	(1-5)  la periode de ref n'a pas de date de fin
     * 	 et
     *  (6-13) la periode de ref est finie
     *  										autorisé	traités
     * 			{-------------------------------<				
     * 1	[.]										O			
     * 2	[......]								N			X
     * 3			[..]							N			X
     * 4	[...................................<	N			X
     * 5			[...........................<	N			X
     * 
     * 
     *  		{------------}						
     * 6	[.]										O			
     * 7	[......]								N			X
     * 8			[..]							N			X
     * 9			[.............]					N			X
     * 10						[...]				O			
     * 11	[....................................<	N			X
     * 12			[............................<	N			X
     * 13						[................<	O			
     *
     */
    public static function CheckChevauchement($aPeriodeAChecker, $refDateDebut, $refDateFin = '', $refIdPeriode = '') {
        // la periode de reference n'a pas de date de fin, les autres doivent FINIR AVANT
        $bOthersMustOnlyBeBefore = ($refDateFin == '');

        $dbg = false;
        if ($dbg) {
            Shortcut::arrayDump($aPeriodeAChecker);
        }

        foreach ($aPeriodeAChecker as $idCheck => $aDate) {
            if ($idCheck == '') {
                $idCheck = '-1';
            }
            if ($idCheck != $refIdPeriode) { // c'est pas la même ;) => ok on check
                list($cDateDebut, $cDateFin) = $aDate;

                if ($bOthersMustOnlyBeBefore) { // infini sur periode de ref
                    if ($cDateFin == '') {
                        // deux periode ne peuvent pas avoir de date de fin infinie
                        // cas 4 / 5
                        if ($dbg) {
                            echo ' cas 4 / 5 : ' . $idCheck . ' -> ' . $cDateFin . '<br />';
                        }
                        return $idCheck;
                    }
                    if (!self::GreaterThan($refDateDebut, $cDateFin)) {
                        // la date de fin de periode à checker est aprés le début de celle de référence
                        // cas 2 / 3
                        if ($dbg) {
                            echo ' cas 2  /3 : ' . $idCheck . ' -> ' . $refDateDebut . '/' . $cDateFin . '<br />';
                        }
                        return $idCheck;
                    }
                } else { // la periode de ref est finie
                    // la periode à checker n'a pas de date de fin, la période doit finir avant
                    if ($cDateFin == '') {
                        if (!self::GreaterThan($cDateDebut, $refDateFin)) {
                            // cas 11 /12
                            if ($dbg) {
                                echo ' cas 11 / 12 : ' . $$idCheck . ' -> ' . cDateDebut . '/' . $refDateFin . '<br />';
                            }
                            return $idCheck;
                        }
                    }

                    if (self::GreaterThan($cDateFin, $refDateDebut)) {
                        // la date de fin de check est apres la date de debut de ref
                        // donc la date de fin de ref DOIT être avant date de debut de check
                        if (!self::GreaterThan($cDateDebut, $refDateFin)) {
                            // cas 7 / 8 / 9
                            if ($dbg) {
                                echo ' cas 7 / 8 / 9 : ' . $idCheck . ' -> ' . $cDateDebut . '/' . $refDateFin . '<br />';
                            }
                            return $idCheck;
                        }
                    }
                }
            }
        }
        return '';
    }

    /**
     * retourne le timestamp d'une date Microsoft Sql
     *
     * @param date(Ymd) $sqlDate
     * @param Boolean $GMT
     * @return int
     */
    public static function getTimeStampDayFromSqlDate($sqlDate, $GMT = true) {
        $annee = substr($sqlDate, 0, 4);
        $mois = substr($sqlDate, 5, 2);
        $jour = substr($sqlDate, 8, 2);
        /* $heure=substr($sqlDate,11,2);
          $minute=substr($sqlDate,14,2);
          $seconde=substr($sqlDate,17,2); */
        if ($GMT)
            return gmmktime(0, 0, 0, $mois, $jour, $annee);
        else
            return mktime(0, 0, 0, $mois, $jour, $annee);
    }

    /**
     * retourne le timestamp d'une date Microsoft Sql
     *
     * @param DateTime $objDate
     * @return int
     */
    public static function getTimeStampFromDate($objDate = null) {
        if ($objDate instanceof DateTime) {
            return $objDate->getTimestamp();
        } elseif ($objDate === null) {
            $objDate = new DateTime(date('Y-m-d'));
            return $objDate->getTimestamp();
        } else {
            $objDate = new DateTime($objDate);
            if ($objDate instanceof DateTime)
                return $objDate->getTimestamp();
            else
                return time();
        }
    }

    public static function getTimesPreviousWeek($time = "") {
        //calculs relatifs a une date
        $time = ($time != "" && is_numeric($time) ? $time : time());

        //on chope le lundi de la semaine courante
        $thisMonday = strtotime('this week monday', $time);

        //lundi precedent
        $previousMonday = strtotime('previous monday', $thisMonday);

        //dimanche suivant
        $previousSunday = strtotime('next sunday', $previousMonday);

        return array($previousMonday, $previousSunday);
    }

}

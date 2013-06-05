<?php

/**
 * Classe math : fonctions mathématiques en statiques
 */
class MathUtils {

  /**
   * TODO :: a supprimer : numberFormat realise le meme traitement
   *
   * @param $number
   * @param $strSeparateurDecimales
   * @param $strSeparateurMilliers
   * @return unknown_type
   */
  public static function moneyFormat($number, $strSeparateurDecimales = ',', $strSeparateurMilliers = '') {
    $number = str_replace(',', '.', $number);

    return number_format($number, 2, $strSeparateurDecimales, $strSeparateurMilliers);
  }

  /**
   * Operation ajouter, utilise la classe bc math de php
   *
   * @param string $a
   * @param string $b
   * @param int    $nbDecimales
   * @return mixed
   */
  public static function ajouter($a, $b, $nbDecimales = 2) {
    return bcadd($a, $b, $nbDecimales);
  }

  /**
   * Operation soustraire, utilise la classe bc math de php
   *
   * @param string $a
   * @param string $b
   * @param int    $nbDecimales
   * @return mixed
   */
  public static function soustraire($a, $b, $nbDecimales = 2) {
    return bcsub($a, $b, $nbDecimales);
  }

  /**
   * Operation multiplier, utilise la classe bc math de php
   *
   * @param string $a
   * @param string $b
   * @param int    $nbDecimales
   * @return mixed
   */
  public static function multiplier($a, $b, $nbDecimales = 2) {
    return bcmul($a, $b, $nbDecimales);
  }

  /**
   * Operation multiplier, utilise la classe bc math de php
   *
   * @param string $a
   * @param string $b
   * @param int    $nbDecimales
   * @return mixed
   */
  public static function diviser($a, $b, $nbDecimales = 2) {
    return bcdiv($a, $b, $nbDecimales);
  }

  /**
   * Calcule le montant HT (utilise essentiellement pour les pdf)
   *
   * @param string $montantTtc
   * @param string $tauxTva
   * @return mixed
   */
  public static function calculerMontantHt($montantTtc, $tauxTva = 19.6, $nbDecimales = 2) {
    // Attention : 1 + (19.6 * 100) est sur 4 décimales
    $tva       = self::ajouter(1, self::diviser($tauxTva, 100, 8), $nbDecimales + 2);
    $montantHt = self::diviser($montantTtc, $tva, $nbDecimales + 2);

    return round($montantHt, $nbDecimales);
  }

  /**
   * Calcule la taxe d'un montant TTC (utilise essentiellement pour les pdf)
   *
   * @param $montantTtc
   * @param $taux
   * @return unknown_type
   */
  public static function calculerMontantTaxe($montantTtc, $tauxTva, $nbDecimales = 2) {
    $montantHT   = self::calculerMontantHt($montantTtc, $tauxTva, $nbDecimales);
    $montantTaxe = self::soustraire($montantTtc, $montantHT, $nbDecimales);

    return round($montantTaxe, $nbDecimales);
  }

  /**
   * Arrondi selon un nombre de decimales et des separateurs
   *
   * @param string $number
   * @param string $strSeparateurDecimales
   * @param string $strSeparateurMilliers
   * @return mixed
   */
  public static function numberFormat($number, $strSeparateurDecimales = ',', $strSeparateurMilliers = '') {
    $number = str_replace(',', '.', $number);

    return number_format($number, 2, $strSeparateurDecimales, $strSeparateurMilliers);
  }

  /**
   * Arrondi selon un nombre de decimales et un separateur, et rajoute le symbole euros
   *
   * @param string $number
   * @param bool   $bUtf8
   * @param string $strSeparateur
   * @return mixed
   */
  public static function moneyFormatEuro($number, $bUtf8 = true, $strSeparateurDecimales = ',', $decimales = 2) {
    $strEuros = '€';
    $strSpace = ($bUtf8) ? '&nbsp;' : ' ';

    $number = number_format($number, $decimales, $strSeparateurDecimales, ' ') . $strSpace . $strEuros;

    $number = ($bUtf8) ? str_replace('  ', $strSpace, $number) : str_replace(' ', chr("A0"), $number);

    return $number;
  }

  /**
   * Calcul le poids en kilos selon un poids en grammes
   *
   * @param string $grammes
   * @return mixed
   */
  public static function grammesToKg($grammes) {
    return (round(($grammes / 1000), 3));
  }

  /**
   * Remplace le separateur point des decimales en une virgule
   *
   * @param string $number
   * @return mixed
   */
  public static function convertUsFloatToFrFloat($number) {
    return str_replace('.', ',', $number);
  }

  /**
   * Remplace le separateur virgule des decimales en un point
   *
   * @param string $number
   * @return mixed
   */
  public static function convertFrFloatToUsFloat($number) {
    settype($number, "string");
    $number = str_replace(',', '.', $number);
    // Ne pas supprimer, ca evite le bug mysterieux de la virgule...
    $number = str_replace('.', '.', $number);

    return floatval($number);
  }

  /**
   * Transforme des octet en unité plus grande (Mo / Go / ...)
   *
   * @param int $size
   * @param     pour       l'arrondi $precision
   * @param     séparateur entre chiffre et unité
   * @return String
   */
  public static function FileSize($size, $precision = 1, $separateur = '&nbsp;') {
    $size   = abs($size);
    $limite = 1000;
    if ($size < $limite) {
      return $size . $separateur . 'o';
    }
    $aTaille = array('o', 'Ko', 'Mo', 'Go', 'To', 'Po');

    $i = 0;
    while ($size >= $limite) {
      $i++;
      $intitule = Round($size / $limite, $precision) . $separateur . $aTaille[$i];
      $limite   = $limite * 1000;
    }

    return $intitule;
  }

  /**
   * Certify que le nombre est bien un float...
   *
   * @param string $SupposedFloat
   * @return float
   */
  public static function certifyFloat($SupposedFloat) {
    $certifiedFloat = (preg_replace("/,/", ".", $SupposedFloat));
    $certifiedFloat = floatval(preg_replace("/^[^0-9\.]/", "", $certifiedFloat));

    return $certifiedFloat;

  }

}
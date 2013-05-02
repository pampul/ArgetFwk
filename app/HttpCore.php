<?php

class HttpCore{


  public static function initialize(){
    /**
     * Constantes de recuperation en HTTP
     */
    if (self::isGet('pattern'))
      define('GET_PATTERN', self::get('pattern'));
    else
      define('GET_PATTERN', null);

    if (self::isGet('pattern'))
      define('GET_CONTENT', self::get('content'));
    else
      define('GET_CONTENT', 'home');


    /**
     * TimeZone du serveur
     */
    define('TIMEZONE', 'Europe/Paris');

    /**
     * Lancement de la gestion de chrono
     */
    define('MICRO_TIME', microtime(true));

    /**
     * Le site comporte une BDD (false pour non)
     */
    define('CONFIG_REQUIRE_BDD', true);


    /**
     * Méthode de cryptage
     * "SHA512", "BLOWFISH+" et "MD5" disponibles
     * Login Protect = Empêcher les brute force
     */
    define('PASSWORD_METHOD', 'SHA512');
    define('LOGIN_PROTECT', true);

    /**
     * Constantes relatives au navigateur
     */
    define('NAVIGATEUR_NOM', $_SERVER['HTTP_USER_AGENT']);

    /**
     * Constante d'URL
     */
    define('SITE_CURRENT_URI', $_SERVER['REQUEST_URI']);

    define('SITE_URL_BASE', self::getSchemeAndHttpHost());
    define('SITE_URL', SITE_URL_BASE . BACKOFFICE_ACTIVE);
    define('SITE_URL_REFERENCEMENT', '');

  }

  /**
   * Is Get Http méthod
   */
  public static function isGet($str){
    if(isset($_GET[$str]))
      return true;
    else
      return false;
  }

  /**
   * Is Post Http méthod
   */
  public static function isPost($str){
    if(isset($_POST[$str]))
      return true;
    else
      return false;
  }

  /**
   * Get Http méthod
   * @return string
   */
  public static function get($str){
    if(isset($_GET[$str]))
      return $_GET[$str];
    else
      return false;
  }

  /**
   * Post Http méthod
   * @return string
   */
  public static function post($str){
    if(isset($_POST[$str]))
      return $_POST[$str];
    else
      return false;
  }

  /**
   * Check if we are in local files
   * @return int
   */
  public static function isLocalhost(){
    return preg_match("#localhost#", $_SERVER['HTTP_HOST']);
  }

  /**
   * Check if the str in url exist
   * @param $str
   * @return boolean
   */
  public static function isPreprodUrl($str){
    return preg_match("#$str#", $_SERVER['HTTP_HOST']);
  }


  /**
   * Gets the scheme and HTTP host.
   *
   * @return string The schem and HTTP host
   */
  public static function getSchemeAndHttpHost()
  {
    if (self::isLocalhost())
      return self::getScheme().self::getHttpHost().'/'.self::getLocalhostUrl().'/';
    else
      return self::getScheme().self::getHttpHost().'/';
  }

  public static function getLocalhostUrl(){
    $arrayExploded = explode('/', $_SERVER['PHP_SELF']);
    return (isset($arrayExploded[1]) ? $arrayExploded[1] : '');
  }

  /**
   * Returns the host name.
   *
   * @return string
   */
  public static function getHost()
  {
    if ($host = self::getForwardedHost()) {
      $elements = explode(',', $host);

      $host = trim($elements[count($elements) - 1]);
    } else {
      if (!$host = $_SERVER['SERVER_NAME']) {
        $host = $_SERVER['SERVER_ADDR'];
      }
    }

    // Remove port number from host
    $host = preg_replace('/:\d+$/', '', $host);

    return trim($host);
  }

  public static function isForwarded() {
    return array_key_exists('HTTP_X_FORWARDED_HOST', $_SERVER);
  }

  public static function getForwardedHost() {
    return (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : false);
  }


  /**
   * Returns the HTTP host being requested.
   *
   * The port name will be appended to the host if it's non-standard.
   *
   * @return string
   *
   * @api
   */
  public static function getHttpHost()
  {
    $scheme = self::getScheme();
    $port   = self::getPort();

    if (('http://' == $scheme && $port == 80) || ('https://' == $scheme && $port == 443)) {
      return self::getHost();
    }

    return self::getHost().':'.$port;
  }


  /**
   * Gets the request's scheme.
   *
   * @return string
   */
  public static function getScheme()
  {
    return self::isSecure().'://';
  }

  /**
   * Checks whether the request is secure or not.
   *
   * @return String
   */
  public static function isSecure()
  {
    return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
  }

  public static function getPort(){
    return $_SERVER['SERVER_PORT'];
  }




}
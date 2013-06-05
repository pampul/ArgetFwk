<?php

class Translation {

  private static $defaultLang = 'fr';
  private static $lang;
  private static $pathToFile;
  private static $translations;

  public static function initialize($lang = 'fr') {

    self::$lang       = $lang;
    self::$pathToFile = PATH_TO_IMPORTANT_FILES . 'app/translations/' . self::$lang . '.php';
    self::getTranslationsByLang();

  }

  /**
   * Get translations by lang and set on the $translations var
   */
  private static function getTranslationsByLang() {
    if (file_exists(self::$pathToFile))
      require_once self::$pathToFile; elseif (file_exists(PATH_TO_IMPORTANT_FILES . 'app/translations/' . self::$defaultLang . '.php'))
      require_once PATH_TO_IMPORTANT_FILES . 'app/translations/' . self::$defaultLang . '.php'; else {
      self::$translations = array();

      return;
    }

    self::$translations = LangTranslation::getArrayTranslations();

  }

  /**
   * Retourne le mot traduis
   *
   * @param string $wordToTranslate
   * @return string
   */
  public static function translate($wordToTranslate) {
    if (isset(self::$translations[$wordToTranslate]))
      return self::$translations[$wordToTranslate]; elseif (ENV_DEV)
      return "<i class='alert-error alert-mini'>No translations for $wordToTranslate in '" . self::$lang . ".php'</i>"; else
      return '';
  }


}
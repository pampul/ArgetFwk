<?php

/**
 * FwkSecurity : Classe permettant la sécurisation des mots de passe ainsi que le reverse password
 *
 * @author f.mithieux
 */
class FwkSecurity extends FwkManager {

    /**
     * Variable à définir pour crypter en MD5 avec un prefix
     * @var string  $cryptMd5
     */
    private static $ninjaString = 'nInJa_s@Lt_Pwd-StriNg';
    
    private static $shortNinjaString = 'shOrt3n_sTr@';

    /**
     * Directory des fichiers de login
     * @var string  $logsConnectDir
     */
    private static $logsConnectDir = '../logs/login/';

    /**
     * Nombre de tentatives de connection maximum
     * @var integer $nbrConnectionTry
     */
    private static $nbrConnectionTry = 5;
    
    /**
     * Nombre de secondes d'attente
     * @var integer  $nbrOfWaitingSecond
     */
    private static $nbrOfWaitingSecond = 90;

    /**
     * Securisation de mot de passe
     * 
     * @param string $password
     * @param integer $rounds
     * @param string $method
     * @return string
     */
    public static function encryptPassword($password, $encryptMethod = null, $rounds = 5000) {

        $password = trim($password);

        $cryptedPwd = '';
        if(is_null($encryptMethod)) $encryptMethod = PASSWORD_METHOD;
        switch ($encryptMethod) {

            case 'SHA512':
                $cryptedPwd = crypt($password, '$6$rounds=' . $rounds . '$' . self::$ninjaString . '$');
                break;

            case 'BLOWFISH+':
                $salt = '';
                $saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
                for ($i = 0; $i < 22; $i++) {
                    $salt .= $saltChars[array_rand($saltChars)];
                }
                $finalSalt = sprintf('$2a$%02d$', $rounds) . $salt;
                $cryptedPwd = crypt($password, $finalSalt);
                break;

            case 'MD5':
                $cryptedPwd = md5(self::$ninjaString . $password);
                break;
        }

        return $cryptedPwd;
    }

    /**
     * Test du mot de passe
     * 
     * @param string $pwdEntered
     * @param string $pwdHashed
     * @param string $method
     * @return boolean
     */
    public static function comparePassword($pwdEntered, $pwdHashed, $arrayParams = array()) {

        if (LOGIN_PROTECT){
            $result = self::multiLoginProtect($arrayParams);
            if ($result !== true)
                return $result;
        }

        $pwdEntered = trim($pwdEntered);

        $result = false;
        switch (PASSWORD_METHOD) {

            case 'SHA512':
                if (crypt($pwdEntered, $pwdHashed) == $pwdHashed)
                    $result = true;
                break;

            case 'BLOWFISH+':
                if (crypt($pwdEntered, $pwdHashed) == $pwdHashed)
                    $result = true;
                break;

            case 'MD5':
                if (md5(self::encryptPassword($pwdEntered)) == $pwdHashed)
                    $result = true;
                break;
        }

        return $result;
    }

    /**
     * Vérifie le nombre de connexions en fonction d'un timestamp
     * 
     * @param array $arrayParams tableau contenant le login par exemple
     * @param string $pathLogs chemin des logs (facultatif)
     * @return boolean
     */
    public static function multiLoginProtect($arrayParams, $pathLogs = null) {

        if (isset($arrayParams['login'])) {

            $dir = '';
            if (isset($arrayParams['dir']))
                $dir = $arrayParams['dir'];

            if(is_null($pathLogs))
                $fullDir = $dir . self::$logsConnectDir . date('Y-m-d') . '_' . $arrayParams['login'] . '.log';
            else 
                $fullDir = $dir . $pathLogs . date('Y-m-d') . '_' . $arrayParams['login'] . '.log';

            if (file_exists($fullDir)) {
                $fOpen = file($fullDir);
                $lastLine = $fOpen[count($fOpen) - 1];
            }else
                $lastLine = '';
            $file = fopen($fullDir, 'a+');
            if ($lastLine != '' && $lastLine != null) {

                /*
                 * Construction d'une ligne : 
                 * 123123123123 | florian.mithieux@gmail.com | 4
                 * timestamp | login | tentatives
                 */
                if (preg_match('#([0-9]+)\s--\s([0-9:]+)\s--\s([a-zA-Z0-9 \.@-_]+)\s--\s([0-9]+)#', $lastLine, $matches)) {

                    $secondPassed = time() - $matches[1];
                    if(isset($arrayParams['seconds'])) self::$nbrOfWaitingSecond = $arrayParams['seconds'];
                    if ($secondPassed > self::$nbrOfWaitingSecond) {
                        $message = PHP_EOL . time() . ' -- ' . date('H:i') . ' -- ' . $arrayParams['login'] . ' -- 1 | ' . $_SERVER['REMOTE_ADDR'];
                        fwrite($file, $message);
                        fclose($file);
                        return true;
                    } else {
                        $nbrOfTry = $matches[4];
                        if(isset($arrayParams['try'])) self::$nbrConnectionTry = $arrayParams['try'];
                        if ($nbrOfTry >= self::$nbrConnectionTry) {
                            $message = PHP_EOL . PHP_EOL . '      ------ ATTENTE NECESSAIRE ------' . PHP_EOL . $matches[1] . ' -- ' . date('H:i') . ' -- '. $arrayParams['login'] . ' -- ' . $nbrOfTry . ' | ' . $_SERVER['REMOTE_ADDR'];
                            fwrite($file, $message);
                            fclose($file);
                            return self::$nbrOfWaitingSecond - $secondPassed;
                        }
                        $nbrOfTry++;
                        $message = PHP_EOL . $matches[1] . ' -- ' . date('H:i') . ' -- '. $arrayParams['login'] . ' -- ' . $nbrOfTry . ' | ' . $_SERVER['REMOTE_ADDR'];
                        fwrite($file, $message);
                        fclose($file);
                        return true;
                    }
                }else
                    return self::$nbrOfWaitingSecond;
            }else {
                $message = PHP_EOL . time() . ' -- ' . date('H:i') . ' -- '. $arrayParams['login'] . ' -- 1' . ' | ' . $_SERVER['REMOTE_ADDR'];
                fwrite($file, $message);
                fclose($file);
                return true;
            }
        }else
            return true;
    }
    
    /**
     * Génération d'un token complexe crypté
     * 
     * @return string
     */
    public static function generateToken(){
        
        return strtoupper(sha1(crypt(time(), '$6$rounds=' . 4991 . '$' . self::$shortNinjaString)) . md5(self::$ninjaString));
        
    }

}

?>

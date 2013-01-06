<?php

/**
 * FwkErrorHandler : Classe permettant de gérer les exceptions
 * 
 * Si DEV/PRE-PROD : Affichage de l'erreur, et possibilité d'envoi par email
 * Si PROD : Non-affichage de l'erreur et envoi par email obligatoire
 * Ajout d'une fonction log créant un fichier reportant les erreurs
 * Futur-ajout d'envoi d'emails si site en PROD
 * ------
 * Si erreur PHP : Affichage de la page en prod
 * Si exception PHP : Affichage page 404
 *
 * @author f.mithieux
 */
class FwkErrorHandler extends FwkManager{

    /**
     * Objet FwkLog
     * 
     * @var FwkLog $FwkLog
     */
    private static $FwkLog;

    /**
     * Tableau associatif regroupant les erreurs PHP
     * 
     * @var array
     */
    public static $errorTypeStrings = array(
        E_NOTICE => 'Notice',
        E_WARNING => 'Warning',
        E_ERROR => 'Error',
        E_PARSE => 'Parsing Error',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_NOTICE => 'User Notice',
        E_USER_WARNING => 'User Warning',
        E_USER_ERROR => 'User Error',
        E_STRICT => 'Strict Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated Warning'
    );

    /**
     * Execute appliquera le handler à la classe courante sur `__CLASS__::handler`.
     * 
     * @param boolean $logsEnabled
     * @param string $pathToLogs
     */
    public static function execute($path = 'logs/errors/php-errors-logs.txt') {

        set_exception_handler(array(__CLASS__, 'exceptionHandler'));
        set_error_handler(array(__CLASS__, 'errorHandler'));
        register_shutdown_function(array(__CLASS__, 'fatalErrorHandler'));

        self::$FwkLog = new FwkLog($path);
    }

    /**
     * Gestion des erreurs par le ErrorHandler
     *
     * @param integer $code
     *  Le code d'erreur : une constante PHP
     * @param string $message
     *  Le message d'erreur qui va être écrit dans les logs
     * @param string $file
     *  Laisser à null : le fichier où se trouve l'erreur
     * @param integer $line
     *  La ligne où se trouve l'erreur
     */
    public static function errorHandler($code, $message, $file = null, $line = null) {

        // Only log if the error won't be raised to an exception and the error is not `E_STRICT`
        if (ERROR_LOGS_ENABLED && !in_array($code, array(E_STRICT))) {
            self::$FwkLog->createLog();
            self::$FwkLog->pushToLog(
                    sprintf(
                            '%s %s - %s%s%s', __CLASS__, $code, $message, ($file ? " in file $file" : null), ($line ? " on line $line" : null)
                    ), $code, true
            );
        }

        if (ENV_DEV)
            throw new ErrorException($message, 0, $code, $file, $line);
    }

    /**
     * Gestion des erreurs fatales par le ErrorHandler
     *
     */
    public static function fatalErrorHandler() {

        $e = error_get_last();

        if ($e) {
            self::errorHandler($e['type'], $e['message'], $e['file'], $e['line']);
            if (!ENV_DEV)
                header('Location: ' . SITE_URL . 'url-error/error500');
        }
    }

    /**
     * Le handler récupère l'exception, et appelle le "render" correspondant pour afficher l'exception à l'utilisateur
     * Si environnement : prod => Erreur 404
     *
     * @param Exception $e
     *  The Exception object
     * @return string
     *  Le résultat de l'exception retournée
     */
    public static function exceptionHandler(Exception $e) {

        try {

            // Redirection sur page 404 si erreur
            if (!ENV_DEV) {
                if (!headers_sent())
                    header('Location: ' . SITE_URL . 'url-error/error404');
            }

            $exception_type = get_class($e);
            if (class_exists("{$exception_type}Handler") && method_exists("{$exception_type}Handler", 'render')) {
                $class = "{$exception_type}Handler";
            } else {
                $class = __CLASS__;
            }

            // Gestion des exceptions dans un fichier de log
            if (ERROR_LOGS_ENABLED) {
                self::$FwkLog->pushToLog(sprintf(
                                '%s %s - %s%s%s', $class, $e->getCode(), $e->getMessage(), ($e->getFile() ? " in file " . $e->getFile() : null), ($e->getLine() ? " on line " . $e->getLine() : null)
                        ), $e->getCode(), true
                );
            }

            $output = call_user_func(array($class, 'render'), $e);

            if (!headers_sent()) {
                header('Content-Type: text/html; charset=utf-8');
                header(sprintf('Content-Length: %d', strlen($output)));
            }



            echo $output;
            exit;
        } catch (Exception $e) {
            try {
                $output = call_user_func(array('FwkErrorHandler', 'render'), $e);

                if (!headers_sent()) {
                    header('Content-Type: text/html; charset=utf-8');
                    header(sprintf('Content-Length: %d', strlen($output)));
                }

                echo $output;
                exit;
            } catch (Exception $e) {
                $html = '<html>
                        <head>
                            <title>Exception critique !</title>
                            <base href="' . SITE_URL . '" />
                            <link href=\"' . SITE_URL . 'web/lib/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
                        </head>
                        <body>
                            <section id=\"main\" class=\"container\">
                            <br/>
                            <div class=\"hero-unit\">
                            
                            <div class=\"alert alert-error\"><h3>Une exception critique est apparue dans le gestionnaire d\'exceptions : </h3></div>
                            <pre style="font-size: 15px;">
                                <br/><br/><i>' . PHP_EOL;
                $html .= '<div class="alert alert-info"><small><strong>[ ' . $e->getMessage() . ' ]</strong> a la ligne <strong>' . $e->getLine() . '</strong> du fichier <strong>' . $e->getFile() . '</small></strong></div><br/><br/><br/><br/><br/><br/>' . $lines . '<br/><br/>' . $trace;
                $html .= "          </i></pre>
                        </body>
                      </html>";

                echo $html;

                if (ERROR_SEND_EMAIL && !ENV_DEV)
                    self::sendMailToAdmin($html);

                exit;
            }
        }
    }

    /**
     * La fonction de render qui renvoi le texte correspondant à l'erreur
     *
     * @param Exception $e
     *  The Exception object
     * @return string
     *  An HTML string
     */
    public static function render(Exception $e) {

        $lines = NULL;

        foreach (self::__nearByLines($e->getLine(), $e->getFile()) as $line => $string) {
            $lines .= sprintf(
                    '<li%s><strong>%d</strong> <code>%s</code></li>', (($line + 1) == $e->getLine() ? ' class="error" style="background: #ff7676; font-weight: bold;"' : NULL), ++$line, str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', htmlspecialchars($string))
            );
        }

        $trace = NULL;

        foreach ($e->getTrace() as $t) {
            $trace .= sprintf(
                    '<li><code><em>[%s:%d]</em></code></li><li><code>&#160;&#160;&#160;&#160;%s%s%s();</code></li>', (isset($t['file']) ? $t['file'] : NULL), (isset($t['line']) ? $t['line'] : NULL), (isset($t['class']) ? $t['class'] : NULL), (isset($t['type']) ? $t['type'] : NULL), $t['function']
            );
        }

        $html = "<html>
                        <head>
                            <title>Exception critique !</title>
                            <base href='" . SITE_URL . "' />
                            <link href=\"" . SITE_URL . "web/lib/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
                        </head>
                        <body>
                            <section id=\"main\" class=\"container\">
                            <br/>
                            <div class=\"hero-unit\">
                            
                            <div class=\"alert alert-error\"><h3>Une exception est apparue : </h3></div>
                            <pre style='font-size: 15px;'>
                                <br/><br/><i>";

        $html .= '<div class="alert alert-info"><small><strong>[ ' . $e->getMessage() . ' ]</strong> a la ligne <strong>' . $e->getLine() . '</strong> du fichier <strong>' . $e->getFile() . '</strong></small></div><br/><br/><br/><br/><br/><br/>' . $lines . '<br/><br/>' . $trace;

        $html .= sprintf('', ($e instanceof ErrorException ? FwkErrorHandler::$errorTypeStrings[$e->getSeverity()] : 'Fatal Error'), $e->getMessage(), $e->getFile(), $e->getLine(), $lines, $trace
        );

        $html .= "          </i></pre>
                            
                            </div>
                            </section>
                        </body>
                      </html>";

        if (ERROR_SEND_EMAIL && !ENV_DEV && !ENV_LOCALHOST)
            self::sendMailToAdmin($html);

        return $html;
    }

    /**
     * Retourne les lignes précédentes et suivantes de l'erreur pour mieux repérer l'exception
     *
     * @param integer $line
     *  La ligne où l'erreur apparaît
     * @param string $file
     *  Le fichier où se trouve l'erreur
     * @param integer $window
     *  Le nombre de lignes à afficher
     * @return array
     */
    protected static function __nearbyLines($line, $file, $window = 5) {
        return array_slice(file($file), ($line - 1) - $window, $window * 2, true);
    }

    /**
     * Envoi un email à l'administrateur du site
     * Prends comme paramètre le contenu de l'erreur
     * 
     * @param string $html
     * @return bool
     */
    private static function sendMailToAdmin($html) {

        $message = Swift_Message::newInstance();
        $mailer = Swift_MailTransport::newInstance();
        $message->setSubject('Erreur sur le site web : ' . SITE_NOM);
        $message->setFrom(array('postmaster@' . strtolower(SITE_NOM) . '.com' => 'postmaster@' . strtolower(SITE_NOM)));
        $message->setTo(array(ADMIN_EMAIL => ADMIN_PRENOM . ' ' . ADMIN_NOM));
        $message->setBody($html, 'text/html');

        return $mailer->send($message);
    }

}

?>

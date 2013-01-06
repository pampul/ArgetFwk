<?php
/**
 * Copyright (c) 2002-2010 Aurélien Maille
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * 
 * @package Wamailer
 * @author  Bobe <wascripts@phpcodeur.net>
 * @link    http://phpcodeur.net/wascripts/wamailer/
 * @license http://www.gnu.org/copyleft/lesser.html
 * @version $Id: mailer.class.php 28 2010-09-24 17:51:19Z bobe $
 */

require dirname(__FILE__) . '/mime.class.php';

if( function_exists('email') && !function_exists('mail') ) {
	// on est probablement chez l’hébergeur Online
	require dirname(__FILE__) . '/online.php';
}

//
// Compatibilité php < 5.1.1
//
if( !defined('DATE_RFC2822') ) {
	define('DATE_RFC2822', 'D, d M Y H:i:s O');
}

if( !($hostname = @php_uname('n')) ) {
	$hostname = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
}

define('MAILER_HOSTNAME',  $hostname);
define('MAILER_MIME_EOL',  (strncasecmp(PHP_OS, 'Win', 3) != 0) ? "\n" : "\r\n");
define('PHP_USE_SENDMAIL', (ini_get('sendmail_path') != '') ? true : false);
unset($hostname);

/**
 * Classe d’envois d’emails
 * 
 * @todo
 * - Envoi avec SMTP (en cours)
 * - parsing des emails sauvegardés
 * - propagation charset dans les objets entêtes (pour encodage de param)
 * - Ajout de Email::loadFromString() et Email::saveAsString() ?
 * 
 * Les sources qui m’ont bien aidées :
 * 
 * @link http://cvs.php.net/php-src/ext/standard/mail.c
 * @link http://cvs.php.net/php-src/win32/sendmail.c
 */
abstract class Mailer {
	
	/**
	 * Version courante de Wamailer
	 */
	const VERSION = '3.0';
	
	/********************** RÉGLAGES SENDMAIL **********************/
	
	/**
	 * Activation du mode sendmail
	 * 
	 * @var boolean
	 * @access private
	 */
	private static $sendmail_mode = false;
	
	/**
	 * Commande de lancement de sendmail
	 * L’option '-t' indique au programme de récupérer les adresses des destinataires dans les
	 * en-têtes 'To', 'Cc' et 'Bcc' de l’email.
	 * L’option '-i' permet d’éviter que le programme n’interprète une ligne contenant uniquement 
	 * un caractère point comme la fin du message.
	 * 
	 * @var string
	 * @access public
	 */
	public static $sendmail_cmd  = '/usr/sbin/sendmail -t -i';
	
	/********************** RÉGLAGES SMTP **************************/
	
	/**
	 * Activation du mode SMTP
	 * 
	 * @var boolean
	 * @access private
	 */
	private static $smtp_mode = false;
	
	/**
	 * Serveur SMTP à contacter
	 * 
	 * @var string
	 * @access public
	 */
	public static $smtp_server = 'localhost';
	
	/**
	 * Active ou désactive l’utilisation directe de sendmail pour l’envoi des emails
	 * 
	 * @param boolean $use  Active/désactive le mode sendmail
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function useSendmail($use)
	{
		self::$sendmail_mode = $use;
	}
	
	/**
	 * Active ou désactive l’utilisation directe d’un serveur SMTP pour l’envoi des emails
	 * 
	 * @param boolean $use  Active/désactive le mode SMTP
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function useSMTP($use)
	{
		self::$smtp_mode = $use;
	}
	
	/**
	 * Vérifie la validité syntaxique d'un email
	 * 
	 * @param string $email
	 * 
	 * @static
	 * @access public
	 * @return boolean
	 */
	public static function checkMailSyntax($email)
	{
		return (bool) preg_match('/^(?:(?(?<!^)\.)[-!#$%&\'*+\/0-9=?a-z^_`{|}~]+)+@'
			. '(?:(?(?<!@)\.)[a-z0-9](?:[-a-z0-9]{0,61}[a-z0-9])?)+$/i', $email);
	}
	
	/**
	 * Nettoie la liste des adresses destinataires pour supprimer toute
	 * personnalisation ('My name' <my@address.tld>)
	 * 
	 * @param string $addressList
	 * 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function clearAddressList($addressList)
	{
		preg_match_all('/(?<=^|[\s,<])[-!#$%&\'*+\/0-9=?a-z^_`{|}~.]+@[-a-z0-9.]+(?=[\s,>]|$)/Si',
			$addressList, $matches);
		
		return $matches[0];
	}
	
	/**
	 * Envoi d’un email
	 * 
	 * @param Email object $email
	 * 
	 * @static
	 * @access public
	 * @return boolean
	 */
	public static function send(Email $email)
	{
		$email->headers->set('X-Mailer', sprintf('Wamailer/%s', self::VERSION));
		
		$rPath = $email->headers->get('Return-Path');
		if( !is_null($rPath) ) {
			$rPath = trim($rPath->value, '<>');
		}
		
		if( self::$sendmail_mode == true ) {
			$email->headers->get('X-Mailer')->append(' (Sendmail mode)');
			$result = self::sendmail($email->__toString(), null, $rPath);
		}
		else if( self::$smtp_mode == true ) {
			if( !class_exists('Mailer_SMTP') ) {
				require dirname(__FILE__) . '/smtp.class.php';
			}
			
			//
			// Nous devons passer directement les adresses email des destinataires
			// au serveur SMTP.
			// On récupère ces adresses des entêtes To, Cc et Bcc.
			//
			$recipients = array();
			foreach( array('to', 'cc', 'bcc') as $name ) {
				$header = $email->headers->get($name);
				
				if( !is_null($header) ) {
					$addressList = $header->__toString();
					$recipients = array_merge($recipients,
						self::clearAddressList($addressList));
				}
			}
			
			$email->headers->get('X-Mailer')->append(' (SMTP mode)');
			//
			// L’entête Bcc ne doit pas apparaitre dans l’email envoyé.
			// On le supprime donc.
			//
			$email->headers->remove('Bcc');
			
			$result = self::smtpmail($email->__toString(), $recipients, $rPath);
		}
		else {
			$subject = $email->headers->get('Subject');
			$recipients = $email->headers->get('To');
			
			list($headers, $message) = explode("\r\n\r\n", $email->__toString(), 2);
			
			if( !is_null($subject) ) {
				$subject = $subject->__toString();
				$headers = str_replace($subject."\r\n", '', $headers);
				$subject = substr($subject, 9);// on skip le nom de l’en-tête
			}
			
			if( !is_null($recipients) ) {
				$recipients = $recipients->__toString();
				
				if( PHP_USE_SENDMAIL ) {
					/**
					 * Sendmail parse les en-têtes To, Cc et Bcc s’ils sont
					 * présents pour récupérer la liste des adresses destinataire.
					 * On passe déjà la liste des destinataires principaux (To)
					 * en argument de la fonction mail(), donc on supprime l’en-tête To
					 */
					$headers = str_replace($recipients."\r\n", '', $headers);
					$recipients = substr($recipients, 4);// on skip le nom de l’en-tête
				}
				else {
					/**
					 * La fonction mail() ouvre un socket vers un serveur SMTP.
					 * On peut laisser l’en-tête To pour la personnalisation.
					 * Il faut par contre passer une liste d’adresses débarassée
					 * de cette personnalisation en argument de la fonction mail()
					 * sous peine d’obtenir une erreur.
					 */
					$recipients = implode(', ', self::clearAddressList($recipients));
				}
			}
			
			if( PHP_USE_SENDMAIL ) {
				$headers = str_replace("\r\n", MAILER_MIME_EOL, $headers);
				$message = str_replace("\r\n", MAILER_MIME_EOL, $message);
				
				/**
				 * PHP ne laisse passer les longs entêtes Subject et To que
				 * si les plis sont séparés par des séquences \r\n<LWS>,
				 * cela même sur les systèmes UNIX-like.
				 * Cela semble poser problème avec certains serveurs POP ou IMAP
				 * qui interprètent les retours chariots comme des sauts de ligne
				 * et les remplacent comme tels, faussant ainsi le marquage de fin
				 * du bloc d’entêtes de l’email.
				 * On remplace les séquences \r\n<LWS> par une simple espace
				 * 
				 * @see SKIP_LONG_HEADER_SEP routine in
				 *   http://cvs.php.net/php-src/ext/standard/mail.c
				 * @see PHP Bug 24805 at http://bugs.php.net/bug.php?id=24805
				 */
				if( strncasecmp(PHP_OS, 'Win', 3) != 0 ) {
					$subject = str_replace("\r\n\t", ' ', $subject);
					$recipients = str_replace("\r\n\t", ' ', $recipients);
				}
			}
			else {
				/**
				 * La fonction mail() utilise prioritairement la valeur de l’option
				 * sendmail_from comme adresse à passer dans la commande MAIL FROM
				 * (adresse qui sera utilisée par le serveur SMTP pour forger l’entête
				 * Return-Path). On donne la valeur de $rPath à l’option sendmail_from
				 */
				if( !is_null($rPath) ) {
					ini_set('sendmail_from', $rPath);
				}
				
				/**
				 * La fonction mail() va parser elle-même les entêtes Cc et Bcc
				 * pour passer les adresses destinataires au serveur SMTP.
				 * Il est donc indispensable de nettoyer l’entête Cc de toute
				 * personnalisation sous peine d’obtenir une erreur.
				 */
				$header_cc = $email->headers->get('Cc');
				if( !is_null($header_cc) ) {
					$header_cc = $header_cc->__toString();
					$new_header_cc = new Mime_Header('Cc',
						implode(', ', self::clearAddressList($header_cc)));
					$headers = str_replace($header_cc, $new_header_cc->__toString(), $headers);
				}
			}
			
			if( !ini_get('safe_mode') && !is_null($rPath) ) {
				$result = mail($recipients, $subject, $message, $headers, '-f' . $rPath);
			}
			else {
				$result = mail($recipients, $subject, $message, $headers);
			}
			
			if( !PHP_USE_SENDMAIL ) {
				ini_restore('sendmail_from');
			}
		}
		
		return $result;
	}
	
	/**
	 * Envoi via sendmail
	 * 
	 * @param string $email       Email à envoyer
	 * @param string $recipients  Adresses supplémentaires de destinataires
	 * @param string $rPath       Adresse d’envoi (définit le return-path)
	 * 
	 * @access public
	 * @return boolean
	 */
	public static function sendmail($email, $recipients = null, $rPath = null)
	{
		if( !empty(self::$sendmail_cmd) ) {
			$sendmail_cmd = self::$sendmail_cmd;
		}
		else {
			$sendmail_cmd = ini_get('sendmail_path');
		}
		
		$sendmail_path = substr($sendmail_cmd, 0, strpos($sendmail_cmd, ' '));
		
		if( !is_executable($sendmail_path) ) {
			throw new Exception("Mailer::sendmail() : [$sendmail_path] n'est pas un fichier exécutable valide");
		}
		
		if( !is_null($rPath) ) {
			$sendmail_cmd .= ' -f' . escapeshellcmd($rPath);
		}
		
		if( is_array($recipients) && count($recipients) > 0 ) {
			$sendmail_cmd .= ' -- ' . escapeshellcmd(implode(' ', $recipients));
		}
		
		$sendmail = popen($sendmail_cmd, 'wb');
		fputs($sendmail, preg_replace("/\r\n?/", MAILER_MIME_EOL, $email));
		
		if( ($code = pclose($sendmail)) != 0 ) {
			trigger_error("Mailer::sendmail() : Sendmail a retourné le code"
				. " d'erreur suivant -> $code", E_USER_WARNING);
			return false;
		}
		
		return true;
	}
	
	/**
	 * Envoi via la classe smtp
	 * 
	 * @param string $email       Email à envoyer
	 * @param string $recipients  Adresses des destinataires
	 * @param string $rPath       Adresse d’envoi (définit le return-path)
	 * 
	 * @access public
	 * @return boolean
	 */
	public static function smtpmail($email, $recipients, $rPath = null)
	{
		if( !class_exists('Mailer_SMTP') ) {
			require dirname(__FILE__) . '/smtp.class.php';
		}
		
		if( is_null($rPath) ) {
			$rPath = ini_get('sendmail_from');
		}
		
		$smtp = new Mailer_SMTP();
		$smtp->connect(self::$smtp_server);
		
		if( !$smtp->from($rPath) ) {
//			$this->error($smtp->msg_error);
			$GLOBALS['php_errormsg'] = $smtp->responseData;
			$smtp->quit();
			return false;
		}
		
		foreach( $recipients as $recipient ) {
			if( !$smtp->to($recipient) ) {
//				$this->error($smtp->msg_error);
				$GLOBALS['php_errormsg'] = $smtp->responseData;
				$smtp->quit();
				return false;
			}
		}
		
		if( !$smtp->send($email) ) {
//			$this->error($smtp->msg_error);
			$GLOBALS['php_errormsg'] = $smtp->responseData;
			$smtp->quit();
			return false;
		}
		
		$smtp->quit();
		
		return true;
	}
	
	public static function setError() {}
	public static function getError() { return $GLOBALS['php_errormsg']; }
}

class Email {
	/**
	 * Liste de constantes utilisables avec la méthode Email::setPriority()
	 */
	const PRIORITY_HIGHEST = 1;
	const PRIORITY_HIGH    = 2;
	const PRIORITY_NORMAL  = 3;
	const PRIORITY_LOW     = 4;
	const PRIORITY_LOWEST  = 5;
	
	/**
	 * Email du destinataire
	 * 
	 * @var string
	 * @access protected
	 */
	protected $sender = '';
	
	/**
	 * Jeu de caractères générique de l’email (D’autres jeux peuvent être
	 * ultérieurement définis pour des sous-ensembles de l’email)
	 * 
	 * @var string
	 * @access public
	 */
	public $charset = 'ISO-8859-1';
	
	/**
	 * Bloc d’en-têtes de l’email
	 * 
	 * @var object
	 * @see Mime_Headers class
	 * @access protected
	 */
	protected $_headers = null;
	
	/**
	 * Partie texte brut de l’email
	 * 
	 * @var object
	 * @see Mime_Part class
	 * @access protected
	 */
	protected $_textPart = null;
	
	/**
	 * Partie HTML de l’email
	 * 
	 * @var object
	 * @see Mime_Part class
	 * @access protected
	 */
	protected $_htmlPart = null;
	
	/**
	 * Multi-Partie globale de l’email
	 * 
	 * @var array
	 * @access protected
	 */
	protected $_attachParts = array();
	
	/**
	 * @var string
	 * @access protected
	 */
	protected $headers_txt = '';
	
	/**
	 * @var string
	 * @access protected
	 */
	protected $message_txt = '';
	
	/**
	 * Constructeur de classe
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($charset = null)
	{
		$this->_headers = new Mime_Headers(array(
			'Return-Path' => '',
			'Date' => '',
			'From' => '',
			'Sender' => '',
			'Reply-To' => '',
			'To' => '',
			'Cc' => '',
			'Bcc' => '',
			'Subject' => '',
			'Message-ID' => '',
			'MIME-Version' => ''
		));
		
		if( !is_null($charset) ) {
			$this->charset = $charset;
		}
	}
	
	/**
	 * Charge un email à partir d’un fichier mail
	 * 
	 * @param string  $filename
	 * @param boolean $fullParse
	 * 
	 * @access public
	 * @return mixed
	 */
	public function load($filename, $fullParse = false)
	{
		if( !is_readable($filename) ) {
			throw new Exception("Cannot read file '$filename'");
		}
		
		$input = file_get_contents($filename);
		$input = preg_replace('/\r\n?|\n/', "\r\n", $input);
		list($headers, $message) = explode("\r\n\r\n", $input, 2);
		
		if( !isset($this) || !($this instanceof Email) ) {
			$email = new Email();
			$returnBool = false;
		}
		else {
			$email = $this;
			$returnBool = true;
		}
		
		$headers = preg_split("/\r\n(?![\x09\x20])/", $headers);
		foreach( $headers as $header ) {
			if( strpos($header, ':') ) {// Pour esquiver l’éventuelle ligne From - ...
				list($name, $value) = explode(':', $header, 2);
				$email->headers->add($name, $value);
			}
		}
		
/*		if( $fullParse ) {
			$sender = $email->headers->get('Return-Path');
			if( !is_null($sender) ) {
				$email->sender = trim($sender->value, '<>');
			}
			
			// @todo
			// Récupération charset "global"
			// + structure, si compatible
			// + attention, headers du premier mime_part se trouvent dans
			// le Mime_Headers de l’objet Email
			
			$contentType = $email->headers->get('Content-Type');
			$boundary = $contentType->param('boundary');
			$charset  = $contentType->param('charset');
			
			if( !is_null($contentType) && strncasecmp($contentType->value, 'multipart', 9) == 0 ) {
				if( is_null($boundary) ) {
					throw new Exception("Bad mime part (missed boundary)");
				}
				
				
			}
			else {
				switch( $contentType->value ) {
					case 'text/plain':
						$email->setTextBody($message, $charset);
						break;
					case 'text/html':
						$email->setHTMLBody($message, $charset);
						break;
					default:
						// C'est donc un fichier attaché seul
						break;
				}
			}
		}
		else {*/
			$email->message_txt = "\r\n".$message;
//		}
		
		return $returnBool ? true : $email;
	}
	
	/**
	 * Sauvegarde l’email dans un fichier
	 * 
	 * @param string $filename
	 * 
	 * @access public
	 * @return void
	 */
	public function save($filename)
	{
		if( !(file_exists($filename) && is_writable($filename)) && !is_writable(dirname($filename)) ) {
			throw new Exception("Cannot write file '$filename'");
		}
		
		file_put_contents($filename, $this->__toString());
	}
	
	/**
	 * @param string $email  Email de l’expéditeur
	 * @param string $name   Personnalisation du nom de l’expéditeur
	 * 
	 * @access public
	 * @return void
	 */
	public function setFrom($email, $name = null)
	{
		$email = $this->sender = trim($email);
		
		if( !is_null($name) ) {
			$email = sprintf('%s <%s>',
				Mime::encodeHeader('From', $name, $this->charset, 'phrase'), $email);
		}
		
		$this->headers->set('From', $email);
	}
	
	/**
	 * @param string $email  Email du destinataire ou tableau contenant la liste des destinataires
	 * @param string $name   Personnalisation du nom du destinataire
	 * 
	 * @access public
	 * @return void
	 */
	public function addRecipient($email, $name = null)
	{
		$this->_addRecipient($email, $name, 'To');
	}
	
	/**
	 * @param string $email  Email du destinataire ou tableau contenant la liste des destinataires
	 * @param string $name   Personnalisation du nom du destinataire
	 * 
	 * @access public
	 * @return void
	 */
	public function addCCRecipient($email, $name = null)
	{
		$this->_addRecipient($email, $name, 'Cc');
	}
	
	/**
	 * @param string $email  Email du destinataire ou tableau contenant la liste des destinataires
	 * 
	 * @access public
	 * @return void
	 */
	public function addBCCRecipient($email)
	{
		$this->_addRecipient($email, null, 'Bcc');
	}
	
	/**
	 * @param string $email   Email du destinataire ou tableau contenant la liste des destinataires
	 * @param string $name    Personnalisation du nom du destinataire
	 * @param string $header  Nom de l’en-tête concerné (parmi To, Cc et Bcc)
	 * 
	 * @access private
	 * @return void
	 */
	private function _addRecipient($email, $name, $header)
	{
		$email = trim($email);
		
		if( !is_null($name) ) {
			$email = sprintf('%s <%s>',
				Mime::encodeHeader($header, $name, $this->charset, 'phrase'), $email);
		}
		
		if( is_null($this->headers->get($header)) ) {
			$this->headers->set($header, $email);
		}
		else {
			$this->headers->get($header)->append(', ' . $email);
		}
	}
	
	/**
	 * @param string $email  email de réponse
	 * @param string $name   Personnalisation
	 * 
	 * @access public
	 * @return void
	 */
	public function setReplyTo($email = null, $name = null)
	{
		if( !is_null($email) ) {
			$email = trim($email);
			
			if( !is_null($name) ) {
				$email = sprintf('%s <%s>', Mime::encodeHeader('Reply-To',
					$name, $this->charset, 'phrase'), $email);
			}
		}
		else {
			$email = $this->headers->get('From')->value;
		}
		
		$this->headers->set('Reply-To', $email);
	}
	
	/**
	 * @param string $email  email pour la notification de lecture (par défaut,
	 *                       l’adresse d’expéditeur est utilisée)
	 * 
	 * @access public
	 * @return void
	 */
	public function setNotify($email = null)
	{
		$this->headers->add(
			'Disposition-Notification-To',
			'<' . (( !is_null($email) ) ? trim($email) : $this->sender) . '>'
		);
	}
	
	/**
	 * Définition de l’adresse de retour pour les emails d’erreurs
	 * 
	 * @param string $email  mail de retour d’erreur (par défaut, l’adresse
	 *                       d’expéditeur est utilisée)
	 * 
	 * @access public
	 * @return void
	 */
	public function setReturnPath($email = null)
	{
		$this->headers->set(
			'Return-Path',
			'<' . (( !is_null($email) ) ? trim($email) : $this->sender) . '>'
		);
	}
	
	/**
	 * Définition du niveau de priorité de l’email
	 * 
	 * @param integer $priority  Niveau de priorité de l’email
	 * 
	 * @access public
	 * @return void
	 */
	public function setPriority($priority)
	{
		if( is_numeric($priority) ) {
			$this->headers->set('X-Priority', $priority);
		}
	}
	
	/**
	 * @param string $str  Nom de l’organisation/entreprise/etc émettrice
	 * 
	 * @access public
	 * @return void
	 */
	public function organization($str)
	{
		$this->headers->set('Organization',
			Mime::encodeHeader('Organization', $str, $this->charset));
	}
	
	/**
	 * @param string $subject  Le sujet de l’email
	 * 
	 * @access public
	 * @return void
	 */
	public function setSubject($subject)
	{
		$this->headers->set('Subject',
			Mime::encodeHeader('Subject', $subject, $this->charset));
	}
	
	/**
	 * @param string $message  Le message de l’email en texte brut
	 * @param string $charset  Jeu de caractères de la chaîne contenue dans $message
	 * 
	 * @access public
	 * @return Mime_Part
	 */
	public function setTextBody($message, $charset = null)
	{
		if( is_null($charset) ) {
			$charset = $this->charset;
		}
		
		$this->_textPart = new Mime_Part($message);
		$this->_textPart->headers->set('Content-Type', 'text/plain');
		$this->_textPart->headers->set('Content-Transfer-Encoding', '8bit');
		$this->_textPart->headers->get('Content-Type')->param('charset', $charset);
		$this->message_txt = '';
		
		return $this->_textPart;
	}
	
	/**
	 * @param string $message  Le message de l’email au format HTML
	 * @param string $charset  Jeu de caractères de la chaîne contenue dans $message
	 * 
	 * @access public
	 * @return Mime_Part
	 */
	public function setHTMLBody($message, $charset = null)
	{
		if( is_null($charset) ) {
			$charset = $this->charset;
		}
		
		$this->_htmlPart = new Mime_Part($message);
		$this->_htmlPart->headers->set('Content-Type', 'text/html');
		$this->_htmlPart->headers->set('Content-Transfer-Encoding', '8bit');
		$this->_htmlPart->headers->get('Content-Type')->param('charset', $charset);
		$this->message_txt = '';
		
		return $this->_htmlPart;
	}
	
	/**
	 * Attache un fichier comme pièce jointe de l’email
	 * 
	 * @param string $filename     Chemin vers le fichier
	 * @param string $name         Nom de fichier
	 * @param string $type         Type MIME du fichier
	 * @param string $disposition  Disposition
	 * 
	 * @access public
	 * @return Mime_Part
	 */
	public function attach($filename, $name = '', $type = '', $disposition = '')
	{
		if( !is_readable($filename) ) {
			throw new Exception("Cannot read file '$filename'");
		}
		
		if( empty($name) ) {
			$name = $filename;
		}
		
		if( empty($type) ) {
			$type = Mime::getType($filename);
		}
		
		return $this->attachFromString(file_get_contents($filename), $name, $type, $disposition);
	}
	
	/**
	 * Attache un contenu comme pièce jointe de l’email
	 * 
	 * @param string $data         Données à joindre
	 * @param string $name         Nom de fichier
	 * @param string $type         Type MIME des données
	 * @param string $disposition  Disposition
	 * 
	 * @access public
	 * @return Mime_Part
	 */
	public function attachFromString($data, $name, $type = 'application/octet-stream', $disposition = '')
	{
		if( $disposition != 'inline' ) {
			$disposition = 'attachment';
		}
		$name = basename($name);
		
		$attach = new Mime_Part($data);
		$attach->headers->set('Content-Type', $type);
		$attach->headers->get('Content-Type')->param('name', $name);
		$attach->headers->set('Content-Disposition', $disposition);
		$attach->headers->get('Content-Disposition')->param('filename', $name);
		$attach->headers->get('Content-Disposition')->param('size', strlen($data));
		$attach->headers->set('Content-Transfer-Encoding', 'base64');
		
		array_push($this->_attachParts, $attach);
		$this->message_txt = '';
		
		return $attach;
	}
	
	/**
	 * Retourne l’email sous forme de chaîne formatée prète à l’envoi
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		$this->headers->set('Date', date(DATE_RFC2822));
		$this->headers->set('MIME-Version', '1.0');
		$this->headers->set('Message-ID', sprintf('<%s@%s>', md5(microtime().rand()), MAILER_HOSTNAME));
		
		$this->headers_txt = $this->headers->__toString();
		if( !empty($this->message_txt) ) {
			return $this->headers_txt . $this->message_txt;
		}
		
		$rootPart = null;
		$attachParts = $this->_attachParts;
		
		if( !is_null($this->_htmlPart) ) {
			$rootPart = $this->_htmlPart;
			
			if( !is_null($this->_textPart) ) {
				$rootPart = new Mime_Part();
				$rootPart->addSubPart($this->_textPart);
				$rootPart->addSubPart($this->_htmlPart);
				$rootPart->headers->set('Content-Type', 'multipart/alternative');
			}
			
			$embedParts = array();
			foreach( $attachParts as &$attach ) {
				if( $attach->headers->get('Content-ID') == null ) {
					$name = $attach->headers->get('Content-Type')->param('name');
					$regexp = '/<([^>]+=\s*)(["\'])cid:' . preg_quote($name, '/') . '\\2([^>]*)>/S';
					
					if( !preg_match($regexp, $this->_htmlPart->body) ) {
						continue;
					}
					
					$cid = md5(microtime()) . '@' . MAILER_HOSTNAME;
					$this->_htmlPart->body = preg_replace($regexp,
						'<\\1\\2cid:' . $cid . '\\2\\3>', $this->_htmlPart->body);
					$attach->headers->set('Content-ID', "<$cid>");
				}
				
				array_push($embedParts, $attach);
				$attach = null;
			}
			
			if( count($embedParts) > 0 ) {
				$embedPart = new Mime_Part();
				$embedPart->addSubPart($rootPart);
				$embedPart->addSubPart($embedParts);
				$embedPart->headers->set('Content-Type', 'multipart/related');
				$embedPart->headers->get('Content-Type')->param('type',
					$rootPart->headers->get('Content-Type')->value);
				$rootPart = $embedPart;
			}
		}
		else if( !is_null($this->_textPart) ) {
			$rootPart = $this->_textPart;
		}
		
		// filtrage nécessaire après la boucle de traitement des objets embarqués plus haut
		$attachParts = array_filter($attachParts);
		
		if( count($attachParts) > 0 ) {
			if( !is_null($rootPart) ) {
				$mixedPart = new Mime_Part();
				$mixedPart->headers->set('Content-Type', 'multipart/mixed');
				$mixedPart->addSubPart($rootPart);
				$mixedPart->addSubPart($attachParts);
			}
			else if( count($attachParts) == 1 ) {
				$mixedPart = $attachParts[0];
			}
			else {
				$mixedPart = new Mime_Part();
				$mixedPart->headers->set('Content-Type', 'multipart/mixed');
				$mixedPart->addSubPart($attachParts);
			}
			
			$rootPart = $mixedPart;
		}
		
		//
		// Le corps d’un email est optionnel (cf. RFC 2822#3.5)
		//
		if( !is_null($rootPart) ) {
			//
			// Par convention, un bref message informatif est ajouté aux emails
			// composés de plusieurs sous-parties, au cas où le client mail
			// ne supporterait pas ceux-ci…
			//
			if( strncasecmp($rootPart->headers->get('Content-Type')->value, 'multipart', 9) == 0 ) {
				$rootPart->body = "This is a multi-part message in MIME format.";
			}
			
			$this->message_txt = $rootPart->__toString();
		}
		
		return $this->headers_txt . $this->message_txt;
	}
	
	public function __set($name, $value)
	{
		switch( $name ) {
			case 'headers':
			case 'textPart':
			case 'htmlPart':
				throw new Exception("Cannot setting $name attribute");
				break;
		}
	}
	
	public function __get($name)
	{
		switch( $name ) {
			case 'headers':
			case 'textPart':
			case 'htmlPart':
				return $this->{'_'.$name};
				break;
		}
	}
	
	public function __clone()
	{
		$this->_headers = clone $this->_headers;
		
		if( !is_null($this->_textPart) ) {
			$this->_textPart = clone $this->_textPart;
		}
		
		if( !is_null($this->_htmlPart) ) {
			$this->_htmlPart = clone $this->_htmlPart;
		}
		
		foreach( $this->_attachParts as &$attach ) {
			$attach = clone $attach;
		}
	}
}

?>

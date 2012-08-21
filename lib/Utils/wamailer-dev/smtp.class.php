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
 * @version $Id: smtp.class.php 28 2010-09-24 17:51:19Z bobe $
 * 
 * @see RFC 2821 - Simple Mail Transfer Protocol
 * @see RFC 2554 - SMTP Service Extension for Authentication
 * 
 * Les sources qui m'ont bien aidées :
 * 
 * @link http://abcdrfc.free.fr/ (français)
 * @link http://www.faqs.org/rfcs/ (anglais)
 * @link http://www.commentcamarche.net/internet/smtp.php3
 * @link http://www.interpc.fr/mapage/billaud/telmail.htm
 */

class Mailer_SMTP {
	/**
	 * Socket de connexion au serveur SMTP
	 * 
	 * @var resource
	 * @access private
	 */
	private $socket;
	
	/**
	 * Nom ou IP du serveur smtp à contacter
	 * 
	 * @var string
	 * @access private
	 */
	private $server;
	
	/**
	 * Port d'accès
	 * 
	 * @var integer
	 * @access private
	 */
	private $port;
	
	/**
	 * Nom d'utilisateur pour l’authentification
	 * 
	 * @var string
	 * @access private
	 */
	private $username;
	
	/**
	 * Mot de passe pour l’authentification
	 * 
	 * @var string
	 * @access private
	 */
	private $passwd;
	
	public  $timeout    = 3;
	public  $debug      = false;
	public  $save_log   = false;
	public  $filename   = '/var/log/wamailer_smtp.log';
	private $logstr     = '';
	private $eol        = "\r\n";// pour la sortie standard
	private $fromCalled = false;
	
	private $_responseCode;
	private $_responseData;
	
	public function __construct()
	{
		if( strncasecmp(PHP_OS, 'Win', 3) != 0 ) {
			$this->eol = "\n";
		}
		
		if( is_null($this->server) ) {
			$this->server = ini_get('SMTP');
			$this->port   = ini_get('smtp_port');
		}
	}
	
	/**
	 * Établit la connexion au serveur SMTP
	 * 
	 * @param string  $server    Nom ou IP du serveur
	 * @param integer $port      Port d'accès
	 * @param string  $username  Nom d'utilisateur pour l’authentification (si nécessaire)
	 * @param string  $passwd    Mot de passe pour l’authentification (si nécessaire)
	 * 
	 * @access public
	 * @return boolean
	 */
	public function connect($server = null, $port = null, $username = null, $passwd = null)
	{
		foreach( array('server', 'port', 'username', 'passwd') as $varname ) {
			if( is_null($$varname) ) {
				$$varname = $this->{$varname};
			}
		}
		
		if( !($hostname = @php_uname('n')) ) {
			$hostname = isset($_SERVER['SERVER_NAME']) ?
				$_SERVER['SERVER_NAME'] : 'localhost';
		}
		
		$this->_responseCode = null;
		$this->_responseData = null;
		$this->logstr = '';
		
		//
		// Ouverture du socket de connexion au serveur SMTP
		//
		if( !($this->socket = fsockopen($server, $port, $errno, $errstr, $this->timeout)) ) {
			throw new Exception("Échec lors de la connexion au serveur smtp ($errno - $errstr)");
		}
		
		// 
		// Code success : 220
		// Code failure : 421
		//
		if( !$this->checkResponse(220) ) {
			return false;
		}
		
		//
		// Comme on est poli, on dit bonjour, et on s'authentifie le cas échéant
		// 
		// Code success : 250
		// Code error   : 500, 501, 504, 421
		//
		$this->put(sprintf("EHLO %s\r\n", $hostname));
		if( !$this->checkResponse(250) ) {
			$this->put(sprintf("HELO %s\r\n", $hostname));
			if( !$this->checkResponse(250) ) {
				return false;
			}
		}
		
		if( !is_null($username) && !is_null($passwd) ) {
			return $this->authenticate($username, $passwd);
		}
		
		return true;
	}
	
	public function isConnected()
	{
		return is_resource($this->smtp->socket);
	}
	
	public function put($input)
	{
		$this->log($input);
		fputs($this->socket, $input);
	}
	
	public function get()
	{
		while( $data = fgets($this->socket, 512) ) {
			$this->log($data);
			$this->_responseData = rtrim($data);
			
			if( substr($data, 3, 1) == ' ' ) {
				$this->_responseCode = substr($data, 0, 3);
				break;
			}
		}
		
		return $this->_responseCode;
	}
	
	public function checkResponse()
	{
		$codesOK = array();
		$numargs = func_num_args();
		
		for( $i = 0; $i < $numargs; $i++ ) {
			$arg = func_get_arg($i);
			array_push($codesOK, $arg);
		}
		
		$this->get();
		
		if( !in_array($this->_responseCode, $codesOK) )
		{// TODO afficher erreur comme ds wamailer 2.x ?
			return false;
		}
		
		return true;
	}
	
	/**
	 * Authentification auprès du serveur, s’il le supporte
	 * 
	 * @param string $username
	 * @param string $passwd
	 * 
	 * @access public
	 * @return boolean
	 */
	public function authenticate($username, $passwd)
	{
		$this->put("AUTH LOGIN\r\n");
		if( !$this->checkResponse(334) ) {
			return false;
		}
		
		$this->put(base64_encode($username). "\r\n");
		if( !$this->checkResponse(334) ) {
			return false;
		}
		
		$this->put(base64_encode($passwd) . "\r\n");
		if( !$this->checkResponse(235) ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Envoit la commande MAIL FROM
	 * Ceci indique au serveur SMTP l’adresse email expéditrice
	 * 
	 * @param string $email
	 * 
	 * @access public
	 * @return boolean
	 */
	public function from($email = null)
	{
		$this->fromCalled = true;
		if( is_null($email) ) {
			$email = ini_get('sendmail_from');
		}
		
		//
		// Code success : 250
		// Code failure : 552, 451, 452
		// Code error   : 500, 501, 421
		//
		$this->put(sprintf("MAIL FROM:<%s>\r\n", $email));
		
		return $this->checkResponse(250);
	}
	
	/**
	 * Envoit la commande RCPT TO
	 * Ceci indique au serveur SMTP l’adresse email du destinataire
	 * Cette commande doit être invoquée autant de fois qu’il y a de destinataire.
	 * Si la méthode from() n’a pas été appelée auparavant, elle est appelée
	 * automatiquement.
	 * 
	 * @param string  $email
	 * @param boolean $strict (si true, retourne true uniquement si code 250)
	 * 
	 * @access public
	 * @return boolean
	 */
	public function to($email, $strict = false)
	{
		if( !$this->fromCalled ) {
			$this->from();
		}
		
		//
		// Code success : 250, 251
		// Code failure : 550, 551, 552, 553, 450, 451, 452
		// Code error   : 500, 501, 503, 421
		//
		$this->put(sprintf("RCPT TO:<%s>\r\n", $email));
		
		return $strict ? $this->checkResponse(250) : $this->checkResponse(250, 251);
	}
	
	public function send($email)
	{
		//
		// Compatibilité Wamailer 2.x
		// Si les entêtes et le corps de l’email sont fournis séparément,
		// on les concatène.
		//
		if( func_num_args() == 2 ) {
			$email  = rtrim($email);
			$email .= "\r\n\r\n" . func_get_arg(1);
		}
		
		$email = preg_replace('/\r\n?|\n/', "\r\n", $email);
		
		//
		// Si un point se trouve en début de ligne, on le double pour éviter
		// que le serveur ne l’interprète comme la fin de l’envoi.
		//
		$email = str_replace("\r\n.", "\r\n..", $email);
		
		//
		// On indique au serveur que l’on va lui livrer les données
		// 
		// Code intermédiaire : 354
		//
		$this->put("DATA\r\n");
		if( !$this->checkResponse(354) ) {
			return false;
		}
		
		// On envoie l’email proprement dit
		$this->put($email . "\r\n");
		
		//
		// On indique la fin des données au serveur
		// 
		// Code success : 250
		// Code failure : 552, 554, 451, 452
		// Code error   : 500, 501, 503, 421
		//
		$this->put("\r\n.\r\n");
		if( !$this->checkResponse(250) ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Envoi la commande NOOP
	 * 
	 * @access public
	 * @return boolean
	 */
	public function noop()
	{
		/**
		 * Code success : 250
		 * Code error   : 500, 421
		 */
		$this->put("NOOP\r\n");
		
		return $this->checkResponse(250);
	}
	
	/**
	 * Envoi la commande RSET
	 * 
	 * @access public
	 * @return boolean
	 */
	public function reset()
	{
		/**
		 * Code success : 250
		 * Code error   : 500, 501, 504, 421
		 */
		$this->put("RSET\r\n");
		
		return $this->checkResponse(250);
	}
	
	/**
	 * Envoi la commande VRFY
	 * 
	 * @access public
	 * @return boolean
	 */
	public function verify($str)
	{
		/**
		 * Code success : 250, 251
		 * Code error   : 500, 501, 502, 504, 421
		 * Code failure : 550, 551, 553
		 */
		$this->put(sprintf("VRFY %s\r\n", $str));
		
		return $this->checkResponse(250, 251);
	}
	
	/**
	 * Envoi la commande QUIT
	 * Termine le dialogue avec le serveur SMTP et ferme le socket de connexion
	 * 
	 * @access public
	 * @return void
	 */
	public function quit()
	{
		/**
		 * Comme on est poli, on dit au revoir au serveur avec la commande adéquat QUIT 
		 *
		 * Code success : 221
		 * Code failure : 500
		 */
		if( is_resource($this->socket) ) {
			$this->put("QUIT\r\n");
			fclose($this->socket);
			$this->socket = null;
		}
		
		if( $this->save_log ) {
			if( $fw = fopen($this->filename, 'w') ) {
				$logstr  = 'Connexion au serveur ' . $this->server . ' :: ' . date('d/M/Y H:i:s O');
				$logstr .= $this->eol . '--------------------' . $this->eol;
				$logstr .= $this->logstr . $this->eol . $this->eol;
				
				fwrite($fw, $logstr);
				fclose($fw);
			}
		}
	}
	
	private function log($str)
	{
		$str = str_replace("\r\n", $this->eol, $str);
		if( $this->debug ) {
			echo $str;
			flush();
		}
		
		$this->logstr .= $str;
	}
	
	public function __get($name)
	{
		switch( $name ) {
			case 'responseCode':
			case 'responseData':
				return $this->{'_'.$name};
				break;
		}
	}
}

?>
